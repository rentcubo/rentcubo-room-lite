<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper, App\Helpers\EnvEditorHelper;

use DB, Log, Hash, Setting, Auth, Validator, Exception;

use App\Provider, App\BookingUserReview, App\BookingProviderReview;

use App\Category, App\SubCategory, App\Lookups;

use App\Host, App\HostDetails;

use App\Booking, App\BookingPayment;

class ProviderController extends Controller
{

    protected $providerApi, $loginProvider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProviderApiController $API, Request $request)
    {
        $this->providerApi = $API;

        $this->middleware('auth:provider', ['except' => ['signup_form', 'signup', 'forgot_password', 'forgot_password_send']]);

        if($request->id || $request->provider_id) {

            $provider_id = $request->id ?: ($request->provider_id ?: 0);
            
            $this->loginProvider = $provider_details = Provider::find($provider_id);

            $request->request->add([
                'id' => $provider_details->id,
                'token' => $provider_details->token,
                'device_type' => DEVICE_WEB,
                'login_by' => 'manual',
            ]);
        }

        if(Auth::guard('provider')->check()) {

            $this->loginProvider = Auth::guard('provider')->user();

            $request->request->add([
                'id' => \Auth::guard('provider')->user()->id,
                'token' => \Auth::guard('provider')->user()->token,
                'device_type' => DEVICE_WEB,
                'login_by' => 'manual',
            ]);
        }

        if($this->loginProvider) {

            if($this->loginProvider->status != PROVIDER_APPROVED) {

                Auth::guard('provider')->logout();
                            
                return redirect()->route('provider.logout')->with('flash_success', Helper::error_message(1000))->send();
            }

        }
    }


	/**
     * @method change_password()
     *
     * @uses To display user change password if they want to change based on logged in user
     *
     * @created Bhawya N
     *
     * @updated
     *
     * @param 
     *
     * @return change password form page 
     */ 
    public function change_password() {

        return view('provider.account.change_password')->with('account_page' , 'account-change_password');
    }

	/**
     * @method change_password_save()
     *
     * @uses  Used to change the provider password
     *
     * @created Bhawya
     *
     * @updated
     *
     * @param 
     *
     * @return view page 
     */

    public function change_password_save(Request $request) {

        try {

            DB::begintransaction();

            $validator = Validator::make($request->all(), [       
            	'password' => 'required|confirmed|min:6',
    			'old_password' => 'required|min:6',
            ]);
            
            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);
                
            }

            $provider_details = Provider::find($request->id);

            if(!$provider_details) {

                Auth::guard('provider')->logout();

                throw new Exception(tr('provider_details_not_found'), 101);

            }

            if(Hash::check($request->old_password,$provider_details->password)) {
                $provider_details->password = Hash::make($request->password);

                $provider_details->save();

                DB::commit();

                Auth::guard('provider')->logout();

                return redirect()->route('provider.home')->with('flash_success', tr('password_change_success'));
                
            } else {

                throw new Exception(tr('password_mismatch'));
            }


        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->withInput()->with('flash_error' , $error);

        }    
    
    }

    /**
     * @method profile()
     *
     * @uses Update Provider details
     *
     * @created Bhawya N
     *
     * @updated
     *
     * @param 
     *
     * @return change password form page 
     */ 
    public function profile() {

        $provider_details = Provider::find(Auth::guard('provider')->user()->id);

        return view('provider.account.profile')
                ->with('page' , 'provider-profile')
                ->with('sub_page' , 'provider-profile')
                ->with('provider_details', $provider_details);
        

    }

    /**
     * @method update_profile()
     *
     * @uses Update Provider details
     *
     * @created Bhawya N
     *
     * @updated
     *
     * @param 
     *
     * @return change password form page 
     */ 
    public function update_profile() {

        $provider_details = Provider::find(Auth::guard('provider')->user()->id);

        return view('provider.account.profile_update')->with('account_page' , 'account-update_profile')->with('provider_details', $provider_details);

    }

    /**
     * @method update_profile_save()
     *
     * @uses Update Provider details
     *
     * @created Bhawya N
     *
     * @updated
     *
     * @param 
     *
     * @return change password form page 
     */ 
    public function update_profile_save(Request $request) {

        try {
            
            DB::begintransaction();

            $validator = Validator::make( $request->all(), [
                'name' => 'required|max:191',
                'email' => $request->provider_id ? 'required|email|max:191|unique:providers,email,'.$request->provider_id.',id' : 'required|email|max:191|unique:providers,email,NULL,id',
                'password' => $request->provider_id ? "" : 'required|min:6',
                'mobile' => 'required|digits_between:6,13',
                'picture' => 'mimes:jpg,png,jpeg',
                ]
            );

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());
                throw new Exception($error, 101);

            }

            $providers_details = $request->provider_id ? Provider::find($request->provider_id) : new Provider;

            $new_user = NO;

            if($providers_details->id) {

                $message = tr('provider_updated_success'); 

            }

            $providers_details->name = $request->has('name') ? $request->name: $providers_details->name;

            $providers_details->email = $request->has('email') ? $request->email: $providers_details->email;

            $providers_details->mobile = $request->has('mobile') ? $request->mobile : '';

            $providers_details->description = $request->has('description') ? $request->description : '';

            // Upload picture

            if($request->hasFile('picture') ) {

                if($request->provider_id) {

                    Helper::delete_file($providers_details->picture, PROFILE_PATH_PROVIDER); 
                    // Delete the old pic
                }

                $providers_details->picture = Helper::upload_file($request->file('picture'), PROFILE_PATH_PROVIDER);
            }

            if($providers_details->save()) {
                
                DB::commit(); 

                return redirect()->route('provider.update_profile', ['provider_details' => $providers_details])->with('flash_success', $message);

            } 

            throw new Exception(tr('provider_save_failed'));
            
        } catch(Exception $e){ 

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->withInput()->with('flash_error', $error);

        }  
    }

    /**
     * @method delete_account_form()
     *
     * To delete the account of the provider who was logged in 
     *
     * @created Bhawya N
     *
     * @updated
     *
     * @param - 
     *
     * @return render the delete account form
     */ 
    public function delete_account_form() {

        return view('provider.account.delete_account')->with('account_page' , 'account-delete_account');

        $active = 'delete_account';

        $login_by = Auth::guard('provider')->check() ? Auth::guard('provider')->user()->login_by : '';

        return view('user.account.delete_account')->with('account_page' , 'account-delete_account');

        return view('provider.account.delete_account')->with('active', $active)->with('login_by', $login_by);

    }


    /**
     * @method delete_account()
     *
     * @uses Update Provider details
     *
     * @created Bhawya N
     *
     * @updated
     *
     * @param 
     *
     * @return profile view form page 
     */ 
    public function delete_account(Request $request) {        

        try {

            DB::beginTransaction();

            $response = $this->providerApi->delete_account($request)->getData();

            if ($response->success) {

                DB::commit();

                return redirect()->route('provider.login')->with('flash_success', $response->message);

            } else {

                throw new Exception($response->error);
                
            }

        } catch(Exception $e) {

            DB::rollback();

            return back()->with('flash_error', $e->getMessage());

        }

    }

    /**
     * @method forgot_password()
     *
     * @uses If the provider, forget his/her password, they can have option to reset their password
     *
     * @created Bhawya
     *
     * @edited
     *
     * @param - 
     *
     * @return 
     */ 
    public function forgot_password() {

        if (!Auth::guard('provider')->check()) {

            $disable = true;

            if( envfile('MAIL_USERNAME') &&  envfile('MAIL_PASSWORD')) {

                $disable = false;
            }
           
            return view('provider.auth.forgot_password')->with('disable', $disable);

        } else {

            return back();
        }

    }

    /**
     * @method send_forgot_mail()
     *
     * @user If the provider, forget his/her password, they can have option to reset their password
     *
     * @created Bhawya
     *
     * @edited
     *
     * @param string $request - Email
     *
     * @return response of forgot password mail
     */ 
    public function forgot_password_send(Request $request) {

        try {
            $request->request->add([
                'device_type'=>DEVICE_WEB,
                'login_by'=>'manual',
            ]);

            $response = $this->providerApi->forgot_password($request)->getData();

            if ($response->success) {

                return redirect()->route('provider.login.form')->with('flash_success', tr('mail_sent_success'));

            } else {

                throw new Exception($response->error);
                
            }

        } catch (Exception $e) {

            $message = $e->getMessage();

            $code = $e->getCode();

            return back()->with('flash_error', $message);

        }
   
    }

    /**
     * @method login_form()
     *
     * @uses To dispaly user registration form
     *
     * @created Bhawya
     *
     * @updated 
     *
     * @param empty $request - As of now no attributes passed
     *
     * @return view page
     */ 
    public function login_form(Request $request) {

        return view('provider.auth.login');

    }

    /**
     * @method signup_form()
     *
     * @uses To dispaly user registration form
     *
     * @created Bhawya
     *
     * @updated 
     *
     * @param empty $request - As of now no attributes passed
     *
     * @return view page
     */ 
    public function signup_form(Request $request) {

        return view('provider.auth.register');
    }


    /**
     * @method signup()
     *
     * @uses To register a new user
     *
     * @created Bhawya
     *
     * @updated
     *
     * @param object $request - Firstname, lastname, email, password, confirm Password
     *
     * @return success/failure message with corresponding page
     */
    public function signup(Request $request) {
        
        try {

            DB::beginTransaction();

            $validator = Validator::make( $request->all(),array(
                    'first_name' => 'required|min:2 | max:20|regex:/^[a-z\d\-.\s]+$/i',
                    'last_name' => 'required|min:2 | max:20|regex:/^[a-z\d\-.\s]+$/i',
                    'mobile'=>'required|digits_between:4,16',
                    'password' => 'required|min:6|max:128|confirmed',
                )
            );

            $verifiy_message_and_redirect = NO;
            
            if($validator->fails()) {

                $errors = implode(',', $validator->messages()->all());

                throw new Exception($errors);

            } else {

                $request->request->add([
                    'device_type' => DEVICE_WEB,
                    'login_by'=>'manual',
                    'device_token' => "123456",
                    'name'=> $request->first_name." ".$request->last_name,
                ]);

                $response = $this->providerApi->register($request)->getData();

                Log::info("verifiy_message_and_redirect".print_r($response, true));
                
                if ($response->success) {

                    Auth::guard('provider')->loginUsingId($response->data->provider_id);

                } else {

                    $login_status = [1000, 1002, 1003, 1004, 1005, 1006, 1007];

                    if(in_array($response->error_code, $login_status)) {

                        $verifiy_message_and_redirect = YES;

                    } else {

                        if ($response->error_code != 1007)  {

                            throw new Exception($response->error);

                        }

                    }
                    
                }

            }

            DB::commit();

            if ($verifiy_message_and_redirect) {

                return redirect()->route('provider.login')->with('flash_success', $response->error);

            }

            return redirect()->route('index')->with('flash_success', $response->message);

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('provider.signup')->withInput()->with('flash_error', $e->getMessage());

        }

    }

    /**
     * @method hosts_index()
     *
     * @uses show hosts list
     *
     * @created Bhawya
     *
     * @updated 
     *
     * @param -
     *
     * @return view page
     */    

    public function hosts_index(Request $request) {        

        $base_query = Host::orderBy('created_at','desc');

        $base_query = $base_query->where('provider_id',$request->provider_id);

        $hosts = $base_query->paginate(10);

        return view('provider.hosts.index')
                    ->with('page_title', "Hosts")
                    ->with('hosts', $hosts);
    
    }

    /**
     * @method hosts_create()
     *
     * @uses To create host details
     *
     * @created Bhawya
     *
     * @updated 
     *
     * @param 
     * 
     * @return view page
     *
     */

    public function hosts_create() {
        
        $host_details = new Host;

        $host_types = Lookups::where('type', 'host_room_type')->get();

        $categories = Category::orderby('name', 'asc')->get();

        foreach ($categories as $key => $category_details) {

            $category_details->is_selected = NO;
        }
       
        return view('provider.hosts.create')
                    ->with('page' , 'hosts')
                    ->with('sub_page','hosts-create')
                    ->with('host_types', $host_types)
                    ->with('categories', $categories)
                    ->with('host_details', $host_details)
                    ->with('sub_categories' , []);
    }

    /**
     * @method hosts_save()
     *
     * @uses To save/update the new/existing service locations object details
     *
     * @created Bhawya
     *
     * @updated Bhawya
     *
     * @param integer (request) $service_location_id, service_location (request) details
     * 
     * @return success/failure message
     *
     */
    
    public function hosts_save(Request $request) {
       
        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(),[
                'host_name' => 'required|max:191',
                'host_type' => 'required',
                'description' => 'required',
                'picture' => 'mimes:jpg,png,jpeg',
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
                'base_price' => 'required|min:0',
                'full_address' => 'required',
                'host_id' => 'exists:hosts,id'
            ]);
            
            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);

            }

            $host_details = new Host;

            $message = tr('host_created_success');

            if( $request->host_id != '') {

                $host_details = Host::find($request->host_id);

                $message = tr('host_updated_success');

            } else {
               
                $host_details->status = APPROVED;

                $host_details->unique_id = uniqid();

                $host_details->is_admin_verified = ADMIN_HOST_VERIFIED;

                $host_details->admin_status = ADMIN_HOST_PENDING;

                $host_details->status = HOST_OWNER_PUBLISHED;

                $host_details->uploaded_by = PROVIDER;

                $host_details->picture = asset('placeholder.jpg');

            }

            $host_details->provider_id = $request->provider_id ?: 0;

            $host_details->category_id = $request->category_id;

            $host_details->sub_category_id = $request->sub_category_id;

            $host_details->host_name = $request->host_name;

            $host_details->host_type = $request->host_type ?: "";

            $host_details->description = $request->description ?: "";

            $host_details->full_address = $request->full_address ?: "";

            $host_details->total_guests = $request->total_guests ?: 0;

            $host_details->base_price = $request->base_price ?: 0;

            if($request->hasFile('picture') ) {

                if($request->host_id) {

                    Helper::delete_file($host_details->picture, FILE_PATH_HOST); // Delete the old pic
                }

                $host_details->picture = Helper::upload_file($request->file('picture'), FILE_PATH_HOST);
            }

            if($host_details->save()) {
                
                DB::commit();

                return redirect()->route('provider.hosts.index',['provider_id' => $host_details->provider_id])->with('flash_success', $message);
            }

            throw new Exception(tr('host_save_failed'), 101);
            
        } catch (Exception $e) {
            
            DB::rollback();

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());
        }

    }

    /**
     * @method hosts_delete
     *
     * @uses To delete the service locations details based on service location id
     *
     * @created Bhawya
     *
     * @updated Bhawya
     *
     * @param integer (request) $service_location_id
     * 
     * @return success/failure message
     *
     */
    public function hosts_delete(Request $request) {

        try {


            $host_details = Host::find($request->host_id);

            $provider_id = $host_details->provider_id;

            if(!$host_details) {

                throw new Exception(tr('host_not_found'), 101);                
            }

            DB::beginTransaction();

            if($host_details->delete() ) {

                DB::commit();

                if($host_details->picture !='' ) {

                    Helper::delete_file($host_details->picture, FILE_PATH_HOST); 
                }

                return redirect()->route('provider.hosts.index',['provider_id' => $provider_id])->with('flash_success', tr('host_deleted_success')); 

            }

            throw new Exception(tr('host_delete_error'));
            
        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('provider.hosts.index', ['provider_id' => $provider_id])->with('flash_error', $e->getMessage());

        }
   
    }

    /**
     * @method hosts_edit()
     *
     * @uses To display and update host details based on the host id
     *
     * @created Bhawya
     *
     * @updated Bhawya
     *
     * @param object $request - host Id
     * 
     * @return redirect view page 
     *
     */
    public function hosts_edit(Request $request) {

        try {

            $host_details = Host::find($request->host_id);

            if(!$host_details) {

                return back()->with('flash_error', tr('host_not_found'));
            }

            $host_types = Lookups::where('type', 'host_room_type')->get();

            foreach ($host_types as $key => $host_type_details) {

                $host_type_details->is_selected = NO;

                if($host_details->host_type == $host_type_details->value) {

                    $host_type_details->is_selected = YES;

                }
            }

            $categories = Category::orderby('name', 'asc')->get();

            foreach ($categories as $key => $category_details) {

                $category_details->is_selected = NO;

                if($host_details->category_id == $category_details->id) {

                    $category_details->is_selected = YES;

                }
            }

            $sub_categories = SubCategory::where('category_id' , $host_details->category_id)->get();

            // change status for selected sub category

            foreach ($sub_categories as $key => $sub_category_details) {

                $sub_category_details->is_selected = $host_details->sub_category_id == $sub_category_details->id ? YES : NO;
            }

            return view('provider.hosts.edit')
                        ->with('page', 'hosts')
                        ->with('sub_page', 'hosts-view')
                        ->with('host_details', $host_details)
                        ->with('host_types', $host_types)
                        ->with('categories', $categories)
                        ->with('sub_categories',$sub_categories);


        } catch (Exception $e) {

            return redirect()->route('admin.hosts.index')->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method hosts_view()
     *
     * @uses view the hosts details based on hosts id
     *
     * @created Bhawya 
     *
     * @updated Bhawya
     *
     * @param object $request - host Id
     * 
     * @return View page
     *
     */
    public function hosts_view(Request $request) {

        try {

            $validator = Validator::make($request->all(), [
                'host_id' => 'required|exists:hosts,id',
            ]);

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                return back()->with('flash_error', $error);
            }
            
            // load host details based on host_id
            $host = Host::find($request->host_id);

            if(!$host) {

                throw new Exception(tr('host_not_found'), 101);   
            }
        
            // Load category name
            $host->category_name = $host->categoryDetails->name ?? '' ;

            // Load sub category name
            $host->sub_category_name = $host->subCategoryDetails->name ?? '' ;

            // Load the user and provider reviews based on provider_id and host_id
            
            $user_reviews = BookingUserReview::where('provider_id', $host->provider_id)
                ->where('host_id', $host->id)
                ->get();

            return view('provider.hosts.view')
                        ->with('page', 'hosts')
                        ->with('sub_page','hosts-view')
                        ->with('host' , $host)
                        ->with('user_reviews' , $user_reviews);
                        
       } catch(Exception $e) {

            return back()->with('flash_error', $e->getMessage());

        }
    }

        /**
     * @method bookings_index()
     *
     * @uses show Bookings details based on login user
     *
     * @created Bhawya
     *
     * @updated 
     *
     * @param -
     *
     * @return view page
     */    

    public function bookings_index(Request $request) {    

        try {

            $base_query = Booking::where('bookings.provider_id' , Auth::guard('provider')->user()->id);

            if($request->host_id) {
                $base_query = $base_query->where('bookings.host_id', $request->host_id);
            }

            $bookings = $base_query->CommonResponse()->orderBy('bookings.created_at', 'desc')->paginate(15);

            foreach ($bookings as $key => $booking_details) {

                $booking_details->total_formatted = formatted_amount($booking_details->total);

                $booking_details->status_text = booking_status($booking_details->status);

                $booking_details->buttons = booking_btn_status($booking_details->status, $booking_details->booking_id);

                $booking_details->user_name = $booking_details->userDetails->name ?? '' ;

            }

            return view('provider.bookings.index')->with('bookings' , $bookings);

        } catch (Exception $e) {

            return back()->with('flash_error', $e->getMessage());
        }

    }

    /**
     * @method bookings_cancel()
     *
     * @uses Cancel the bookings by the user
     *
     * @created Bhawya
     *
     * @edited
     *
     * @param string $request - Bookings ID
     *
     * @return response of bookings cancel
     */ 
    public function bookings_cancel(Request $request) {

        try {

            $response = $this->providerApi->bookings_cancel($request)->getData();
            
            if ($response->success) {

                return back()->with('flash_success', $response->message);

            } else {

                throw new Exception($response->error);
                
            }

        } catch (Exception $e) {

            return back()->with('flash_error', $e->getMessage());

        }
   
    }

    /**
     * @method bookings_view()
     *
     * @uses view the booking details based on hosts id
     *
     * @created Vithya R 
     *
     * @updated Vithya R 
     *
     * @param object $request - host Id
     * 
     * @return View page
     *
     */
    public function bookings_view(Request $request) {

        try {

            $response = $this->providerApi->bookings_view($request)->getData();

            if ($response->success) {

                $booking_details = $response->data;

                return view('provider.bookings.view')->with('booking_details' , $booking_details);

            } else {

                throw new Exception($response->error);
                
            }

        } catch(Exception $e) {

            return back()->with('flash_error', $e->getMessage());
        }
    }

}
