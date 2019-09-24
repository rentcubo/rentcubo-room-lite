<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper, App\Helpers\HostHelper;

use App\Repositories\HostRepository as HostRepo;

use DB, Log, Hash, Validator, Exception, Setting;

use App\Provider;

use App\User;

use App\Category, App\SubCategory;

use App\Lookups, App\StaticPage;

use App\Host, App\HostGallery, App\HostInventory;

use App\Booking, App\BookingPayment;

use App\BookingProviderReview, App\BookingUserReview;

use Carbon\Carbon;

class ProviderApiController extends Controller {

    protected $loginProvider, $skip, $take, $timezone, $currency;

    public function __construct(Request $request) {

        Log::info(url()->current());

        $this->skip = $request->skip ?: 0;

        $this->take = $request->take ?: (Setting::get('admin_take_count') ?: TAKE_COUNT);

        $this->currency = Setting::get('currency', '$');

        $this->loginProvider = Provider::CommonResponse()->find($request->id);

        if($this->loginProvider) {

            $this->timezone = $this->loginProvider->timezone ?: "America/New_York";
        }

    }

    /**
     * @method register()
     *
     * @uses Registered provider can register through manual or social login
     * 
     * @created Vidhya R 
     *
     * @updated Vidhya R
     *
     * @param Form data
     *
     * @return Json response with provider details
     */
    public function register(Request $request) {

        try {

            DB::beginTransaction();

            // Validate the common and basic fields

            $basicValidator = Validator::make($request->all(),
                [
                    'device_type' => 'required|in:'.DEVICE_ANDROID.','.DEVICE_IOS.','.DEVICE_WEB,
                    'device_token' => 'required',
                    'login_by' => 'required|in:manual,facebook,google',
                ]
            );

            if($basicValidator->fails()) {

                $error = implode(',', $basicValidator->messages()->all());

                throw new Exception($error , 101);

            } else {

                $allowedSocialLogin = ['facebook','google'];

                if (in_array($request->login_by,$allowedSocialLogin)) {

                    // validate social registration fields

                    $socialValidator = Validator::make($request->all(),
                                [
                                    'social_unique_id' => 'required',
                                    'name' => 'required|max:255|min:2',
                                    'email' => 'required|email|max:255',
                                    'mobile' => 'digits_between:6,13',
                                    'picture' => '',
                                    'gender' => 'in:male,female,others',
                                ]
                            );

                    if ($socialValidator->fails()) {

                        $error = implode(',', $socialValidator->messages()->all());

                        throw new Exception($error , 101);

                    }

                } else {

                    // Validate manual registration fields

                    $manualValidator = Validator::make($request->all(),
                        [
                            'name' => 'required|max:255',
                            'email' => 'required|email|max:255|min:2',
                            'password' => 'required|min:6',
                            'picture' => 'mimes:jpeg,jpg,bmp,png',
                        ]
                    );

                    // validate email existence

                    $emailValidator = Validator::make($request->all(),
                        [
                            'email' => 'unique:providers,email',
                        ]
                    );

                    if($manualValidator->fails()) {

                        $error = implode(',', $manualValidator->messages()->all());

                        throw new Exception($error , 101);
                        
                    } else if($emailValidator->fails()) {

                        $error = implode(',', $emailValidator->messages()->all());

                        throw new Exception($error , 101);

                    } 

                }

                $provider_details = Provider::where('email' , $request->email)->first();

                $send_email = DEFAULT_FALSE;

                // Creating the provider

                if(!$provider_details) {

                    $provider_details = new Provider;

                    register_mobile($request->device_type);

                    $send_email = DEFAULT_TRUE;

                    $provider_details->picture = asset('placeholder.jpg');

                    $provider_details->registration_steps = 1;


                } else {

                    if (in_array($provider_details->status , [PROVIDER_PENDING , PROVIDER_DECLINED])) {

                        throw new Exception(Helper::error_message(1000) , 1000);
                    
                    }

                }

                if($request->has('name')) {

                    $provider_details->name = $request->name;

                }

                if($request->has('email')) {

                    $provider_details->email = $request->email;

                }

                if($request->has('mobile')) {

                    $provider_details->mobile = $request->mobile;

                }

                if($request->has('password')) {

                    $provider_details->password = Hash::make($request->password ?: "123456");

                }

                $provider_details->gender = $request->has('gender') ? $request->gender : "male";

                $provider_details->payment_mode = COD;

                $provider_details->token = Helper::generate_token();

                $provider_details->token_expiry = Helper::generate_token_expiry();

                $check_device_exist = Provider::where('device_token', $request->device_token)->first();

                if($check_device_exist) {

                    $check_device_exist->device_token = "";

                    $check_device_exist->save();
                }

                $provider_details->device_token = $request->has('device_token') ? $request->device_token : "";

                $provider_details->device_type = $request->has('device_type') ? $request->device_type : DEVICE_WEB;

                $provider_details->login_by = $request->has('login_by') ? $request->login_by : 'manual';

                $provider_details->social_unique_id = $request->has('social_unique_id') ? $request->social_unique_id : '';

                // Upload picture

                if($request->login_by == "manual") {

                    if($request->hasFile('picture')) {

                        $provider_details->picture = Helper::upload_file($request->file('picture') , PROFILE_PATH_PROVIDER);

                    }

                } else {

                    $provider_details->is_verified = PROVIDER_EMAIL_VERIFIED;

                    $provider_details->picture = $request->picture ?: $provider_details->picture;

                }   

                if ($provider_details->save()) {
                    
                    // Send welcome email to the new provider:

                    if($send_email) {

                        if ($provider_details->login_by == 'manual') {

                            $provider_details->password = $request->password;

                            $subject = tr('provider_welcome_title').' '.Setting::get('site_name');

                            $email_data = $provider_details;

                            $page = "emails.providers.welcome";

                            $email = $provider_details->email;

                            $email_send_response = Helper::send_email($page,$subject,$email,$email_data);

                            // No need to throw error. For forgot password we need handle the error response
                            if($email_send_response->success) {

                            } else {

                                $error = $email_send_response->error;

                                Log::info("Registered EMAIL Error".print_r($error , true));
                                
                            }

                        }

                    }

                    if(in_array($provider_details->status , [PROVIDER_DECLINED , PROVIDER_PENDING])) {

                        // !!!! NOTE: 1007 - Is only for message, don't change
                    
                        $response = ['success' => false , 'error' => Helper::error_message(1007) , 'error_code' => 1000];

                        DB::commit();

                        return response()->json($response, 200);
                   
                    }

                    if ($provider_details->is_verified == PROVIDER_EMAIL_VERIFIED) {

                        $data = Provider::CommonResponse()->find($provider_details->id);

                        $response_array = ['success' => true, 'data' => $data];

                    } else {

                        $response_array = ['success'=>false, 'error' => Helper::error_message(1001), 'error_code'=>1001];

                        DB::commit();

                        return response()->json($response_array, 200);

                    }

                } else {

                    throw new Exception(Helper::error_message(103), 103);

                }

            }

            DB::commit();

            return response()->json($response_array, 200);

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            $code = $e->getCode();

            $response_array = ['success'=>false, 'error'=>$error, 'error_code'=>$code];

            return response()->json($response_array);

        }
   
    }

