<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\HostRepository as HostRepo;

use App\Helpers\Helper, App\Helpers\HostHelper;

use App\Http\Resources\HostCollection as HostCollection;

use App\Repositories\BookingRepository as BookingRepo;

use Carbon\Carbon;

use Carbon\CarbonPeriod;

use DB, Log, Hash, Validator, Exception, Setting;

use App\Booking, App\BookingPayment;

use App\BookingProviderReview, App\BookingUserReview;

use App\Category, App\SubCategory;

use App\Host, App\HostGallery;

use App\Lookups, App\StaticPage;

use App\Provider;

use App\User;

class UserApiController extends Controller {

    protected $loginUser;

    protected $skip, $take, $timezone, $currency;

	public function __construct(Request $request) {

        Log::info(url()->current());
        
        $this->loginUser = User::CommonResponse()->find($request->id);

        $this->skip = $request->skip ?: 0;

        $this->take = $request->take ?: (Setting::get('admin_take_count') ?: TAKE_COUNT);

        $this->currency = Setting::get('currency', '$');

        $this->timezone = $this->loginUser ? $this->loginUser->timezone : "";

    }

    /**
     * @method register()
     *
     * @uses Registered user can register through manual or social login
     * 
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param Form data
     *
     * @return Json response with user details
     */
    public function register(Request $request) {

        try {
            
            DB::beginTransaction();

            // Validate the common and basic fields

            $basic_validator = Validator::make($request->all(),
                [
                    'device_type' => 'required|in:'.DEVICE_ANDROID.','.DEVICE_IOS.','.DEVICE_WEB,
                    'device_token' => 'required',
                    'login_by' => 'required|in:manual,facebook,google',
                ]
            );

            if($basic_validator->fails()) {

                $error = implode(',', $basic_validator->messages()->all());

                throw new Exception($error , 101);

            }

            $allowed_social_logins = ['facebook','google'];

            if(in_array($request->login_by,$allowed_social_logins)) {

                // validate social registration fields

                $social_validator = Validator::make($request->all(),
                            [
                                'social_unique_id' => 'required',
                                'name' => 'required|max:255|min:2',
                                'email' => 'required|email|max:255',
                                'mobile' => 'digits_between:6,13',
                                'picture' => '',
                                'gender' => 'in:male,female,others',
                            ]
                        );

                if($social_validator->fails()) {

                    $error = implode(',', $social_validator->messages()->all());

                    throw new Exception($error , 101);

                }

            } else {

                // Validate manual registration fields

                $manual_validator = Validator::make($request->all(),
                    [
                        'name' => 'required|max:255',
                        'email' => 'required|email|max:255|min:2',
                        'password' => 'required|min:6',
                        'picture' => 'mimes:jpeg,jpg,bmp,png',
                    ]
                );

                // validate email existence

                $email_validator = Validator::make($request->all(),
                    [
                        'email' => 'unique:users,email',
                    ]
                );

                if($manual_validator->fails()) {

                    $error = implode(',', $manual_validator->messages()->all());

                    throw new Exception($error , 101);
                    
                } else if($email_validator->fails()) {

                	$error = implode(',', $email_validator->messages()->all());

                    throw new Exception($error , 101);

                } 

            }

            $user_details = User::where('email' , $request->email)->first();

            $send_email = DEFAULT_FALSE;

            // Creating the user

            if(!$user_details) {

                $user_details = new User;

                register_mobile($request->device_type);

                $send_email = DEFAULT_TRUE;

                $user_details->picture = asset('placeholder.jpg');

                $user_details->registration_steps = 1;

            } else {

                if(in_array($user_details->status , [USER_PENDING , USER_DECLINED])) {

                    throw new Exception(Helper::error_message(1000) , 1000);
                
                }

            }

            if($request->has('name')) {

                $user_details->name = $request->name;

            }

            if($request->has('email')) {

                $user_details->email = $request->email;

            }

            if($request->has('mobile')) {

                $user_details->mobile = $request->mobile;

            }

            if($request->has('password')) {

                $user_details->password = Hash::make($request->password ?: "123456");

            }

            $user_details->gender = $request->has('gender') ? $request->gender : "male";

            $user_details->payment_mode = COD;

            $user_details->token = Helper::generate_token();

            $user_details->token_expiry = Helper::generate_token_expiry();

            $check_device_exist = User::where('device_token', $request->device_token)->first();

            if($check_device_exist) {

                $check_device_exist->device_token = "";

                $check_device_exist->save();
            }

            $user_details->device_token = $request->device_token ?: "";

            $user_details->device_type = $request->device_type ?: DEVICE_WEB;

            $user_details->login_by = $request->login_by ?: 'manual';

            $user_details->social_unique_id = $request->social_unique_id ?: '';

            // Upload picture

            if($request->login_by == "manual") {

                if($request->hasFile('picture')) {

                    $user_details->picture = Helper::upload_file($request->file('picture') , PROFILE_PATH_USER);

                }

            } else {

                $user_details->is_verified = USER_EMAIL_VERIFIED; // Social login

                $user_details->picture = $request->picture ?: $user_details->picture;

            }   
            
            if($user_details->save()) {

                // Send welcome email to the new user:

                if($send_email) {

                    if($user_details->login_by == 'manual') {

                        $user_details->password = $request->password;

                        $subject = tr('user_welcome_title').' '.Setting::get('site_name');

                        $email_data = $user_details;

                        $page = "emails.users.welcome";

                        $email = $user_details->email;

                        $email_send_response = Helper::send_email($page,$subject,$email,$email_data);

                        // No need to throw error. For forgot password we need handle the error response

                        if($email_send_response->success) {

                        } else {

                            $error = $email_send_response->error;

                            Log::info("Registered EMAIL Error".print_r($error , true));
                            
                        }

                    }

                }

                if(in_array($user_details->status , [USER_DECLINED , USER_PENDING])) {
                
                    $response = ['success' => false , 'error' => Helper::error_message(1000) , 'error_code' => 1000];

                    DB::commit();

                    return response()->json($response, 200);
               
                }

                if($user_details->is_verified == USER_EMAIL_VERIFIED) {

                	$data = User::CommonResponse()->find($user_details->id);

                    $response_array = ['success' => true, 'data' => $data];

                } else {

                    $response_array = ['success'=>false, 'error' => Helper::error_message(1001), 'error_code'=>1001];

                    DB::commit();

                    return response()->json($response_array, 200);

                }

            } else {

                throw new Exception(Helper::error_message(103), 103);

            }

            DB::commit();

            return response()->json($response_array, 200);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }
   
    }

