<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB, Hash, Setting, Auth, Validator, Exception;

use App\Helpers\Helper, App\Helpers\HostHelper;

use App\User;

use App\Host, App\Booking;

use App\BookingUserReview;

use App\HostGallery, App\Category, App\SubCategory;

class UserController extends Controller
{
	protected $userApi;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserApiController $API, Request $request)
    {
        $this->userApi = $API;

        $this->middleware('auth', ['except' => ['signup', 'signup_save', 'forgot_password', 'forgot_password_send']]);

        if($request->id) {
            
            $user_details = User::find($request->id);

            $request->request->add([
                'id' => $user_details->id,
                'token' => $user_details->token,
                'device_type' => DEVICE_WEB,
                'login_by' => 'manual',
            ]);
        }

        if(\Auth::check()) {

            $request->request->add([
                'id' => \Auth::user()->id,
                'token' => \Auth::user()->token,
                'device_type' => DEVICE_WEB,
                'login_by' => 'manual',
            ]);
        }

    }

	/**
	 *
	 * @method home()
	 *
	 * @uses 
	 *
	 * @created Vithya R
	 *
	 * @updated Vithya R
	 *
	 * @param 
	 *
	 * @return 
	 */

	public function home(Request $request) {

        $base_query = Host::UserBaseResponse()->orderBy('hosts.updated_at' , 'desc');

        if($request->category_id) {

            $base_query = $base_query->where('hosts.category_id', $request->category_id);

        }

        if($request->sub_category_id) {

            $base_query = $base_query->where('hosts.sub_category_id', $request->sub_category_id);
            
        }

        $hosts = $base_query->paginate(15);

        $categories = [];

        if(!$request->category_id && !$request->sub_category_id) {

            $categories = Category::CommonResponse()->where('categories.status' , APPROVED)->orderBy('name' , 'asc')->get();
        }

        $sub_categories = [];

        if($request->category_id) {
            
            $sub_categories = SubCategory::CommonResponse()->where('sub_categories.status' , APPROVED)->where('category_id', $request->category_id)->orderBy('sub_categories.name' , 'asc')->get();

        }

        foreach ($hosts as $key => $host_details) {

            $host_details->base_price_formatted = formatted_amount($host_details->base_price);

            $host_galleries = HostGallery::where('host_id', $host_details->host_id)->select('picture', 'caption')->skip(0)->take(3)->get();

            $host_details->gallery = $host_galleries;

        }

	 	return view('user.home')->with('hosts', $hosts)->with('categories', $categories)->with('sub_categories', $sub_categories);

	} 
	
	/**
     * @method profile()
     *
     * @uses Update user details
     *
     * @created Bhawya N
     *
     * @updated
     *
     * @param 
     *
     * @return profile view form page 
     */ 
    public function profile() {
        
        $user_details = Auth::user();

        return view('user.account.profile')
                ->with('account_page' , 'account-user-profile')
                ->with('user_details', $user_details);

    }

    /**
     * @method update_profile()
     *
     * @uses Update User details
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

        $user_details = Auth::user();

        return view('user.account.profile_update')
                ->with('account_page' , 'account-update_profile')
                ->with('user_details', $user_details);

    }

    /**
     * @method update_profile_save()
     *
     * @uses Update User details
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
                'email' => $request->user_id ? 'required|email|max:191|unique:users,email,'.$request->user_id.',id' : 'required|email|max:191|unique:users,email,NULL,id',
                'password' => $request->user_id ? "" : 'required|min:6',
                'mobile' => 'required|digits_between:6,13',
                'picture' => 'mimes:jpg,png,jpeg',
                ]
            );

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);

            }

            $users_details = $request->user_id ? User::find($request->user_id) : new User;

            $new_user = NO;

            if($users_details->id) {

                $message = tr('user_updated_success'); 

            }

            $users_details->name = $request->has('name') ? $request->name: $users_details->name;

            $users_details->email = $request->has('email') ? $request->email: $users_details->email;

            $users_details->mobile = $request->has('mobile') ? $request->mobile : '';

            $users_details->description = $request->has('description') ? $request->description : '';

            // Upload picture

            if($request->hasFile('picture') ) {

                if($request->user_id) {

                    Helper::delete_file($users_details->picture, PROFILE_PATH_USER); 
                    // Delete the old pic
                }

                $users_details->picture = Helper::upload_file($request->file('picture'), PROFILE_PATH_USER);
            }

            if($users_details->save()) {
                
                DB::commit(); 

                return redirect()->route('user.update_profile', ['user_details' => $users_details])->with('flash_success', $message);

            } 

            throw new Exception(tr('user_save_failed'));
            
        } catch(Exception $e){ 

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->withInput()->with('flash_error', $error);

        }  
    }

    /**
     * @method forgot_password()
     *
     * @uses If the user, forget his/her password, they can have option to reset their password
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

        if (!Auth::guard('user')->check()) {

            $disable = true;

            if( envfile('MAIL_USERNAME') &&  envfile('MAIL_PASSWORD')) {

                $disable = false;
            }
           
            return view('user.auth.forgot_password')->with('disable', $disable);

        } else {

            return back();
        }

    }

    /**
     * @method forgot_password_send()
     *
     * @user If the user, forget his/her password, they can have option to reset their password
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

            $response = $this->userApi->forgot_password($request)->getData();

            if ($response->success) {

                return redirect()->route('user.login.form')->with('flash_success', tr('mail_sent_success'));

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

        return view('user.account.change_password')->with('account_page' , 'account-change_password');

    }

	/**
     * @method change_password_save()
     *
     * @uses  Used to change the user password
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

            $user_details = User::find($request->id);

            if(!$user_details) {

                Auth::guard('user')->logout();

                throw new Exception(tr('user_details_not_found'), 101);

            }

            if(Hash::check($request->old_password,$user_details->password)) {

                $user_details->password = Hash::make($request->password);

                $user_details->save();

                DB::commit();

                Auth::guard('user')->logout();

                return back()->with('flash_success', tr('password_change_success'));
                
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
     * @method delete_account()
     *
     * To delete the account of the user who was logged in 
     *
     * @created Bhawya N
     *
     * @updated
     *
     * @param - 
     *
     * @return render the delete account form
     */ 
    public function delete_account() {

        return view('user.account.delete_account')->with('account_page' , 'account-delete_account');

    }


    /**
     * @method delete_account()
     *
     * @uses Update user details
     *
     * @created Bhawya N
     *
     * @updated Vithya R
     *
     * @param 
     *
     * @return profile view form page 
     */ 
    public function delete_account_update(Request $request) {

        try {

            DB::beginTransaction();

            $response = $this->userApi->delete_account($request)->getData();

            if ($response->success) {

                DB::commit();

                return redirect()->route('user.login')->with('flash_success', $response->message);

            } else {

                throw new Exception($response->error);
                
            }

        } catch(Exception $e) {

            DB::rollback();

            return back()->with('flash_error', $e->getMessage());

        }

    }

    /**
     * @method signup()
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
    public function signup(Request $request) {

        if (!Auth::check()) {

            return view('user.auth.register');

        } else {

            return redirect()->route('index');

        }

    }

    /**
     * @method signup_save()
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
    public function signup_save(Request $request) {
        
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

                $response = $this->userApi->register($request)->getData();
               
                if ($response->success) {

                    Auth::loginUsingId($response->data->user_id);

                } else {

                    if ($response->error_code != 7001)  {

                        throw new Exception($response->error);

                    } else {

                        $verifiy_message_and_redirect = YES;
                    }
                    
                }

            }

            DB::commit();

            if ($verifiy_message_and_redirect) {

                return redirect()->route('user.login')->with('flash_success', $response->error);

            }

            return redirect()->route('index')->with('flash_success', $response->message);

        } catch(Exception $e) {

            DB::rollback();

            $code = $e->getCode();

            $message = $e->getMessage();

            return redirect()->route('user.sign_up')->withInput()->with('flash_error', $message);

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
     * @return hosts view page
     */    

    public function hosts_index(Request $request) {        

        $base_query = Host::orderBy('created_at','desc');

        $page = "hosts"; $sub_page = "hosts-view"; $page_title = tr('view_hosts');

        $base_query = $base_query->where('is_admin_verified',ADMIN_HOST_VERIFIED)
            ->where('admin_status',ADMIN_HOST_APPROVED)
            ->where('status',HOST_OWNER_PUBLISHED);

        $hosts = $base_query->get();

        foreach ($hosts as $key => $host_details) {
       
            // User Ratings based on hosts // @todo
            $user_reviews = BookingUserReview::where('host_id',$host_details->id)->get();
            if(count($user_reviews) > 0) {    
                $ones = $user_reviews->where('ratings', 1)->count();
                $twos = $user_reviews->where('ratings', 2)->count();
                $three = $user_reviews->where('ratings', 3)->count();
                $fours = $user_reviews->where('ratings', 4)->count();
                $fives = $user_reviews->where('ratings', 5)->count();
               
                $host_details->ratings = (5*$fives + 4*$fours + 3*$three + 2*$twos + 1*$ones) / ($fives+$fours+$three+$twos+$ones);
            }
            
        }

        return view('user.hosts.index')
                    ->with('page', $page)
                    ->with('sub_page', $sub_page)
                    ->with('page_title', $page_title)
                    ->with('hosts', $hosts);
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

            // Load Provider name
            $host->provider_name = $host->providerDetails->username ?? '' ;

            $host->provider_image = $host->providerDetails->picture ?? '' ;


            // Load the user and provider reviews based on provider_id and host_id

            // User Ratings based on hosts

            $user_reviews = BookingUserReview::where('host_id',$host->id)->get();

            $host->total_ratings = $user_reviews->count();

            $host->overall_ratings = $user_reviews->avg('ratings');

            $host->checkin = date('d/m/Y');

            $host->checkout = date('d/m/Y', strtotime($host->checkin . " + 1 day"));

            return view('user.hosts.view')->with('host' , $host);
                        
       } catch(Exception $e) {

            $error = $e->getMessage();

            return redirect()->route('user.home')->with('flash_error', $error);

        }
    }

    /**
     * @method bookings_create()
     *
     * @user create bookings for the selected host_id
     *
     * @created Bhawya
     *
     * @edited
     *
     * @param string $request - host_id
     *
     * @return response of bookings create
     */ 
    public function bookings_create(Request $request) {

        try {

            $response = $this->userApi->bookings_create($request)->getData();

            if ($response->success) {

                return redirect()->route('user.bookings.index',['user_id' => $response->data->user_id ])->with('flash_success', tr('bookings_completed'));

            } else {

                throw new Exception($response->error);
                
            }

        } catch (Exception $e) {

            return back()->withInput()->with('flash_error', $e->getMessage());
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

            $bookings = Booking::where('bookings.user_id' , $request->user_id)
                            ->CommonResponse()
                            ->orderBy('bookings.created_at', 'desc')
                            ->paginate(15);

            foreach ($bookings as $key => $booking_details) {

                $booking_details->total_formatted = formatted_amount($booking_details->total);

                $booking_details->status_text = booking_status($booking_details->status);

                $booking_details->buttons = booking_btn_status($booking_details->status, $booking_details->booking_id);

                $booking_details->provider_name = $booking_details->providerDetails->name ?? '' ;

            }

            return view('user.bookings.index')->with('bookings' , $bookings);

        } catch (Exception $e) {

            return back()->with('flash_error', $e->getMessage());
        }

    }


    /**
     * @method bookings_checkin()
     *
     * @uses used to update the checkin status of booking
     *
     * @created Bhawya
     *
     * @updated
     *
     * @param object $request
     *
     * @return response of details
     */
    public function bookings_checkin(Request $request){

        try {

            $response = $this->userApi->bookings_checkin($request)->getData();

            if ($response->success) {

                return back()->with('flash_success', $response->message);

            } else {

                throw new Exception($response->error);
                
            }

        } catch(Exception $e) {

            return back()->with('flash_error', $e->getMessage());
        }
    }

    /**
     * @method bookings_checkout()
     *
     * @uses used to update the checkout status of booking
     *
     * @created Bhawya
     *
     * @updated
     *
     * @param object $request
     *
     * @return response of details
     */
    public function bookings_checkout(Request $request){

        try {

            $response = $this->userApi->bookings_checkout($request)->getData();
            
            if ($response->success) {

                return back()->with('flash_success', $response->message);

            } else {

                throw new Exception($response->error);
                
            }

        } catch(Exception $e) {

            return back()->with('flash_error', $e->getMessage());
        }
    }

    /**
     * @method bookings_review()
     *
     * @uses used to update the checkout status of booking
     *
     * @created Bhawya
     *
     * @updated
     *
     * @param object $request
     *
     * @return response of details
     */
    public function bookings_review(Request $request){

        try {

            $response = $this->userApi->bookings_rating_report($request)->getData();
            
            if ($response->success) {

                return back()->with('flash_success', $response->message);

            } else {

                throw new Exception($response->error);
                
            }

        } catch(Exception $e) {

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

            $response = $this->userApi->bookings_cancel($request)->getData();
            
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

            $response = $this->userApi->bookings_view($request)->getData();

            if ($response->success) {

                $booking_details = $response->data;

                return view('user.bookings.view')->with('booking_details' , $booking_details);

            } else {

                throw new Exception($response->error);
                
            }

        } catch(Exception $e) {

            return back()->with('flash_error', $e->getMessage());
        }
    }

}