    /**
     * @method login()
     *
     * @uses Registered provider can login using their email & password
     * 
     * @created Vidhya R 
     *
     * @updated Vidhya R
     *
     * @param object $request - provider Email & Password
     *
     * @return Json response with provider details
     */
    public function login(Request $request) {

        try {

            DB::beginTransaction();

            $basicValidator = Validator::make($request->all(),
                [
                    'device_token' => 'required',
                    'device_type' => 'required|in:'.DEVICE_ANDROID.','.DEVICE_IOS.','.DEVICE_WEB,
                    'login_by' => 'required|in:manual,facebook,google',
                ]
            );

            if($basicValidator->fails()){

                $error = implode(',', $basicValidator->messages()->all());

                throw new Exception($error , 101);

            } else {

                /*validate manual login fields*/

                $manualValidator = Validator::make($request->all(),
                    [
                        'email' => 'required|email',
                        'password' => 'required',
                    ]
                );

                if ($manualValidator->fails()) {

                    $error = implode(',', $manualValidator->messages()->all());

                    throw new Exception($error , 101);

                
                }

                $provider_details = Provider::where('email', '=', $request->email)->first();

                $email_active = DEFAULT_TRUE;

                // Check the provider details 

                if(!$provider_details) {
         
                    throw new Exception(Helper::error_message(1006) , 1006);

                }

                // check the provider approved status

                if ($provider_details->status != PROVIDER_APPROVED) {

                    $error = Helper::error_message(1000);

                    throw new Exception($error , 1000);


                }

                if (Setting::get('is_account_email_verification')) {

                    if (!$provider_details->is_verified) {

                        Helper::check_email_verification("" , $provider_details->id, $error);

                        $email_active = DEFAULT_FALSE;

                    }

                }

                if(!$email_active) {

                    $error = Helper::error_message(1001);

                    throw new Exception($error , 1001);

                }

                if(Hash::check($request->password, $provider_details->password)) {

                    // Generate new tokens

                    // @todo remove after testing phase
                    
                    // $provider_details->token = Helper::generate_token();

                    $provider_details->token_expiry = Helper::generate_token_expiry();
                    
                    // Save device details

                    $check_device_exist = Provider::where('device_token', $request->device_token)->first();

                    if($check_device_exist) {

                        $check_device_exist->device_token = "";
                        
                        $check_device_exist->save();
                    }

                    $provider_details->device_token = $request->device_token ? $request->device_token : $provider_details->device_token;

                    $provider_details->device_type = $request->device_type ? $request->device_type : $provider_details->device_type;

                    $provider_details->login_by = $request->login_by ? $request->login_by : $provider_details->login_by;

                    $provider_details->save();

                    $data = Provider::CommonResponse()->find($provider_details->id);

                    $response_array = ['success' => true, 'message' => Helper::success_message(101) , 'data' => $data];

                } else {

                    $error = Helper::error_message(102);

                    throw new Exception($error , 102);
                    
                }


            }

            DB::commit();

            return response()->json($response_array, 200);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }
    
    }

 
    /**
     * @method forgot_password()
     *
     * @uses If the provider forgot his/her password he can hange it over here
     *
     * @created Vidhya R 
     *
     * @updated Vidhya R
     *
     * @param object $request - Email id
     *
     * @return send mail to the valid provider
     */
    