    /**
     * @method login()
     *
     * @uses Registered user can login using their email & password
     * 
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param object $request - User Email & Password
     *
     * @return Json response with user details
     */
    public function login(Request $request) {

        try {

            DB::beginTransaction();

            $basic_validator = Validator::make($request->all(),
                [
                    'device_token' => 'required',
                    'device_type' => 'required|in:'.DEVICE_ANDROID.','.DEVICE_IOS.','.DEVICE_WEB,
                    'login_by' => 'required|in:manual,facebook,google',
                ]
            );

            if($basic_validator->fails()){

                $error = implode(',', $basic_validator->messages()->all());

                throw new Exception($error , 101);

            }

            /** Validate manual login fields */

            $manual_validator = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required',
                ]
            );

            if($manual_validator->fails()) {

                $error = implode(',', $manual_validator->messages()->all());

            	throw new Exception($error , 101);

            }

            $user_details = User::where('email', '=', $request->email)->first();

            $email_active = DEFAULT_TRUE;

            // Check the user details 

            if(!$user_details) {

            	throw new Exception(Helper::error_message(1002), 1002);

            }

            // check the user approved status

            if($user_details->status != USER_APPROVED) {

            	throw new Exception(Helper::error_message(1000), 1000);

            }

            if(Setting::get('is_account_email_verification') == YES) {

                if(!$user_details->is_verified) {

                    Helper::check_email_verification("" , $user_details->id, $error);

                    $email_active = DEFAULT_FALSE;

                }

            }

            if(!$email_active) {

    			throw new Exception(Helper::error_message(1001), 1001);
            }