    public function forgot_password(Request $request) {

        try {

            DB::beginTransaction();

            // Check email configuration and email notification enabled by admin

            if(Setting::get('is_email_notification') != 1 || envfile('MAIL_USERNAME') == "" || envfile('MAIL_PASSWORD') == "" ) {

                throw new Exception(Helper::error_message(106), 106);
                
            }
            
            $validator = Validator::make($request->all(),
                [
                    'email' => 'required|email|exists:providers,email',
                ],
                [
                    'exists' => 'The :attribute doesn\'t exists',
                ]
            );
            
            if ($validator->fails()) {
                
                $error = implode(',',$validator->messages()->all());
                
                throw new Exception($error , 101);
            
            } else {

                $provider_details = Provider::where('email' , $request->email)->first();

                if(!$provider_details) {
         
                    throw new Exception(Helper::error_message(1006) , 1006);

                }

                if($provider_details) {

                    if($provider_details->login_by != "manual") {

                        throw new Exception(Helper::error_message(119), 119);
                        
                    }

                    // check email verification

                    if($provider_details->is_verified == PROVIDER_EMAIL_NOT_VERIFIED) {
                        throw new Exception(Helper::error_message(120), 120);
                    }

                    // Check the provider approve status

                    if(in_array($provider_details->status , [PROVIDER_DECLINED , PROVIDER_PENDING])) {
                        throw new Exception(Helper::error_message(121), 121);
                    }

                    $new_password = Helper::generate_password();

                    $provider_details->password = Hash::make($new_password);

                    $email_data = [];

                    $subject = tr('provider_forgot_email_title' , Setting::get('site_name'));

                    $email_data['email']  = $provider_details->email;

                    $email_data['password'] = $new_password;

                    $page = "emails.providers.forgot-password";

                    $email_send_response = Helper::send_email($page,$subject,$provider_details->email,$email_data);

                    if($email_send_response->success) {

                        if(!$provider_details->save()) {

                            throw new Exception(Helper::error_message(103), 103);

                        }

                        $response_array = ['success' => true , 'message' => Helper::success_message(102)];

                    } else {

                        $error = $email_send_response->error;

                        throw new Exception($error, $email_send_response->error_code);
                        
                    }
                    
                } else {

                    $error = Helper::error_message(1006);

                    throw new Exception($error , 1006);
                    
                }

            }

            DB::commit();

            return response()->json($response_array, 200);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }
    
    }