            if(Hash::check($request->password, $user_details->password)) {

                // Generate new tokens

                // @todo remove after testing phase
                
                // $user_details->token = Helper::generate_token();

                $user_details->token_expiry = Helper::generate_token_expiry();
                
                // Save device details

                $check_device_exist = User::where('device_token', $request->device_token)->first();

                if($check_device_exist) {

                    $check_device_exist->device_token = "";
                    
                    $check_device_exist->save();
                }

                $user_details->device_token = $request->device_token ? $request->device_token : $user_details->device_token;

                $user_details->device_type = $request->device_type ? $request->device_type : $user_details->device_type;

                $user_details->login_by = $request->login_by ? $request->login_by : $user_details->login_by;

                $user_details->save();

                $data = User::CommonResponse()->find($user_details->id);

                $response_array = array('success' => true, 'message' => Helper::success_message(101) , 'data' => $data);

            } else {

				throw new Exception(Helper::error_message(102), 102);
                
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
     * @uses If the user forgot his/her password he can hange it over here
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param object $request - Email id
     *
     * @return send mail to the valid user
     */
    
    public function forgot_password(Request $request) {

        try {

            DB::beginTransaction();

            // Check email configuration and email notification enabled by admin

            if(Setting::get('is_email_notification') != YES || envfile('MAIL_USERNAME') == "" || envfile('MAIL_PASSWORD') == "" ) {

                throw new Exception(Helper::error_message(106), 106);
                
            }
            
            $validator = Validator::make($request->all(),
                [
                    'email' => 'required|email|exists:users,email',
                ],
                [
                    'exists' => 'The :attribute doesn\'t exists',
                ]
            );

            if($validator->fails()) {
                
                $error = implode(',',$validator->messages()->all());
                
                throw new Exception($error , 101);
            
            }

            $user_details = User::where('email' , $request->email)->first();

            if(!$user_details) {

                throw new Exception(Helper::error_message(1002), 1002);
            }

            if($user_details->login_by != "manual") {

                throw new Exception(Helper::error_message(119), 119);
                
            }

            // check email verification

            if($user_details->is_verified == USER_EMAIL_NOT_VERIFIED) {

                throw new Exception(Helper::error_message(120), 120);
            }

            // Check the user approve status

            if(in_array($user_details->status , [USER_DECLINED , USER_PENDING])) {
                throw new Exception(Helper::error_message(121), 121);
            }

            $new_password = Helper::generate_password();

            $user_details->password = Hash::make($new_password);

            $email_data = array();

            $subject = tr('user_forgot_email_title' , Setting::get('site_name'));

            $email_data['email']  = $user_details->email;

            $email_data['password'] = $new_password;

            $page = "emails.users.forgot-password";

            $email_send_response = Helper::send_email($page,$subject,$user_details->email,$email_data);

            if($email_send_response->success) {

                if(!$user_details->save()) {

                    throw new Exception(Helper::error_message(103), 103);

                }

                $response_array = ['success' => true , 'message' => Helper::success_message(102), 'code' => 102];

            } else {

                $error = $email_send_response->error;

                throw new Exception($error, $email_send_response->error_code);
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
     * @uses To change the password of the user
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param object $request - Password & confirm Password
     *
     * @return json response of the user
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

            $user_details = User::find($request->id);

            if(!$user_details) {

                throw new Exception(Helper::error_message(1002), 1002);
            }

            if($user_details->login_by != "manual") {

                throw new Exception(Helper::error_message(121), 121);
                
            }

            if(Hash::check($request->old_password,$user_details->password)) {

                $user_details->password = Hash::make($request->password);
                
                if($user_details->save()) {

                    DB::commit();

                    return $this->sendResponse(Helper::success_message(104), $success_code = 104, $data = []);
                
                } else {

                    throw new Exception(Helper::error_message(103), 103);   
                }

            } else {

                throw new Exception(Helper::error_message(108) , 108);
            }

            

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /** 
     * @method profile()
     *
     * @uses To display the user details based on user  id
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param object $request - User Id
     *
     * @return json response with user details
     */

    public function profile(Request $request) {

        try {

            $user_details = User::where('id' , $request->id)->CommonResponse()->first();

            if(!$user_details) { 

                throw new Exception(Helper::error_message(1002) , 1002);
            }

            $data = $user_details->toArray();

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());

        }
    
    }
 
    /**
     * @method update_profile()
     *
     * @uses To update the user details
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param objecct $request : User details
     *
     * @return json response with user details
     */
    public function update_profile(Request $request) {

        try {

            DB::beginTransaction();
            
            $validator = Validator::make($request->all(),
                [
                    'name' => 'required|max:255',
                    'email' => 'email|unique:users,email,'.$request->id.'|max:255',
                    'mobile' => 'digits_between:6,13',
                    'picture' => 'mimes:jpeg,bmp,png',
                    'gender' => 'in:male,female,others',
                    'device_token' => '',
                    'description' => ''
                ]);

            if($validator->fails()) {

                // Error messages added in response for debugging

                $error = implode(',',$validator->messages()->all());
             
                throw new Exception($error , 101);
                
            }

            $user_details = User::find($request->id);

            if(!$user_details) { 

                throw new Exception(Helper::error_message(1002) , 1002);
            }

            $user_details->name = $request->name ? $request->name : $user_details->name;
            
            if($request->has('email')) {

                $user_details->email = $request->email;
            }

            $user_details->mobile = $request->mobile ?: $user_details->mobile;

            $user_details->gender = $request->gender ?: $user_details->gender;

            $user_details->description = $request->description ?: '';

            // Upload picture
            if($request->hasFile('picture') != "") {

                Helper::delete_file($user_details->picture, COMMON_FILE_PATH); // Delete the old pic

                $user_details->picture = Helper::upload_file($request->file('picture') , COMMON_FILE_PATH);

            }

            if($user_details->save()) {

            	$data = User::CommonResponse()->find($user_details->id);

                DB::commit();

                return $this->sendResponse(Helper::success_message(214), $code = 214, $data );

            } else {    

        		throw new Exception(Helper::error_message(103) , 103);
            }

        } catch (Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }
   
    }

    /**
     * @method delete_account()
     * 
     * @uses Delete user account based on user id
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param object $request - Password and user id
     *
     * @return json with boolean output
     */

    public function delete_account(Request $request) {

        try {

            DB::beginTransaction();

            $request->request->add([ 
                'login_by' => $this->loginUser ? $this->loginUser->login_by : "manual",
            ]);
            
            $validator = Validator::make($request->all(),
                [
                    'password' => 'required_if:login_by,manual',
                ], 
                [
                    'password.required_if' => 'The :attribute field is required.',
                ]);

            if($validator->fails()) {

                $error = implode(',',$validator->messages()->all());
             
                throw new Exception($error , 101);
                
            }

            $user_details = User::find($request->id);

            if(!$user_details) {

            	throw new Exception(Helper::error_message(1002), 1002);
                
            }

            // The password is not required when the user is login from social. If manual means the password is required

            if($user_details->login_by == 'manual') {

                if(!Hash::check($request->password, $user_details->password)) {

                    $is_delete_allow = NO ;

                    $error = Helper::error_message(108);
         
                    throw new Exception($error , 108);
                    
                }
            
            }

            if($user_details->delete()) {

                DB::commit();

                $message = Helper::success_message(103);

                return $this->sendResponse($message, $code = 103, $data = []);

            } else {

            	throw new Exception(Helper::error_message(205), 205);
            }

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }

	}

    /**
     * @method logout()
     *
     * @uses Logout the user
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param 
     * 
     * @return
     */
    public function logout(Request $request) {

        // @later no logic for logout

        return $this->sendResponse(Helper::success_message(106), 106);

    }

    /**
     * @method configurations()
     *
     * @uses used to get the configurations for base products
     *
     * @created Vithya R Chandrasekar
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
                'id' => 'required|exists:users,id',
                'token' => 'required'
            ]);

            if($validator->fails()) {

                $error = implode(',',$validator->messages()->all());

                throw new Exception($error, 101);

            }

            // Update timezone details

            $user_details = User::find($request->id);

            $message = "";

            if($user_details && $request->timezone) {
                
                $user_details->timezone = $request->timezone ?: $user_details->timezone;

                $user_details->save();

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

            $url_data['chat_socket_url'] = Setting::get("chat_socket_url") ?: "";

            $url_data['refund_page_url'] = route('static_pages.view', ['type' => 'refund']);

            $url_data['cancellation_page_url'] = route('static_pages.view', ['type' => 'cancellation']);

            $data['urls'] = $url_data;

            $notification_data['FCM_SENDER_ID'] = "";

            $notification_data['FCM_SERVER_KEY'] = $notification_data['FCM_API_KEY'] = "";

            $notification_data['FCM_PROTOCOL'] = "";

            $data['notification'] = $notification_data;

            $data['site_name'] = Setting::get('site_name');

            $data['site_logo'] = Setting::get('site_logo');

            $data['currency'] = $this->currency;

            return $this->sendResponse($message, $success_code = "", $data);

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
                                ->where('hosts.is_admin_verified', ADMIN_HOST_VERIFIED)
                                ->where('hosts.admin_status', ADMIN_HOST_APPROVED)
                                ->where('hosts.status', HOST_OWNER_PUBLISHED)
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

            $basic_details->min_days_text = $basic_details->min_days." nights min";

            $basic_details->max_days_text = $basic_details->max_days." nights max";
          
            // Actions
        
            $basic_details->per_day_formatted = formatted_amount($basic_details->per_day);

            $basic_details->per_day_symbol = tr('list_per_day_symbol');

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

            $pricing_details->currency = $this->currency;

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
     * @method reviews_index()
     *
     * @uses used to get the reviews based review_type = provider | Host
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */
    public function reviews_index(Request $request) {

        try {

            $validator = Validator::make($request->all(), [
                            'host_id' => 'exists:hosts,id',
                            'provider_id' => 'exists:providers,id',
                            
                        ],
                        [
                            'required' => Helper::error_message(202),
                            'exists.host_id' => Helper::error_message(200),
                            'exists.provider_id' => Helper::error_message(201)
                        ]
                    );

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);
                
            }

            $base_query = BookingUserReview::leftJoin('users', 'users.id', '=', 'booking_user_reviews.user_id')
                                ->leftJoin('providers', 'providers.id', '=', 'booking_user_reviews.provider_id')
                                ->leftJoin('hosts', 'hosts.id', '=', 'booking_user_reviews.host_id')
                                ->select('booking_user_reviews.id as booking_user_review_id', 
                                        'hosts.host_name as host_name','user_id','users.name as user_name', 
                                        'users.picture as user_picture', 'providers.name as provider_name',
                                        'providers.id as provider_id', 'providers.picture as provider_picture',
                                        'ratings', 'review', 'booking_user_reviews.created_at');

            if($request->host_id) {

                $basic_query = $base_query->where('booking_user_reviews.host_id', $request->host_id);

            }

            if($request->provider_id) {

                $basic_query = $base_query->where('booking_user_reviews.provider_id', $request->provider_id);

            }

            $reviews = $base_query->skip($this->skip)->take($this->take)->get();

            return $this->sendResponse($message = "", $success_code = "", $reviews);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method bookings_create()
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

    public function bookings_create(Request $request) {


        try {
                  
            DB::beginTransaction();

            $today = date('Y-m-d');

            $validator = Validator::make($request->all(), [

                'host_id' => 'required|exists:hosts,id',
                'checkin' => 'after:'.$today.'|date',
                'checkout' => 'required_if:checkin,|date|after:checkin',
                'description' => '',
                'total_guests' => 'integer'
            ]);

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);
            }

            $user_details = $this->loginUser;

            $host = $host_details = Host::where('id', $request->host_id)->VerifedHostQuery()->first();
            
            if(!$host) {

                throw new Exception(Helper::error_message(200), 200);
                
            }

            $booking_details = new Booking;

            $booking_details->host_id = $request->host_id;

            $booking_details->user_id = $request->id;

            $booking_details->provider_id = $host->provider_id;

            $booking_details->per_day = $host->per_day;

            $booking_details->currency = $this->currency;

            $booking_details->payment_mode = $user_details->payment_mode;

            $booking_details->status = BOOKING_DONE_BY_USER;
            

            $booking_details->host_checkin = $host->checkin ?: "";

            $booking_details->host_checkout = $host->checkout ?: "";

            $booking_details->description = $request->description ?: "";

            // Validated and send response 

            // Check the host available on the selected dates

            if($request->checkin && $request->checkout) {

                $booking_details->checkin = $request->checkin ? date('Y-m-d', strtotime($request->checkin)) : $booking_details->checkin;

                $booking_details->checkout = $request->checkout ? date('Y-m-d', strtotime($request->checkout)) : $booking_details->checkout;

                $booking_details->total_days = total_days($request->checkin, $request->checkout);

            }

            $booking_details->total_guests = $request->total_guests ?: $host_details->total_guests;

            $booking_details->per_day = $host_details->base_price ?: Setting::get('base_price', "0");

            $booking_details->save();

            // Update the pricings

            $per_day_for_all_guests = $booking_details->per_day;

            $total = $per_day_for_all_guests * $booking_details->total_days;

            $booking_details->total = $total;

            if($booking_details->save()) {

                $booking_payment_details = new BookingPayment;

                $booking_payment_details->booking_id = $booking_details->id;

                $booking_payment_details->user_id = $request->id;

                $booking_payment_details->provider_id = $host_details->provider_id;

                $booking_payment_details->host_id = $request->host_id;

                $booking_payment_details->payment_id = "COD-".rand();

                $booking_payment_details->currency = $this->currency;

                $booking_payment_details->total_time = $booking_details->total_days;

                $booking_payment_details->sub_total = $booking_payment_details->actual_total = $booking_payment_details->total = $booking_payment_details->paid_amount = $booking_details->total;

                $booking_payment_details->status = PAID;

                $booking_payment_details->save();

                DB::commit();

                $booking_details = Booking::where('bookings.id', $booking_details->id)->CommonResponse()->first();

                $booking_details->total_formatted = formatted_amount($booking_details->per_day);

                $booking_details->payment_id = $booking_payment_details->payment_id; // @todo send proper details

                return $this->sendResponse($message = Helper::success_message(2013), $success_code = 2013, $booking_details);

            }

            throw new Exception(tr('booking_save_failed'), 101);
            

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
                'booking_id' => 'required|exists:bookings,id,user_id,'.$request->id,

            ]);

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);
            }

            $booking_details = Booking::where('user_id', $request->id)->where('id', $request->booking_id)->first();

            if(!$booking_details) {

                throw new Exception(Helper::error_message(206), 206); 
            }

            $host_details = Host::where('hosts.id', $booking_details->host_id)->VerifedHostQuery()->first();

            if(!$host_details) {

                throw new Exception(Helper::error_message(200), 200);
                
            }

            $user_details = $this->loginUser;

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

            $data->total_guests = $booking_details->total_guests;

            $data->checkin = common_date($booking_details->checkin, $user_details->timezone, "d, M Y"); 

            $data->checkout = common_date($booking_details->checkout, $user_details->timezone, "d, M Y"); 

            $data->total_days = $booking_details->total_days ?: 1;

            $data->total_days_text = $data->total_days." nights";

            $data->currency = $booking_details->currency;

            $data->total = $booking_details->total;

            $data->total_formatted = formatted_amount($booking_details->total);

            $host_galleries = HostGallery::where('host_id', $host_details->id)->select('picture', 'caption')->get();

            $data->gallery = $host_galleries;

            $data->provider_details = Provider::where('id', $host_details->provider_id)->select('id as provider_id', 'username as provider_name', 'email', 'picture', 'mobile', 'description','created_at')->first();

            $booking_payment_details = $booking_details->bookingPayments;

            $pricing_details = new \stdClass();

            $pricing_details->currency = $this->currency;

            $pricing_details->per_day = $booking_details->per_day ?: 0.00;

            $pricing_details->per_day_formatted = formatted_amount($booking_details->per_day);

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


            $data->pricing_details = $pricing_details;

            $data->status_text = booking_status($booking_details->status);

            $data->buttons = booking_btn_status($booking_details->status, $booking_details->booking_id);

            $data->provider_name = $booking_details->providerDetails->name ?? '' ;

            // Assign amenties to main data


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

                'booking_id' => 'required|exists:bookings,id,user_id,'.$request->id
            ]);

            if($validator->fails()){

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error , 101);

            }
            
            $booking_details = Booking::where('bookings.id', $request->booking_id)->where('user_id', $request->id)->first();

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

            $booking_details->status = BOOKING_CANCELLED_BY_USER;

            $booking_details->cancelled_reason = $request->cancelled_reason ?: "";

            $booking_details->cancelled_date = date('Y-m-d H:i:s');

            if($booking_details->save()) {

                DB::commit();

                $data = ['booking_id' => $booking_details->id];

                return $this->sendResponse(Helper::success_message(213), $code = 213, $data);

            } else {
                
                throw new Exception(Helper::error_message(208), 208);

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

            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|exists:bookings,id', 
                'ratings' => 'required|min:1',
                'review' => 'required'
            ]);

            if($validator->fails()) {

                $error = implode(",", $validator->messages()->all());
                
                throw new Exception($error, 101);
            }

            DB::beginTransaction();

            // Check the booking is exists and belongs to the logged in user

            $booking_details = Booking::where('user_id', $request->id)->where('id', $request->booking_id)->first();

            if(!$booking_details) {

                throw new Exception(Helper::error_message(206), 206);
                
            }

            // Check the booking is eligible for review

            if($booking_details->status != BOOKING_CHECKOUT) {

                throw new Exception(Helper::error_message(214), 214);
                
            }

            $review_details = new BookingUserReview;

            $review_details->user_id = $booking_details->user_id;

            $review_details->provider_id = $booking_details->provider_id;

            $review_details->host_id = $booking_details->host_id;

            $review_details->booking_id = $booking_details->id;

            $review_details->ratings = $request->ratings ?: 0;

            $review_details->review = $request->review ?: "";

            $review_details->status = APPROVED;

            $review_details->save();

            $booking_details->status = BOOKING_COMPLETED;

            $booking_details->save();

            $host_details = Host::find($booking_details->host_id);

            if($host_details) {

                $host_details->total_ratings += 1;

                $host_details->overall_ratings = BookingUserReview::where('host_id', $booking_details->host_id)->avg('ratings');

                $host_details->save();
            }

            DB::commit();

            $data = ['booking_id' => $request->booking_id, 'booking_provider_review_id' => $review_details->id];

            $message = Helper::success_message(216); $code = 216; 

            return $this->sendResponse($message, $code, $data);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method bookings_checkin()
     *
     * @uses used to update the checkout status of booking
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */

    public function bookings_checkin(Request $request){

        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(), [

                'booking_id' => 'required|exists:bookings,id,user_id,'.$request->id
            ]);

            
            if($validator->fails()){

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error , 101);

            }
            
            $booking_details = Booking::where('bookings.id', $request->booking_id)->first();

            if(!$booking_details) {

                throw new Exception(Helper::error_message(206), 206);
            }

            $not_allowed_status = [BOOKING_CANCELLED_BY_USER, BOOKING_CANCELLED_BY_PROVIDER, BOOKING_COMPLETED, BOOKING_REFUND_INITIATED, BOOKING_CHECKIN, BOOKING_CHECKOUT, BOOKING_REVIEW_DONE];

            if(in_array($booking_details->status, $not_allowed_status)) {
                
                throw new Exception(Helper::error_message(218), 218);
            }
           
            $booking_details->status = BOOKING_CHECKIN;

            $booking_details->checkin = date("Y-m-d H:i:s");

            $booking_details->save();

            DB::commit();

            return $this->sendResponse(tr('check_in_message'), $code = "", $booking_details);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method bookings_checkout()
     *
     * @uses used to update the checkout status of booking
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param object $request
     *
     * @return response of details
     */

    public function bookings_checkout(Request $request){

        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(), [

                'booking_id' => 'required|exists:bookings,id,user_id,'.$request->id
            ]);

            if($validator->fails()){

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error , 101);

            }
            
            $booking_details = Booking::where('bookings.id', $request->booking_id)->first();
            
            if(!$booking_details) {

                throw new Exception(Helper::error_message(206), 206);
            }

            $not_allowed_status = [BOOKING_CANCELLED_BY_USER, BOOKING_CANCELLED_BY_PROVIDER, BOOKING_COMPLETED, BOOKING_REFUND_INITIATED, BOOKING_CHECKOUT, BOOKING_CHECKOUT, BOOKING_REVIEW_DONE];

            if(in_array($booking_details->status, $not_allowed_status)) {
                
                throw new Exception(Helper::error_message(219), 219);
            }

            $booking_details->status = BOOKING_CHECKOUT;

            $booking_details->checkout = date("Y-m-d H:i:s");

            $booking_details->save();

            DB::commit();

            // Send notifications to user

            return $this->sendResponse(tr('check_out_message'), $code = "", []);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method bookings ()
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
    public function bookings(Request $request) {

        try {

            $bookings = Booking::where('bookings.user_id' , $request->id)
                            ->CommonResponse()
                            ->paginate(15);

            return $this->sendResponse($message = "", $success_code = "", $bookings);

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

            $base_query = BookingProviderReview::where('booking_provider_reviews.user_id', $request->id)->CommonResponse();

            $reviews = $base_query->skip($this->skip)->take($this->take)->get();

            return $this->sendResponse($message = "", $success_code = "", $reviews);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method reviews_for_providers()
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
    public function reviews_for_providers(Request $request) {

        try {

            $base_query = BookingUserReview::where('booking_user_reviews.user_id', $request->id)->CommonResponse();

            $reviews = $base_query->skip($this->skip)->take($this->take)->get();

            return $this->sendResponse($message = "", $success_code = "", $reviews);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

}