    /**
     * @method change_password()
     *
     * @uses To change the password of the provider
     *
     * @created Vidhya R 
     *
     * @updated Vidhya R
     *
     * @param object $request - Password & confirm Password
     *
     * @return json response of the provider
     */
    public function change_password(Request $request) {

        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                    'password' => 'required|confirmed|min:6',
                    'old_password' => 'required|min:6',
                ]);

            if($validator->fails()) {
                
                $error = implode(',',$validator->messages()->all());
               
                throw new Exception($error , 101);
           
            }

            $provider_details = Provider::find($request->id);

            if(!$provider_details) {
         
                throw new Exception(Helper::error_message(1006) , 1006);

            }

            if($provider_details->login_by != "manual") {

                throw new Exception(Helper::error_message(121), 121);
                
            }

            if(Hash::check($request->old_password,$provider_details->password)) {

                $provider_details->password = Hash::make($request->password);
                
                if($provider_details->save()) {

                    $response_array = ['success' => true , 'message' => Helper::success_message(104)];
                
                } else {

                    throw new Exception(Helper::error_message(103), 103);
                    
                }

            } else {

                throw new Exception(Helper::error_message(108) , 108);
                
            }

            DB::commit();

            return response()->json($response_array,200);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /** 
     * @method profile()
     *
     * @uses To display the provider details based on provider  id
     *
     * @created Vidhya R 
     *
     * @updated Vidhya R
     *
     * @param object $request - provider Id
     *
     * @return json response with provider details
     */

    public function profile(Request $request) {

        try {

            $provider_details = Provider::where('id' , $request->id)->FullResponse()->first();

            if (!$provider_details) { 

                $error = Helper::error_message(1006);

                throw new Exception($error , 1006);
                
            }

            $data = $provider_details->toArray();

            return $this->sendResponse("", "", $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());

        }
    
    }
 
    /**
     * @method update_profile()
     *
     * @uses To update the provider details
     *
     * @created Vidhya R 
     *
     * @updated Vidhya R
     *
     * @param objecct $request provider details
     *
     * @return json response with provider details
     */
    public function update_profile(Request $request) {

        try {

            DB::beginTransaction();
            
            $validator = Validator::make($request->all(),
                [
                    'name' => 'required|max:255',
                    'email' => 'email|unique:providers,email,'.$request->id.'|max:255',
                    'mobile' => 'digits_between:6,13',
                    'picture' => 'mimes:jpeg,bmp,png',
                    'gender' => 'in:male,female,others',
                    'device_token' => '',
                    'school' => '',
                    'work' => '',
                    'languages' => '',
                    'full_address' => '',
                ]);

            if ($validator->fails()) {

                // Error messages added in response for debugging

                $error = implode(',',$validator->messages()->all());
             
                throw new Exception($error , 101);
                
            }

            $provider_details = Provider::find($request->id);

            if(!$provider_details) {
         
                throw new Exception(Helper::error_message(1006) , 1006);

            }
                
            $provider_details->name = $request->name ? $request->name : $provider_details->name;

            
            if($request->has('email')) {

                $provider_details->email = $request->email;
            }

            $provider_details->mobile = $request->mobile ?: $provider_details->mobile;

            $provider_details->gender = $request->gender ?: $provider_details->gender;

            $provider_details->description = $request->description ?: '';

            $provider_details->school = $request->school ?: '';

            $provider_details->work = $request->work ?: '';

            $provider_details->languages = $request->languages ?: '';

            $provider_details->full_address = $request->full_address ?: '';

            // Upload picture

            if ($request->hasFile('picture') != "") {

                // Delete the old pic

                Helper::delete_file($provider_details->picture, PROFILE_PATH_PROVIDER); 

                $provider_details->picture = Helper::upload_file($request->file('picture') , PROFILE_PATH_PROVIDER);

            }

            if ($provider_details->save()) {

                $data = Provider::CommonResponse()->find($provider_details->id);

                DB::commit();

                return $this->sendResponse(Helper::success_message(215), $code = 215, $data );

            } else {

                throw new Exception(Helper::error_message(103), 103);                    
            }

        } catch (Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }
   
    }

    /**
     * @method delete_account()
     * 
     * @uses Delete provider account based on provider id
     *
     * @created Vidhya R 
     *
     * @updated Vidhya R
     *
     * @param object $request - Password and provider id
     *
     * @return json with boolean output
     */

    public function delete_account(Request $request) {

        DB::beginTransaction();

        try {

            $request->request->add([ 
                'login_by' => $this->loginProvider ? $this->loginProvider->login_by : "manual",
            ]);

            $validator = Validator::make($request->all(),
                [
                    'password' => 'required_if:login_by,manual',
                ], 
                [
                    'password.required_if' => 'The :attribute field is required.',
                ]);

            if ($validator->fails()) {

                $error = implode(',',$validator->messages()->all());
             
                throw new Exception($error , 101);
                
            }

            $provider_details = Provider::find($request->id);

            if(!$provider_details) {
     
                throw new Exception(Helper::error_message(1006) , 1006);

            }

            // The password is not required when the provider is login from social. If manual means the password is required

            if($provider_details->login_by == 'manual') {

                if(!Hash::check($request->password, $provider_details->password)) {

                    $is_delete_allow = NO ;

                    $error = Helper::error_message(108);
         
                    throw new Exception($error , 108);
                    
                }
            
            }

            if($provider_details->delete()) {

                $response_array = ['success'=>true, 'message'=>tr('account_delete_success')];

            } else {
                throw new Exception("Error Processing Request", 1);
                
            }
            
            DB::commit();

            return response()->json($response_array,200);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    public function logout(Request $request) {

        // @later no logic for logout

        return $this->sendResponse(Helper::success_message(106), 106);

    }

    /**
     * @method configurations()
     *
     * @uses used to get the configurations for base products
     *
     * @created vithya R
     *
     * @updated - 
     *
     * @param - 
     *
     * @return JSON Response
     */
    public function configurations(Request $request) {

        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:providers,id',
                'token' => 'required',

            ]);

            if($validator->fails()) {

                $error = implode(',',$validator->messages()->all());

                throw new Exception($error, 101);

            }

            // Update timezone details

            $provider_details = Provider::find($request->id);

            $message = "";

            if($provider_details && $request->timezone) {

                $provider_details->timezone = $request->timezone ?: $provider_details->timezone;

                $provider_details->save();

                $message = tr('timezone_updated');

            }

            $config_data = $data = [];

            $payment_data['is_stripe'] = 1;

            $payment_data['stripe_publishable_key'] = Setting::get('stripe_publishable_key') ?: "";

            $payment_data['stripe_secret_key'] = Setting::get('stripe_secret_key') ?: "";

            $payment_data['stripe_secret_key'] = Setting::get('stripe_secret_key') ?: "";

            $data['payments'] = $payment_data;

            $data['urls']  = [];

            $url_data['base_url'] = envfile("APP_URL") ?: "";

            $url_data['socket_url'] = Setting::get("SOCKET_URL") ?: "";

            $data['urls'] = $url_data;

            $notification_data['FCM_SENDER_ID'] = "";

            $notification_data['FCM_SERVER_KEY'] = $notification_data['FCM_API_KEY'] = "";

            $notification_data['FCM_PROTOCOL'] = "";

            $data['notification'] = $notification_data;

            $data['site_name'] = Setting::get('site_name');

            $data['site_logo'] = Setting::get('site_logo');

            $data['currency'] = Setting::get('currency');

            return $this->sendResponse($message, $success_code = "", $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());

        }
   
    }

    /**
     * @method hosts_index()
     *
     * @uses used to get the hosts
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */
    public function hosts_index(Request $request) {

        try {

            $hosts = Host::where('hosts.provider_id' , $request->id)
                        ->select('hosts.id as host_id', 'hosts.host_name', 'hosts.picture as host_picture', 'hosts.host_type', 'hosts.city as host_location', 'hosts.created_at', 'hosts.updated_at', 'hosts.is_admin_verified', 'hosts.status as provider_host_status', 'admin_status as admin_host_status')
                        ->orderBy('hosts.updated_at' , 'desc')
                        ->skip($this->skip)
                        ->take($this->take)
                        ->get();

            foreach ($hosts as $key => $host_details) {

                $host_additional_details_steps = 0;

                $host_details->is_completed = $host_additional_details_steps == 8 ? YES: NO;

                $host_details->complete_percentage = ($host_additional_details_steps/8) * 100;

                $host_details->host_picture = $host_details->host_picture ?: asset('host-placeholder.png');

            }

            $response_array = ['success' => true , 'data' => $hosts];

            return response()->json($response_array , 200);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method hosts_view()
     *
     * @uses used to get the host details
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */
    public function hosts_view(Request $request) {

        try {

            $host = $host_details = Host::where('hosts.id', $request->host_id)
                                ->where('hosts.provider_id', $request->id)
                                ->first();

            if(!$host) {

                throw new Exception(Helper::error_message(200), 200);
                
            }

            // Create empty object

            $data = new \stdClass();

            /* # # # # # # # # # # BASIC DETAILS SECTION START # # # # # # # # # # */

            $basic_host_details = Host::where('hosts.id', $request->host_id)->SingleBaseResponse()->first();

            $basic_details = new \stdClass();

            $basic_details = $basic_host_details;

            $basic_details->share_link = url('/');
          
            // Actions
        
            $basic_details->per_day_formatted = formatted_amount($basic_details->per_day);

            $basic_details->per_day_symbol = tr('per_day_symbol');

            // Gallery details 

            $host_galleries = HostGallery::where('host_id', $host->id)->select('picture', 'caption')->get();

            $basic_details->gallery = $host_galleries;

            // Section 4

            $section_4_data = $section_4 = [];

            $section_4_data['title'] = $host->max_guests." ".tr('guests');

            $section_4_data['picture'] = asset('sample/users.png');

            array_push($section_4, $section_4_data);

            $section_4_data = [];

            $section_4_data['title'] = $host_details->total_bedrooms." ".tr('bedrooms');

            $section_4_data['picture'] = asset('sample/bedroom.png');

            array_push($section_4, $section_4_data);

            $section_4_data = [];

            $section_4_data['title'] = $host_details->total_beds." ".tr('beds');

            $section_4_data['picture'] = asset('sample/bed.png');

            array_push($section_4, $section_4_data);

            $section_4_data = [];

            $section_4_data['title'] = $host_details->total_bathrooms." ".tr('bath');

            $section_4_data['picture'] = asset('sample/bath.png');

            array_push($section_4, $section_4_data);

            $basic_details->section_4 = $section_4;

            // Assign basic details to main data

            $data->basic_details = $basic_details;

            /* # # # # # # # # # # BASIC DETAILS SECTION END # # # # # # # # # # */

            

            /* @ @ @ @ @ @ @ @ @ @ PRICING SECTION START @ @ @ @ @ @ @ @ @ @ */

            $pricing_details = new \stdClass();

            $pricing_details->currency = Setting::get('currency');

            $pricing_details->per_day_symbol = tr('list_per_day_symbol');

            $pricing_details->per_day = $host->per_day ?: 0.00;

            $pricing_details->per_day_formatted = formatted_amount($host->per_day);

            // $pricing_details->per_week = $host->per_week ?: 0.00;

            // $pricing_details->per_month = $host->per_month ?: 0.00;

            $pricing_details->service_fee = $host->service_fee ?: 0.00;

            $pricing_details->service_fee_formatted = formatted_amount($host->service_fee);

            $pricing_details->cleaning_fee = $host->cleaning_fee ?: 0.00;

            $pricing_details->cleaning_fee_formatted = formatted_amount($host->cleaning_fee);

            $pricing_details->tax_fee = $host->tax_fee ?: 0.00;

            $pricing_details->tax_fee_formatted = formatted_amount($host->tax_fee);

            $pricing_details->other_fee = $host->other_fee ?: 0.00;

            $pricing_details->other_fee_formatted = formatted_amount($host->other_fee);

            // Assign amenties to main data

            $data->pricing_details = $pricing_details;

            /* @ @ @ @ @ @ @ @ @ @ PRICING SECTION END @ @ @ @ @ @ @ @ @ @ */

            /* @ @ @ @ @ @ @ @ @ @ SLEEPTING ARRANGEMENTS SECTION START @ @ @ @ @ @ @ @ @ @ */


            $sleeping_data = [];

            $sleeping1['title'] = tr('bedrooms');

            $sleeping1['note'] = $host_details->total_bedrooms." ".tr('bedrooms');;

            $sleeping1['picture'] = asset('sample/bedroom.png');

            array_push($sleeping_data, $sleeping1);


            $sleeping2['title'] = tr('beds'); 

            $sleeping2['note'] = $host_details->total_beds." ".tr('beds');

            $sleeping2['picture'] = asset('sample/bed.png');

            array_push($sleeping_data, $sleeping2);


            $sleeping3['title'] = tr('bathrooms');

            $sleeping3['note'] = $host_details->total_bathrooms." ".tr('bathrooms');

            $sleeping3['picture'] = asset('sample/bath.png');

            array_push($sleeping_data, $sleeping3);
            

            $sleeping_arrangement_data = new \stdClass();

            $sleeping_arrangement_data->title = tr('sleeping_arrangements');

            $sleeping_arrangement_data->data = $sleeping_data;

            // Assign amenties to main data

            $data->arrangements = $sleeping_arrangement_data;


            /* @ @ @ @ @ @ @ @ @ @ SLEEPTING ARRANGEMENTS SECTION END @ @ @ @ @ @ @ @ @ @ */

            // Host provider details

            $provider_details = Provider::where('id', $host->provider_id)->FullResponse()->first();

            $provider_details->total_reviews = BookingUserReview::where('provider_id', $host->provider_id)->count();

            $data->provider_details = $provider_details;

            $data->questions =[];

            // Rules 

            $policies_data = HostHelper::host_policies($request->host_id);

            $policies = new \stdClass();

            $policies->title = tr('policies_rules');

            $policies->data = $policies_data;


            // Assign amenties to main data

            $data->policies = $policies;


            // Other Questions

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method hosts_save() 
     *
     * @uses save or update the host details
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param
     *
     * @return json repsonse
     */  
    public function hosts_save(Request $request) {

        Log::info("Host save".print_r($request->all(), true));

        try {

            // Common validator for all steps

            $validator = Validator::make($request->all(), [
                            'host_id' => 'exists:hosts,id,provider_id,'.$request->id,
                            'total_guests' => 'min:0',
                            'min_guests' => 'min:0',
                            'max_guests' => 'min:0',
                            'total_bedrooms' => 'min:0',
                            'total_beds' => 'min:0',
                            'total_bathrooms' => 'min:0',
                            'bathroom_type' => 'min:0',

                        ],[
                            'host_id' => Helper::error_message(200)
                        ]);

            DB::beginTransaction();

            if(!$request->host_id) {

                // Check the provider type is subscribed

                $provider_type = Helper::check_provider_type($this->loginProvider);

                if($provider_type == PROVIDER_TYPE_NORMAL) {

                    throw new Exception(Helper::error_message(1009), 1009);
                    
                }

            }

            // start the process logics

            // Check the host exists

            $host_response = HostRepo::hosts_save($request);

            if($host_response['success'] == false) {

                throw new Exception($host_response['error'], $host_response['error_code']);
                
            }

            $host = $host_response['host'];

            $host_details = $host_response['host_details'];


            // Host details (Structure & rooms)

            if($request->step == HOST_STEP_1) {

                HostHelper::check_step1_status($host, $host_details);

            }

            // Location update

            if($request->step == HOST_STEP_2) {

                HostHelper::check_step2_status($host, $host_details);

            }

            // Amenties

            if($request->step == HOST_STEP_3) {

                HostHelper::check_step3_status($host, $host_details);

            }

            // Title, Description

            if($request->step == HOST_STEP_4) {

                HostHelper::check_step4_status($host, $host_details);

            }

            // Other Questions

            if($request->step == HOST_STEP_5) {

                HostHelper::check_step5_status($host, $host_details);

            }

            // Pricing
            
            if($request->step == HOST_STEP_6) {

                HostHelper::check_step6_status($host, $host_details);

            }

            // HOST_STEP_7 - Availability

            if($request->step == HOST_STEP_7) {

                HostHelper::check_step7_status($host, $host_details);

            }

            if($request->step == HOST_STEP_8) {

                HostHelper::check_step8_status($host, $host_details);

            }

            // check steps are completed with enough details

            // Update the step status

            // check the completion of the host

            // store the details

            DB::commit();

            // send response

            $message = "The host updated successfully";

            $success_code = 200;

            $data = Host::select('id as host_id', 'host_name')->where('hosts.id', $host->id)->first();

            // $data->step1 = $host_details->step1;

            return $this->sendResponse($message, $success_code, $data);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method hosts_upload_files() 
     *
     * @uses Draft the uploaded files
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param
     *
     * @return json repsonse
     */  
    
    public function hosts_upload_files(Request $request) {

        try {

            DB::beginTransaction();

            // Validate the common and basic fields

            $validator = Validator::make($request->all(),
                [
                    'host_id' => 'required|exists:hosts,id,provider_id,'.$request->id,
                    'file' => 'required'
                ], 
                [
                    'exists' => Helper::error_message(200)
                ]
            );

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error , 101);

            }

            if($request->hasfile('file')) {

                $data = HostRepo::host_gallery_upload($request->file('file'), $request->host_id, $status = NO);

                DB::commit();
            
            }

            $message = "Uploaded successfully";

            return $this->sendResponse($message, $code = "", $data);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method hosts_remove_files() 
     *
     * @uses Draft the uploaded files
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param
     *
     * @return json repsonse
     */  
    
    public function hosts_remove_files(Request $request) {

        try {

            DB::beginTransaction();

            // Validate the common and basic fields

            $validator = Validator::make($request->all(),
                [
                    'host_id' => 'required|exists:hosts,id,provider_id,'.$request->id,
                    'host_gallery_id' => 'required'
                ], 
                [
                    'exists' => Helper::error_message(200)
                ]
            );

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error , 101);

            }

            if(HostGallery::where('id', $request->host_gallery_id)->delete()) {

                DB::commit();

                $message = "The file removed";

                return $this->sendResponse($message, $code = "", $data = []);

            } else {

                throw new Exception("The action failed", 101);
            
            }

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method hosts_status()
     *
     * @uses used to update the status of the selected host
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */
    public function hosts_status(Request $request) {

        try {

            DB::beginTransaction();

            $host_details = Host::where('id', $request->host_id)->where('provider_id', $request->id)->first();

            if(!$host_details) {

                throw new Exception(Helper::error_message(200), 200);                
            }

            $host_details->status = $host_details->status ? HOST_OWNER_UNPUBLISHED : HOST_OWNER_PUBLISHED;

            $host_details->save();

            DB::commit();

            $message = $host_details->status ? Helper::success_message(208) : Helper::success_message(209);

            $response_array = ['success' => true , 'message' => $message];

            return response()->json($response_array , 200);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method hosts_delete()
     *
     * @uses used to update the status of the selected host
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */
    public function hosts_delete(Request $request) {

        try {

            DB::beginTransaction();

            $host_details = Host::where('id', $request->host_id)->where('provider_id', $request->id)->first();

            if(!$host_details) {

                throw new Exception(Helper::error_message(200), 200);                
            }

            if($host_details->delete()) {

                DB::commit();

                $response_array = ['success' => true , 'message' => Helper::success_message(210)];

                return response()->json($response_array , 200);

            } else {

                throw new Exception(Helper::error_message(Helper::error_message(204)), 204);

            }

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method bookings_view()
     *
     * @uses used to get the list of bookings
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */

    public function bookings_view(Request $request) {

        Log::info("bookings_view".print_r($request->all(), true));

        try {

            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|exists:bookings,id,provider_id,'.$request->id,

            ]);

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);
            }

            $booking_details = Booking::where('provider_id', $request->id)->where('id', $request->booking_id)->first();

            if(!$booking_details) {

                throw new Exception(Helper::error_message(206), 206); 
            }

            $host_details = Host::where('hosts.id', $booking_details->host_id)->VerifedHostQuery()->first();

            if(!$host_details) {

                throw new Exception(Helper::error_message(200), 200);
                
            }

            $user_details = $this->loginProvider;

            $data = new \stdClass;

            $data->booking_id = $booking_details->id;

            $data->booking_unique_id = $booking_details->unique_id;

            $data->host_id = $booking_details->host_id;

            $data->host_unique_id = $host_details->unique_id;

            $data->host_name = $host_details->host_name;

            $data->host_type = $host_details->host_type;

            $data->picture = $host_details->picture;

            $data->full_address = $host_details->full_address;

            $data->share_link = url('/');

            $sub_category_name = $host_details->subCategoryDetails->name ?? "";

            $data->sub_category_name = $sub_category_name." - $host_details->host_type";

            $data->location = "";

            $data->host_description = $host_details->description;

            $data->host_picture = $host_details->picture;

            $data->total_guests = $booking_details->total_guests;

            $data->adults = $booking_details->adults ?: 0;

            $data->children = $booking_details->children ?: 0;

            $data->infants = $booking_details->infants ?: 0;

            $data->checkin = common_date($booking_details->checkin, $user_details->timezone, "d, M Y"); 

            $data->checkout = common_date($booking_details->checkout, $user_details->timezone, "d, M Y"); 

            $data->total_days = $booking_details->total_days ?: 1;

            $data->total_days_text = $data->total_days." nights";

            $data->currency = $booking_details->currency;

            $data->total = $booking_details->total;

            $data->total_formatted = formatted_amount($booking_details->total);

            $host_galleries = HostGallery::where('host_id', $host_details->id)->select('picture', 'caption')->get();

            $data->gallery = $host_galleries;

            $data->user_details = User::where('id', $booking_details->user_id)->select('id as user_id', 'username as user_name', 'email', 'picture', 'mobile', 'description','created_at')->first();

            $booking_payment_details = $booking_details->bookingPayments;

            $pricing_details = new \stdClass();

            $pricing_details->currency = $this->currency;

            $pricing_details->per_day = $booking_details->per_day ?: 0.00;

            $pricing_details->per_day_formatted = formatted_amount($booking_details->per_day);

            // $pricing_details->per_week = $host_details->per_week ?: 0.00;

            // $pricing_details->per_month = $host_details->per_month ?: 0.00;

            $pricing_details->service_fee = $host_details->service_fee ?: 0.00;

            $pricing_details->service_fee_formatted = formatted_amount($host_details->service_fee);


            $pricing_details->cleaning_fee = $host_details->cleaning_fee ?: 0.00;

            $pricing_details->cleaning_fee_formatted = formatted_amount($host_details->cleaning_fee);

            $pricing_details->tax_fee = $host_details->tax_fee ?: 0.00;

            $pricing_details->tax_fee_formatted = formatted_amount($host_details->tax_fee);


            $pricing_details->other_fee = $host_details->other_fee ?: 0.00;

            $pricing_details->tother_fee_formatted = formatted_amount($host_details->tother_fee);


            $pricing_details->payment_id = $booking_payment_details->payment_id ?: "";

            $pricing_details->payment_mode = $booking_payment_details->payment_mode ?: "CARD";


            $pricing_details->paid_amount = $booking_payment_details->paid_amount ?: 0.00;

            $pricing_details->paid_amount_formatted = formatted_amount($booking_payment_details->paid_amount ?: 0.00);

            $pricing_details->paid_date = common_date($booking_payment_details->paid_date ?: date('Y-m-d')); // @todo

            // Assign amenties to main data

            $data->pricing_details = $pricing_details;

            $data->status_text = booking_status($booking_details->status);

            $data->buttons = booking_btn_status($booking_details->status, $booking_details->id);

            $data->user_name = $booking_details->userDetails->name ?? '' ;

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method bookings_cancel()
     *
     * @uses used to get the list of bookings
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */
    public function bookings_cancel(Request $request) {

        try {

            $validator = Validator::make($request->all(), [

                'booking_id' => 'required|exists:bookings,id,provider_id,'.$request->id
            ]);

            if($validator->fails()) {

                $error = implode(',',$validator->messages()->all());

                throw new Exception($error, 101);
            }

            $booking_details = Booking::where('bookings.id', $request->booking_id)->where('provider_id', $request->id)->first();

            if(!$booking_details) {

                throw new Exception(Helper::error_message(206), 206);
            }

            // check the required status to cancel the booking

            $cancelled_status = [BOOKING_CANCELLED_BY_USER, BOOKING_CANCELLED_BY_PROVIDER];

            if(in_array($booking_details->status, $cancelled_status)) {

                throw new Exception(Helper::error_message(209), 209);
                
            }

            // After checkin the user can't cancel the booking 

            if($booking_details->status == BOOKING_CHECKIN) {
                
                throw new Exception(Helper::error_message(217), 217);

            }

            DB::beginTransaction();

            // check the required status to cancel the booking

            $booking_details->status = BOOKING_CANCELLED_BY_PROVIDER;

            $booking_details->cancelled_reason = $request->cancelled_reason ?: "";

            $booking_details->cancelled_date = date('Y-m-d H:i:s');

            if($booking_details->save()) {

                DB::commit();

                $message = Helper::success_message(212); $code = 212;

                $data['booking_id'] = $request->booking_id;

                return $this->sendResponse($message, $code, $data);

            } else {
                
                throw new Exception(Helper::error_message(207), 207);
                
            }

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }
    /**
     * @method bookings_rating_report()
     *
     * @uses used to get the list of bookings
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */
    public function bookings_rating_report(Request $request) {

        try {

            $booking_chats = BookingChat::where('user_id' , $request->user_id)
                        ->skip($this->skip)
                        ->take($this->take)
                        ->orderBy('updated_at' , 'desc')
                        ->get();

            $response_array = ['success' => true , 'data' => $booking_chats];

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method sub_categories()
     *
     * @uses used get the sub_categories lists
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param 
     *
     * @return response of details
     */

    public function sub_categories(Request $request) {

        try {

            $sub_categories = SubCategory::where('category_id', $request->category_id)->CommonResponse()->where('sub_categories.status' , APPROVED)->orderBy('sub_categories.name' , 'asc')->get();

            $host_details = Host::find($request->host_id);

            foreach ($sub_categories as $key => $sub_category_details) {

                $sub_category_details->is_checked  = NO;

                if($host_details) {
                    $sub_category_details->is_checked = $host_details->sub_category_id == $sub_category_details->sub_category_id ? YES : NO;
                }
            }

            return $this->sendResponse("", "", $sub_categories);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method reviews_for_you()
     *
     * @uses used to get the reviews based review_type = provider | Host @todo
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */
    public function reviews_for_you(Request $request) {

        try {

            $base_query = BookingUserReview::where('booking_user_reviews.provider_id', $request->id)->CommonResponse();

            $reviews = $base_query->skip($this->skip)->take($this->take)->get();

            return $this->sendResponse($message = "", $success_code = "", $reviews);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method reviews_for_users()
     *
     * @uses used to get the reviews based review_type = provider | Host @todo 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */
    public function reviews_for_users(Request $request) {

        try {

            $base_query = BookingProviderReview::where('booking_provider_reviews.provider_id', $request->id)->CommonResponse();

            $reviews = $base_query->skip($this->skip)->take($this->take)->get();

            return $this->sendResponse($message = "", $success_code = "", $reviews);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }
}
