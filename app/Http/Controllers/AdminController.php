<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper, App\Helpers\EnvEditorHelper;

use DB, Hash, Setting, Auth, Validator, Exception, Enveditor;

use App\Admin;

use App\Provider;

use App\User;

use App\Booking, App\BookingPayment;

use App\BookingProviderReview, App\BookingUserReview;

use App\Category, App\SubCategory;

use App\Host, App\HostGallery;

use App\Settings, App\StaticPage, App\Lookups;

use Carbon\Carbon;

class AdminController extends Controller {
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

        $this->middleware('auth:admin');
    }

    /**
     * @method users_index()
     *
     * @uses Show the application dashboard.
     *
     * @created vithya
     *
     * @updated vithya
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function index() {
       
        if(Auth::guard('admin')->user()) { 
        
            $date = date("Y-m-d H:i:s"); 

            $dashboard_data = [];

            $dashboard_data['total_users'] = User::count();

            $dashboard_data['total_providers'] = Provider::count();

            $dashboard_data['total_hosts'] = Host::count();

            $dashboard_data['total_verified_hosts'] = Host::where('is_admin_verified', ADMIN_HOST_VERIFIED)->count();

            $dashboard_data['total_unverified_hosts'] = Host::whereIn('is_admin_verified', [ADMIN_HOST_VERIFY_PENDING, ADMIN_HOST_VERIFY_DECLINED])->count();

            $dashboard_data['total_bookings'] = Booking::count();

            $dashboard_data['total_revenue'] = BookingPayment::where('status', PAID)->sum('booking_payments.total');
            
            $dashboard_data['today_revenue'] = BookingPayment::whereDate('booking_payments.updated_at',today())->where('status', PAID)->sum('booking_payments.paid_amount');

            // Recent datas

            $recent_users= User::orderBy('updated_at' , 'desc')->skip(0)->take(6)->get();

            $recent_providers= Provider::orderBy('updated_at' , 'desc')->skip(0)->take(6)->get(); 

            $recent_bookings = Booking::orderBy('updated_at' , 'desc')->skip(0)->take(6)->get();

            $data = json_decode(json_encode($dashboard_data));

            // last x days page visiters count for graph
            $views = last_x_days_page_view(10);

            // hosts analytics
            $hosts_count = get_hosts_count();
            
            return view('admin.dashboard')
                ->with('page' , 'dashboard')
                ->with('sub_page' , 'dashboard')
                ->with('data', $data)
                ->with('recent_users', $recent_users)
                ->with('recent_providers', $recent_providers)
                ->with('recent_bookings', $recent_bookings)
                ->with('views', $views)
                ->with('hosts_count', $hosts_count);
        } else {
            
            return view('admin.auth.login');
        }
    }

    /**
     * @method users_index()
     *
     * @uses To list out users details 
     *
     * @created Anjana
     *
     * @updated vithya
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function users_index() {

        $users = User::orderBy('updated_at','desc')->get();

        return view('admin.users.index')
                    ->with('page','users')
                    ->with('sub_page' , 'users-view')
                    ->with('users' , $users);
    }

    /**
     * @method users_create()
     *
     * @uses To create user details
     *
     * @created  Anjana
     *
     * @updated vithya
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function users_create() {

        $user_details = new User;

        return view('admin.users.create')
                    ->with('page' , 'users')
                    ->with('sub_page','users-create')
                    ->with('user_details', $user_details);           
    }

    /**
     * @method users_edit()
     *
     * @uses To display and update user details based on the user id
     *
     * @created Anjana
     *
     * @updated Anjana
     *
     * @param object $request - User Id
     * 
     * @return redirect view page 
     *
     */
    public function users_edit(Request $request) {

        try {

            $user_details = User::find($request->user_id);

            if(!$user_details) { 

                throw new Exception(tr('user_not_found'), 101);
            }

            return view('admin.users.edit')
                    ->with('page' , 'users')
                    ->with('sub_page','users-view')
                    ->with('user_details' , $user_details); 
            
        } catch(Exception $e) {

            $error = $e->getMessage();

            return redirect()->route('admin.users.index')->with('flash_error' , $error);
        }
    
    }

    /**
     * @method users_save()
     *
     * @uses To save the users details of new/existing user object based on details
     *
     * @created Anjana
     *
     * @updated vithya
     *
     * @param object request - User Form Data
     *
     * @return success message
     *
     */
    public function users_save(Request $request) {

        try {

            DB::begintransaction();

            $validator = Validator::make( $request->all(), [
                'name' => 'required|max:191',
                'email' => $request->user_id ? 'required|email|max:191|unique:users,email,'.$request->user_id.',id' : 'required|email|max:191|unique:users,email,NULL,id',
                'password' => $request->user_id ? "" : 'required|min:6',
                'mobile' => $request->mobile ? 'digits_between:6,13' : '',
                'picture' => 'mimes:jpg,png,jpeg',
                'description' => 'max:191',
                'user_id' => 'exists:users,id'
                ]
            );

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);

            }

            $user_details = $request->user_id ? User::find($request->user_id) : new User;

            $is_new_user = NO;

            if($user_details->id) {

                $message = tr('user_updated_success'); 

            } else {

                $is_new_user = YES;

                $user_details->password = ($request->password) ? \Hash::make($request->password) : null;

                $message = tr('user_created_success');

                $user_details->email_verified_at = date('Y-m-d H:i:s');

                $user_details->picture = asset('placeholder.jpg');

                $user_details->is_verified = USER_EMAIL_VERIFIED;

            }

            $user_details->name = $request->name ?: $user_details->name;

            $user_details->email = $request->email ?: $user_details->email;

            $user_details->mobile = $request->mobile ?: '';

            $user_details->description = $request->description ?: '';

            $user_details->login_by = $request->login_by ?: 'manual';

            // Upload picture

            if($request->hasFile('picture') ) {

                if($request->user_id) {

                    Helper::delete_file($user_details->picture, COMMON_FILE_PATH); 
                    // Delete the old pic
                }

                $user_details->picture = Helper::upload_file($request->file('picture'), COMMON_FILE_PATH);
            }

            if($user_details->save()) {

                if($is_new_user == YES) {

                    /**
                     * @todo Welcome mail notification
                     */

                    $user_details->is_verified = USER_EMAIL_VERIFIED;

                    $user_details->save();

                }
                    
                DB::commit(); 

                return redirect(route('admin.users.view', ['user_id' => $user_details->id]))->with('flash_success', $message);

            } 

            throw new Exception(tr('user_save_failed'));
            
        } catch(Exception $e){ 

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->withInput()->with('flash_error', $error);

        } 

    }

    /**
     * @method users_view()
     *
     * @uses view the users details based on users id
     *
     * @created Anjana 
     *
     * @updated vithya
     *
     * @param object $request - User Id
     * 
     * @return View page
     *
     */
    public function users_view(Request $request) {
       
        try {
      
            $user_details = User::find($request->user_id);

            if(!$user_details) { 

                throw new Exception(tr('user_not_found'), 101);                
            }            
                 
            return view('admin.users.view')
                        ->with('page', 'users') 
                        ->with('sub_page','users-view') 
                        ->with('user_details' , $user_details);

            
        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('flash_error', $error);
        }
    
    }

    /**
     * @method users_delete()
     *
     * @uses delete the user details based on user id
     *
     * @created Anjana
     *
     * @updated  
     *
     * @param object $request - User Id
     * 
     * @return response of success/failure details with view page
     *
     */
    public function users_delete(Request $request) {

        try {

            DB::begintransaction();

            $user_details = User::find($request->user_id);
            
            if(!$user_details) {

                throw new Exception(tr('user_not_found'), 101);                
            }

            if($user_details->delete()) {

                DB::commit();

                return redirect()->route('admin.users.index')->with('flash_success',tr('user_deleted_success'));   

            } 
            
            throw new Exception(tr('user_delete_failed'));
            
        } catch(Exception $e){

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->with('flash_error', $error);

        }       
         
    }

    /**
     * @method users_status
     *
     * @uses To update user status as DECLINED/APPROVED based on users id
     *
     * @created Anjana
     *
     * @updated 
     *
     * @param object $request - User Id
     * 
     * @return response success/failure message
     *
     **/
    public function users_status(Request $request) {

        try {

            DB::beginTransaction();

            $user_details = User::find($request->user_id);

            if(!$user_details) {

                throw new Exception(tr('user_not_found'), 101);
                
            }

            $user_details->status = $user_details->status ? DECLINED : APPROVED ;

            if($user_details->save()) {

                DB::commit();

                $message = $user_details->status ? tr('user_approve_success') : tr('user_decline_success');

                return redirect()->back()->with('flash_success', $message);
            }
            
            throw new Exception(tr('user_status_change_failed'));

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->route('admin.users.index')->with('flash_error', $error);

        }

    }

    /**
     * @method providers_index
     *
     * @uses Get the providers list
     *
     * @created Anjana
     *
     * @updated Vidhya
     *
     * @param 
     * 
     * @return view page
     *
     */
    public function providers_index() {

        $providers = Provider::orderBy('updated_at','desc')->get();

        return view('admin.providers.index')
                    ->with('page' , 'providers')
                    ->with('sub_page','providers-view')
                    ->with('providers' , $providers);

    }

    /**
     * @method providers_create
     *
     * @uses To create providers details
     *
     * @created Anjana
     *
     * @updated  
     *
     * @param 
     * 
     * @return view page
     *
     */
    public function  providers_create() {

        $provider_details = new Provider;

        return view('admin.providers.create')
                    ->with('page' , 'providers')
                    ->with('sub_page','providers-create')
                    ->with('provider_details', $provider_details);
    
    }

    /**
     * @method providers_edit()
     *
     * @uses To display and update provider details based on the provider id
     *
     * @created Anjana
     *
     * @updated Anjana 
     *
     * @param object $request - provider Id
     * 
     * @return redirect view page 
     *
     */    
    public function providers_edit(Request $request) {

        try {
      
            $provider_details = Provider::find($request->provider_id);

            if(!$provider_details) {

                throw new Exception(tr('provider_not_found'), 101);
                
            }

            return view('admin.providers.edit')
                        ->with('page', 'providers')
                        ->with('sub_page', 'providers-view')
                        ->with('provider_details', $provider_details);
            
        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('flash_error', $error);
        }
    
    }

    /**
     * @method providers_save
     *
     * @uses To save the providers details of new/existing provider object based on details
     *
     * @created Anjana
     *
     * @updated
     *
     * @param object $request - providers object details
     * 
     * @return response of success/failure response details
     *
     */
    public function providers_save(Request $request) {

        try {
            
            DB::begintransaction();

            $validator = Validator::make( $request->all(), [
                'name' => 'required|max:191',
                'email' => $request->provider_id ? 'required|email|max:191|unique:providers,email,'.$request->provider_id.',id' : 'required|email|max:191|unique:providers,email,NULL,id',
                'password' => $request->provider_id ? "" : 'required|min:6',
                'mobile' => 'required|digits_between:6,13',
                'picture' => 'mimes:jpg,png,jpeg',
                'description' => 'required|max:191'
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

            } else {

                $new_user = YES;

                $message = tr('provider_created_success');

                $providers_details->password = ($request->password) ? \Hash::make($request->password) : null;

                $providers_details->email_verified_at = date('Y-m-d H:i:s');

                $providers_details->picture = asset('placeholder.jpg');

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

                return redirect()->route('admin.providers.view', ['provider_id' => $providers_details->id])->with('flash_success', $message);

            } 

            throw new Exception(tr('provider_save_failed'));
            
        } catch(Exception $e){ 

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->withInput()->with('flash_error', $error);

        }   
        
    }

    /**
     * @method providers_view
     *
     * @uses view the selected provider details 
     *
     * @created Anjana
     *
     * @updated
     *
     * @param Integer $request - provider id
     * 
     * @return view page
     *
     **/
    public function providers_view(Request $request) {

        $provider_details = Provider::find($request->provider_id);

        if(!$provider_details) {

            return redirect()->route('admin.providers.index')->with('flash_error',tr('provider_not_found'));
        }

        return view('admin.providers.view')
                    ->with('page', 'providers')
                    ->with('sub_page','providers-view')
                    ->with('provider_details' , $provider_details);
    
    }

    /**
     * @method providers_delete
     *
     * @uses To delete the providers details based on selected provider id
     *
     * @created Anjana
     *
     * @updated 
     *
     * @param Integer $request - provider id
     * 
     * @return response of success/failure details
     *
     **/
    public function  providers_delete(Request $request) {

        try {

            DB::beginTransaction();

            $provider_details = provider::find($request->provider_id);

            if(!$provider_details) {

                throw new Exception(tr('provider_not_found'), 101);
                
            }

            if($provider_details->delete()) {

                DB::commit();

                return redirect()->route('admin.providers.index')->with('flash_success',tr('provider_delete_success')); 
            } 
            
            throw new Exception(tr('provider_delete_failed'));

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->route('admin.providers.index')->with('flash_error', $error);

        }
   
    }

    /**
     * @method providers_status
     *
     * @uses To update provider status as DECLINED/APPROVED based on provide id
     *
     * @created Anjana
     *
     * @updated 
     *
     * @param Integer $request - provider id
     * 
     * @return response success/failure message
     *
     **/
    public function providers_status(Request $request) {

        try {

            DB::beginTransaction();

            $provider_details = Provider::find($request->provider_id);

            if(!$provider_details) {

                throw new Exception(tr('provider_not_found'), 101);
                
            }

            $provider_details->status = $provider_details->status ? DECLINED : APPROVED;

            if($provider_details->save()) {

                DB::commit();

                $message = $provider_details->status ? tr('provider_approve_success') : tr('provider_decline_success');

                return redirect()->back()->with('flash_success', $message);
            }

            throw new Exception(tr('provider_status_change_failed'), 101);

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->route('admin.providers.index')->with('flash_error', $error);

        }

    }

    /**
     * @method categories_index
     *
     * @uses Get the categories list
     *
     * @created vithya
     *
     * @updated  
     *
     * @param 
     * 
     * @return view page
     *
     */
    public function categories_index() {

        $categories = Category::orderBy('updated_at','desc')->get();

        return view('admin.categories.index')
                    ->with('page' , 'categories')
                    ->with('sub_page','categories-view')
                    ->with('categories' , $categories);
    }

    /**
     * @method categories_create
     *
     * @uses To create categories details
     *
     * @created vithya
     *
     * @updated  
     *
     * @param 
     * 
     * @return view page
     *
     */
    public function  categories_create() {

        $category_details = new Category;
        
        return view('admin.categories.create')
                ->with('page' , 'categories')
                ->with('sub_page','categories-create')
                ->with('category_details', $category_details);
    
    }
  
    /**
     * @method categories_edit()
     *
     * @uses To display and update category details based on the category id
     *
     * @created Anjana
     *
     * @updated Anjana
     *
     * @param object $request - category Id
     * 
     * @return redirect view page 
     *
     */
    public function categories_edit(Request $request){

        try {
      
            $category_details = Category::find($request->category_id);

            if(!$category_details) {

                return redirect()->route('admin.categories.index')->with('flash_error',tr('category_not_found'));
            }

            return view('admin.categories.edit')
                        ->with('page','categories')
                        ->with('sub_page','categories-view')
                        ->with('category_details',$category_details);
            
        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('flash_error', $error);
        }
    
    }

    /**
     * @method categories_save
     *
     * @uses To save the details based on category or to create a new category
     *
     * @created vithya
     *
     * @updated
     *
     * @param object $request - category object details
     * 
     * @return response of success/failure response details
     *
     */
    
    public function categories_save(Request $request) {

        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(),[
                'name' => 'required|max:191',
                'picture' => 'mimes:jpg,png,jpeg',
                'description' => 'max:191',
                'picture' => 'mimes:jpg,png,jpeg'
            ]);
            
            if( $validator->fails() ) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);

            }
            
            $category_details = new Category;

            $message = tr('category_created_success');

            if($request->category_id != '') {

                $category_details = Category::find($request->category_id);

                $message = tr('category_updated_success');

            } else {
               
                $category_details->status = APPROVED;

                $category_details->unique_id = uniqid();

            }

            $category_details->name = $category_details->provider_name = $request->name ?: $category_details->name;

            $category_details->type = 1; // Not used now 

            $category_details->description = $request->description ?: "";

            if($request->hasFile('picture')) {

                if($request->category_id){

                    //Delete the old picture located in categories file
                    Helper::delete_file($category_details->picture,FILE_PATH_CATEGORY);
                }

                $category_details->picture = Helper:: upload_file($request->file('picture'), FILE_PATH_CATEGORY);

            }

            if($category_details->save()) {

                DB::commit();

                return redirect()->route('admin.categories.view', ['category_id' => $category_details->id])->with('flash_success', $message);

            }

            return back()->with('flash_error', tr('category_save_failed'));
           

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->withInput()->with('flash_error', $error);

        }
        
    }

    /**
     * @method categories_view
     *
     * @uses view the selected category details 
     *
     * @created vithya
     *
     * @updated
     *
     * @param integer $category_id
     * 
     * @return view page
     *
     */
    public function categories_view(Request $request) {

        $category_details = Category::find($request->category_id);

        if(!$category_details) {

            return redirect()->route('admin.categories.index')->with('flash_error',tr('category_not_found'));
        }
       
        return view('admin.categories.view')
                    ->with('page', 'categories')
                    ->with('sub_page','categories-view')
                    ->with('category_details' , $category_details);
    
    }

    /**
     * @method categories_delete
     *
     * @uses To delete the category details based on selected category id
     *
     * @created vithya
     *
     * @updated 
     *
     * @param integer $category_id
     * 
     * @return response of success/failure details
     *
     */
    public function  categories_delete(Request $request) {

        try {

            DB::beginTransaction();

            $category_details = Category::find($request->category_id);

            if(!$category_details) {

                throw new Exception(tr('category_not_found'), 101);
                
            }

            if($category_details->delete()) {

                DB::commit();

                return redirect()->route('admin.categories.index')->with('flash_success',tr('category_deleted_success')); 

            } 

            throw new Exception(tr('category_delete_failed'));
            
        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->route('admin.categories.index')->with('flash_error', $error);

        }
   
    }

    /**
     * @method categories_status
     *
     * @uses To update category status as DECLINED/APPROVED based on category id
     *
     * @created anjana
     *
     * @updated vithya
     *
     * @param integer $category_id
     * 
     * @return response success/failure message
     *
     */
    public function categories_status(Request $request) {

        try {

            DB::beginTransaction();

            $category_details = Category::find($request->category_id);

            if(!$category_details) {

                throw new Exception(tr('category_not_found'), 101);
                
            }

            $category_details->status = $category_details->status ? DECLINED : APPROVED;

            if($category_details->save()) {

                DB::commit();

                $message = $category_details->status ? tr('category_approve_success') : tr('category_decline_success');

                return redirect()->back()->with('flash_success', $message);
            }

            throw new Exception(tr('category_status_change_failed'));


        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->with('flash_error', $error);

        }

    }


     /**
     * @method sub_categories_index
     *
     * @uses Get the sub_categories list
     *
     * @created Anjana
     *
     * @updated  
     *
     * @param 
     * 
     * @return view page
     *
     */

    public function sub_categories_index(Request $request) {

        $base_query = SubCategory::orderBy('updated_at','desc');

        $category_details = [];

        if($request->category_id) {

            $base_query = SubCategory::where('category_id', $request->category_id);

            $category_details = Category::find($request->category_id);
        }

        $sub_categories = $base_query->get();

        return view('admin.sub_categories.index')
                    ->with('page' , 'sub_categories')
                    ->with('sub_page','sub_categories-view')
                    ->with('sub_categories' , $sub_categories)
                    ->with('category_details' , $category_details);

    }

    /**
     * @method sub_categories_create
     *
     * @uses To create sub_categories details
     *
     * @created Anjana
     *
     * @updated  
     *
     * @param 
     * 
     * @return view page
     *
     */
    public function  sub_categories_create() {

        $sub_category_details = new SubCategory;

        $categories = Category::orderby('name', 'asc')->get();

        foreach ($categories as $key => $category_details) {

            $category_details->is_selected = NO;
        }
        
        return view('admin.sub_categories.create')
                ->with('page' , 'sub_categories')
                ->with('sub_page','sub_categories-create')
                ->with('sub_category_details', $sub_category_details)
                ->with('categories',$categories);
    
    }

    /**
     * @method sub_categories_edit()
     *
     * @uses To display and update sub_category details based on the sub_category id
     *
     * @created Anjana
     *
     * @updated Anjana
     *
     * @param object $request - sub_category Id
     * 
     * @return redirect view page 
     *
     */    
    public function sub_categories_edit(Request $request) {

        try {

            $sub_category_details = SubCategory::find($request->sub_category_id);
            
            if(!$sub_category_details) {

                throw new Exception(tr('sub_category_not_found'), 101);

            }

            $categories = Category::orderby('name', 'asc')->get();

            foreach ($categories as $key => $category_details) {

                $category_details->is_selected = NO;

                if($sub_category_details->category_id == $category_details->id) {

                    $category_details->is_selected = YES;

                }
            
            }

            return view('admin.sub_categories.edit')
                    ->with('page','sub_categories')
                    ->with('sub_page','sub_categories-view')
                    ->with('sub_category_details',$sub_category_details)
                    ->with('categories',$categories);

        } catch(Exception $e) {

            $error = $e->getMessage();

            return redirect()->route('admin.sub_categories.index')->with('flash_error',$error);

        }                

    
    }

    /**
     * @method sub_categories_save
     *
     * @uses To save the details based on sub_category or to create a new sub_category
     *
     * @created Anjana
     *
     * @updated
     *
     * @param object $request - sub_category object details
     * 
     * @return response of success/failure response details
     *
     */
    public function sub_categories_save(Request $request) {

        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(),[
                'name' => 'required|max:191',
                'provider_name' => 'max:191',
                'description' => 'max:191',
                'picture' => 'mimes:jpg,png,jpeg',
                'category_id' =>'required|exists:categories,id'
            ]);
            
            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);
            }
            
            $sub_category_details = new SubCategory;

            $message = tr('sub_category_created_success');

            if($request->sub_category_id != '') {

                $sub_category_details = SubCategory::find($request->sub_category_id);

                $message = tr('sub_category_updated_success');

            } else {
               
                $sub_category_details->status = APPROVED;

            }

            $sub_category_details->name = $sub_category_details->provider_name = $request->name ?: $sub_category_details->name;

            $sub_category_details->type = 1;
            
            $sub_category_details->category_id = $request->category_id ?: $sub_category_details->category_id;
            
            $sub_category_details->description = $request->description ?: "";

            if($request->hasFile('picture')) {

                if($request->sub_category_id){

                    //Delete the old picture located in sub_categories file

                    Helper::delete_file($sub_category_details->picture,FILE_PATH_SUB_CATEGORY);
                }

                $sub_category_details->picture = Helper:: upload_file($request->file('picture'), FILE_PATH_SUB_CATEGORY);

            }

            if($sub_category_details->save()) {

                DB::commit();

                return redirect()->route('admin.sub_categories.view', ['sub_category_id' => $sub_category_details->id])->with('flash_success', $message);

            } 

            return back()->with('flash_error', tr('sub_category_save_failed'));
            

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->withInput()->with('flash_error', $error);

        }
        
    }

    /**
     * @method sub_categories_view
     *
     * @uses view the selected sub_category details 
     *
     * @created Anjana
     *
     * @updated
     *
     * @param integer $sub_category_id
     * 
     * @return view page
     *
     */
    public function sub_categories_view(Request $request) {

        try {

            $sub_category_details = SubCategory::find($request->sub_category_id);

            if(!$sub_category_details) {

                throw new Exception(tr('sub_category_not_found'), 101);
            }

            $sub_category_details->category_name = Category::where('id',$sub_category_details->category_id)->pluck('name')->first();
            
            return view('admin.sub_categories.view')
                        ->with('page', 'sub_categories')
                        ->with('sub_page','sub_categories-view')
                        ->with('sub_category_details' , $sub_category_details);

        } catch(Exception $e) {

            $error = $e->getMessage();

            return redirect()->route('admin.sub_categories.index')->with('flash_error',$error);

        }
    
    }

    /**
     * @method sub_categories_delete
     *
     * @uses To delete the sub_category details based on selected sub_category id
     *
     * @created Anjana
     *
     * @updated 
     *
     * @param integer $sub_category_id
     * 
     * @return response of success/failure details
     *
     */
    public function sub_categories_delete(Request $request) {
        
        try {

            DB::beginTransaction();

            $sub_category_details = SubCategory::find($request->sub_category_id);

            if(!$sub_category_details) {

                throw new Exception(tr('sub_category_not_found'), 101);
                
            }

            if($sub_category_details->delete()) {

                DB::commit();

                return redirect()->route('admin.sub_categories.index')->with('flash_success',tr('sub_category_deleted_success')); 

            }

            throw new Exception(tr('sub_category_delete_failed'));

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->route('admin.sub_categories.index')->with('flash_error', $error);

        }
   
    }

    /**
     * @method sub_categories_status
     *
     * @uses To update sub_category status as DECLINED/APPROVED based on sub_category id
     *
     * @created Anjana
     *
     * @updated 
     *
     * @param integer $sub_category_id
     * 
     * @return response success/failure message
     *
     */
    public function sub_categories_status(Request $request) {

        try {

            DB::beginTransaction();

            $sub_category_details = SubCategory::find($request->sub_category_id);

            if(!$sub_category_details) {

                throw new Exception(tr('sub_category_not_found'), 101);
                
            }

            $sub_category_details->status = $sub_category_details->status ? DECLINED : APPROVED;

            if( $sub_category_details->save()) {

                DB::commit();

                $message = $sub_category_details->status ? tr('sub_category_approve_success') : tr('sub_category_decline_success');

                return redirect()->back()->with('flash_success', $message);
            }
            
            throw new Exception(tr('sub_category_status_change_failed'), 101);

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->route('admin.sub_categories.index')->with('flash_error', $error);

        }

    }

    /**
     * @method hosts_index()
     *
     * @uses show hosts list
     *
     * @created vithya R
     *
     * @updated vithya R
     *
     * @param -
     *
     * @return view page
     */    

    public function hosts_index(Request $request) {

        $base_query = Host::orderBy('created_at','desc');

        $page = "hosts"; $sub_page = "hosts-view"; $page_title = tr('view_hosts');

        if($request->provider_id) {

            $base_query = $base_query->where('provider_id',$request->provider_id);

            // $page = "providers"; $sub_page = "providers-view";

            $provider_details = Provider::find($request->provider_id);

            $page_title = tr('view_hosts')." - ".$provider_details->name;

        } 

        if($request->category_id) {

            $base_query = $base_query->where('category_id',$request->category_id);

            // $page = "categories"; $sub_page = "categories-view";

            $category_details = Category::find($request->category_id);

            $page_title = tr('view_hosts')." - ".$category_details->name;

        }

        if($request->sub_category_id) {

            $base_query = $base_query->where('sub_category_id',$request->sub_category_id);

            // $page = "sub_categories"; $sub_page = "sub_categories-view";

            $sub_category_details = SubCategory::find($request->sub_category_id);

            $page_title = tr('view_hosts')." - ".$sub_category_details->name;

        }

        if($request->unverified == YES) {

            $base_query = $base_query->whereIn('is_admin_verified', [ADMIN_HOST_VERIFY_PENDING,ADMIN_HOST_VERIFY_DECLINED]);

            $page_title = tr('unveried_hosts');

            $sub_page = "hosts-unverified";

        }

        $hosts = $base_query->get();

        foreach ($hosts as $key => $host_details) {

            // get provider name & image
            $host_details->provider_name = $host_details->providerDetails->username ?? '' ;

        }

        return view('admin.hosts.index')
                    ->with('page', $page)
                    ->with('sub_page', $sub_page)
                    ->with('page_title', $page_title)
                    ->with('hosts', $hosts);
    }


    /**
     * @method hosts_create()
     *
     * @uses To create host details
     *
     * @created Anjana H
     *
     * @updated Anjana H
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
       
        $providers = Provider::orderby('name', 'asc')->where('status',APPROVED)->get();
       
        return view('admin.hosts.create')
                    ->with('page' , 'hosts')
                    ->with('sub_page','hosts-create')
                    ->with('host_types', $host_types)
                    ->with('categories', $categories)
                    // ->with('service_locations', $service_locations)
                    ->with('providers', $providers)
                    ->with('host_details', $host_details)
                    ->with('sub_categories' , []);
    }

    /**
     * @method hosts_edit()
     *
     * @uses To display and update host details based on the host id
     *
     * @created Anjana
     *
     * @updated vithya
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

            $providers = Provider::orderby('name', 'asc')->where('status',APPROVED)->get();
           
            foreach ($providers as $key => $provider_details) {

                $provider_details->is_selected = NO;

                if($host_details->provider_id == $provider_details->id) {

                    $provider_details->is_selected = YES;

                }
            }
            return view('admin.hosts.edit')
                        ->with('page', 'hosts')
                        ->with('sub_page', 'hosts-view')
                        ->with('host_details', $host_details)
                        ->with('host_types', $host_types)
                        ->with('categories', $categories)
                        ->with('sub_categories',$sub_categories)
                        ->with('providers',$providers);


        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->route('admin.hosts.index')->with('flash_error', $error);
        }
    
    }


    /**
     * @method hosts_save()
     *
     * @uses To save/update the new/existing service locations object details
     *
     * @created Anjana H
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
                'full_address' => 'required',
                'description' => 'required',
                'picture' => 'mimes:jpg,png,jpeg',
                'category_id' => 'required|exists:categories,id',
                'provider_id' => 'required|exists:providers,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
                'base_price' => 'required|min:0',
                'full_address' => 'required',
                'total_guests' => 'required|min:1',
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

                $host_details->admin_status = ADMIN_HOST_APPROVED;

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

            $host_details->full_address = $request->full_address;

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

                return redirect()->route('admin.hosts.view', ['host_id' => $host_details->id])->with('flash_success',$message);
            }

            throw new Exception(tr('host_save_failed'), 101);
            
        } catch (Exception $e) {
            
            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->withInput()->with('flash_error', $error);
        }

    }

    /**
     * @method hosts_view()
     *
     * @uses view the hosts details based on hosts id
     *
     * @created Anjana 
     *
     * @updated Anjana
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

            // get provider name & image
            $host->provider_name = $host->providerDetails->name ?? '' ;
           
            $host->provider_image = $host->providerDetails->picture ?? '' ;

             
            // Load HostGallerie details based on host id
            // $host_galleries = HostGallery::where('host_id', $request->host_id)->get();
        
            return view('admin.hosts.view')
                        ->with('page', 'hosts')
                        ->with('sub_page','hosts-view')
                        ->with('host' , $host);
                        // ->with('amenties' , $amenties)
                        // ->with('host_galleries' , $host_galleries);

       } catch(Exception $e) {

            $error = $e->getMessage();

            return back()->with('flash_error', $error);

        }
    }

    /**
     * @method hosts_availability_view()
     *
     * @uses view the hosts availability calendar view
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
    public function hosts_availability_view(Request $request) {

        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:hosts',
            ]);

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                return back()->with('flash_error', $error);
            }

            // Load the host details based on the host id
            $host_detail = Host::find($request->id);

            if(!$host_detail) {

                throw new Exception(tr('host_not_found'), 101);   
            }

            // Load the host availability details based on the host id
            $hosts_availability = HostAvailability::where('host_id', $request->id)->get();

            if(!$hosts_availability) {

                return redirect()->route('admin.hosts.index')->with('flash_error',tr('host_not_found'));  
            }

            return view('admin.hosts.availability')
                            ->with('page', 'hosts')
                            ->with('sub_page','hosts-view')
                            ->with('host_detail' , $host_detail)
                            ->with('hosts_availability' , $hosts_availability);

        } catch(Exception $e) {

            $error = $e->getMessage();

            return back()->with('flash_error', $error);

        }
       
    }

    /**
     * @method hosts_delete
     *
     * @uses To delete the service locations details based on service location id
     *
     * @created Anjana H
     *
     * @updated Anjana H
     *
     * @param integer (request) $service_location_id
     * 
     * @return success/failure message
     *
     */
    public function hosts_delete(Request $request) {

        try {

            DB::beginTransaction();

            $host_details = Host::find($request->host_id);

            if(!$host_details) {

                throw new Exception(tr('host_not_found'), 101);                
            }

            if($host_details->delete() ) {

                DB::commit();

                // Delete relavant image

                if($host_details->picture !='' ) {

                        Helper::delete_file($host_details->picture, FILE_PATH_HOST); 
                }

                return redirect()->route('admin.hosts.index')->with('flash_success',tr('host_deleted_success')); 

            }

            throw new Exception(tr('host_delete_error'));
            
        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->route('admin.hosts.index')->with('flash_error', $error);

        }
   
    }

    /**
     * @method hosts_status
     *
     * @uses To update host status as DECLINED/APPROVED based on host id
     *
     * @created Anjana H
     *
     * @updated Anjana H
     *
     * @param integer (request) $host_id
     * 
     * @return success/failure message
     *
     */
    public function hosts_status(Request $request) {

        try {

            DB::beginTransaction();
            
            $host_details = Host::find($request->host_id);

            if(!$host_details) {

                throw new Exception(tr('host_not_found'), 101);                
            }

            $host_details->admin_status = $host_details->admin_status ? DECLINED : APPROVED;

            if($host_details->save()) {

                DB::commit();

                $message = $host_details->admin_status ? tr('host_approve_success') : tr('host_decline_success');

                return redirect()->back()->with('flash_success', $message);
            }

            throw new Exception(tr('host_status_change_failed'));
        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->route('admin.hosts.index')->with('flash_error', $error);

        }

    }

    /**
     * @method hosts_verification_status
     *
     * @uses To change the host admin verification
     *
     * @created Anjana H
     *
     * @updated Anjana H
     *
     * @param integer $host_id
     * 
     * @return success/failure message
     *
     */
    public function hosts_verification_status(Request $request) {

        try {

            DB::beginTransaction();

            $host_details = Host::find($request->host_id);

            if(!$host_details) {

                throw new Exception(tr('host_not_found'), 101);                
            }

            $host_details->is_admin_verified = $host_details->is_admin_verified ? ADMIN_HOST_VERIFY_DECLINED : ADMIN_HOST_VERIFIED;

            $host_details->save();

            DB::commit();

            $message = $host_details->is_admin_verified ? tr('host_admin_verified') : tr('host_admin_verification_declined');

            return redirect()->back()->with('flash_success', $message);

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->route('admin.hosts.index')->with('flash_error', $error);

        }

    }

    /**
     * @method bookings_dashboard()
     *
     * @uses to display bookings analysis
     *
     * @created Anjana
     *
     * @updated Anjana
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function bookings_dashboard() {  
        
        $booking_data['total_bookings'] = Booking::count();

        $booking_data['bookings_completed'] = Booking::where('status', '=' ,BOOKING_COMPLETED)->count();

        $booking_data['bookings_cancelled_by_user'] = Booking::where('status', '=', BOOKING_CANCELLED_BY_USER)->count();

        $booking_data['bookings_cancelled_by_provider'] = Booking::where('status', '=', BOOKING_CANCELLED_BY_PROVIDER)->count();

        // today checkin and checkouts count
        $booking_data['today_bookings_checkin'] = Booking::where('status','=',BOOKING_CHECKIN)->whereDate('checkin',today())->count();

        $booking_data['today_bookings_checkout'] = Booking::where('status','=',BOOKING_CHECKOUT)->whereDate('checkout',today())->count();
       
        $data = (object) $booking_data;

        return view('admin.bookings.dashboard')
                ->with('page','bookings')
                ->with('sub_page' , 'bookings-dashboard')   
                ->with('data' , $data);    
    }  

    /**
     * @method bookings_index()
     *
     * @uses To list out bookings details 
     *
     * @created Anjana
     *
     * @updated vithya
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function bookings_index(Request $request) {

        $base_query = Booking::orderBy('updated_at','desc');

        // to get user based bookings
        if($request->user_id) {

            $module_details = User::find($request->user_id);

            if(!$module_details) {

                return redirect()->back()->with('flash_error',tr('user_not_found'));
            }

            $base_query = $base_query->where('bookings.user_id','=', $request->user_id);

        } 

        // to get provider based bookings
        if($request->provider_id) {
           
            $module_details = Provider::find($request->provider_id);

            if(!$module_details) {

                return redirect()->back()->with('flash_error',tr('provider_not_found'));
            }

            $base_query = $base_query->where('bookings.provider_id','=', $request->provider_id);
        }        

        // to get host based bookings
        if($request->host_id) {
           
            $module_details = Host::find($request->host_id);

            if(!$module_details) {

                return redirect()->back()->with('flash_error',tr('host_not_found'));
            }

            $base_query = $base_query->where('bookings.host_id','=', $request->host_id);
        }

        // Get booking details based on categories
        if($request->category_id) { 

            $host_ids = Host::where('category_id',$request->category_id)->pluck('id')->toArray();
            $base_query = $base_query->whereIn('bookings.host_id', $host_ids);
        }

        // Get booking details based on sub categories
        if($request->sub_category_id) { 

            $host_ids = Host::where('sub_category_id',$request->sub_category_id)->pluck('id')->toArray();
            $base_query = $base_query->whereIn('bookings.host_id', $host_ids);
        }        

        // To check and get bookings belongs to below status 

        $booking_status = array(BOOKING_INITIATE, BOOKING_ONPROGRESS, BOOKING_WAITING_FOR_PAYMENT , BOOKING_COMPLETED, BOOKING_CANCELLED_BY_USER, BOOKING_CANCELLED_BY_PROVIDER, BOOKING_REFUND_INITIATED, BOOKING_CHECKIN, BOOKING_CHECKOUT ); 

        if($request->status && in_array($request->status, $booking_status)) {
          
            $base_query = $base_query->where('status', '=', $request->status);
        }       

        if($request->status == BOOKING_CHECKIN) {

            $base_query = $base_query->whereDate('checkin',today());
        }        

        if($request->status == BOOKING_CHECKOUT) {

            $base_query = $base_query->whereDate('checkin',today());
        }
        
        $bookings = $base_query->get();

        // to assign related user,provider,host detatils to bookings
        foreach ($bookings as $key => $value) {

            $value->user_name = $value->userDetails ? $value->userDetails->name : '' ;

            $value->provider_name = $value->providerDetails ? $value->providerDetails->name : '' ;

            $value->host_name = $value->hostDetails ? $value->hostDetails->host_name : '' ;
        }   

        return view('admin.bookings.index')
                    ->with('page','bookings')
                    ->with('sub_page' , 'bookings')
                    ->with('bookings' , $bookings);
    }

    /**
     * @method bookings_view()
     *
     * @uses view the bookings details based on bookings id
     *
     * @created Anjana 
     *
     * @updated Anjana
     *
     * @param object $request - booking Id
     * 
     * @return View page
     *
     */
    public function bookings_view(Request $request) {
        
        try {

            $booking_details = Booking::find($request->booking_id);

            if(!$booking_details) {

                throw new Exception(tr('booking_not_found'), 101);   
            }

            // get users details

            $booking_details->user_name = $booking_details->userDetails->name ?? "User-Deleted";

            $booking_details->user_picture = $booking_details->userDetails->picture ?? asset('placeholder.jpg');


            $booking_details->provider_name = $booking_details->providerDetails->name ?? "provider-deleted";

            $booking_details->provider_picture = $booking_details->providerDetails->picture ?? asset('placeholder.jpg'); 


            $booking_details->host_name = $booking_details->hostDetails->host_name ?? "host-deleted";

            $booking_details->host_picture = $booking_details->hostDetails->picture ?? asset('placeholder.jpg');

            // get booking payments details
            $booking_details->payments_details = BookingPayment::BookingPaymentdetailsview()->where('booking_id','=',$booking_details->id)->first();

            return view('admin.bookings.view')
                    ->with('page', 'bookings-payments')
                    ->with('sub_page' ,'bookings-payments')
                    ->with('booking_details',$booking_details);

        } catch (Exception $e) {

            $error = $e->getMessage();

            return back()->with('flash_error', $error);

        }
    }

    /**
     * @method bookings_payments()
     *
     * @uses To display bookings payments
     *
     * @created Anjana
     *
     * @updated vithya
     *
     * @param 
     *
     * @return
     *
     **/
    public function bookings_payments(Request $request) {
        
        $base_query = BookingPayment::orderBy('created_at','DESC');

        if($request->user_id) {
                       
            $base_query = $base_query->where('booking_payments.user_id',$request->user_id);
        }        

        if($request->host_id) {
                       
            $base_query = $base_query->where('booking_payments.host_id',$request->host_id);
        }      


        if($request->provider_id) {
                       
            $base_query = $base_query->where('booking_payments.provider_id',$request->provider_id);
        } 

        $booking_payments = $base_query->paginate(10);

        // to assign related user,provider,host detatils to booking payments
        foreach ($booking_payments as $key => $value) {

            $value->booking_unique_id = $value->bookingDetails->unique_id ?? 'NONE' ;

            $value->user_name = $value->userDetails->name ?? 'user-deleted' ;

            $value->provider_name = $value->providerDetails->name ?? 'provider-deleted' ;

            $value->host_name = $value->hostDetails->host_name ?? 'host-deleted' ;
        }   

        return view('admin.revenues.booking_payments')
                ->with('page', 'bookings-payments')
                ->with('sub_page' ,'bookings-payments')
                ->with('booking_payments',$booking_payments);
    }

    /**
     * @method bookings_view()
     *
     * @uses Used to display the Single booking payments details.
     *
     * @created Anjana
     *
     * @updated 
     *
     * @param request $booking_id
     *
     * @return view page
     *
     *@todo change method name later
     */
    public function booking_view(Request $request) {

        $booking_payment_details = BookingPayment::Bookingpaymentdetailsview()->where('booking_payments.id', $request->booking_id)->first();

        return view('admin.booking.view')
                ->with('page', 'booking')
                ->with('sub_page' ,'booking-view')
                ->with('booking_payment_details',$booking_payment_details);

    }

    /**
     * @method reviews_providers()
     *
     * @uses To list out provider review details 
     *
     * @created Anjana
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function reviews_providers() {

        $provider_reviews=BookingProviderReview::leftjoin('users','users.id','=','booking_provider_reviews.user_id')
                    ->leftjoin('bookings','bookings.id','=','booking_provider_reviews.booking_id')
                    ->leftjoin('providers','providers.id','=','booking_provider_reviews.provider_id')
                    ->leftjoin('hosts','hosts.id','=','booking_provider_reviews.host_id')
                    ->select('users.id as user_id','users.name as user_name',
                        'providers.id as provider_id','providers.name as provider_name',
                        'bookings.id as booking_id',
                        'hosts.id as host_id','hosts.host_name',
                        'booking_provider_reviews.review',
                        'booking_provider_reviews.ratings',
                        'booking_provider_reviews.created_at',
                        'booking_provider_reviews.id as booking_review_id')
                    ->get();

        return view('admin.reviews.index')
                ->with('page', 'reviews')
                ->with('sub_page' , 'reviews-provider')
                ->with('reviews', $provider_reviews);
    } 

    /**
     * @method reviews_users()
     *
     * @uses To list out user review details 
     *
     * @created Anjana
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function reviews_users() {

        $user_reviews=BookingUserReview::leftjoin('users','users.id','=','booking_user_reviews.user_id')
        ->leftjoin('bookings','bookings.id','=','booking_user_reviews.booking_id')
        ->leftjoin('providers','providers.id','=','booking_user_reviews.provider_id')
        ->leftjoin('hosts','hosts.id','=','booking_user_reviews.host_id')
        ->select('users.id as user_id','users.name as user_name',
            'providers.id as provider_id','providers.name as provider_name',
            'bookings.id as booking_id',
            'hosts.id as host_id','hosts.host_name',
            'booking_user_reviews.review',
            'booking_user_reviews.ratings',
            'booking_user_reviews.created_at as date',
            'booking_user_reviews.id as booking_review_id')
        ->get();

        return view('admin.reviews.index')
                ->with('page', 'reviews')
                ->with('sub_page' , 'reviews-user')
                ->with('reviews', $user_reviews);
    }

    /**
     * @method reviews_users_view()
     *
     * @uses view the users review details based on booking_reviews_id
     * @created Anjana 
     *
     * @updated  
     *
     * @param integer booking_reviews_id
     * 
     * @return View page
     *
     */
    public function reviews_users_view(Request $request) {

        // Check the user_review_id
        $validator = Validator::make($request->all(), [
            'booking_review_id' => 'required|exists:booking_user_reviews,id',
        ]);

        if($validator->fails()) {

            $error = implode(',', $validator->messages()->all());

            return back()->with('flash_error', $error);
        }

        $user_reviews = BookingUserReview::leftjoin('users','users.id','=','booking_user_reviews.user_id')
                ->leftjoin('bookings','bookings.id','=','booking_user_reviews.booking_id')
                ->leftjoin('providers','providers.id','=','booking_user_reviews.provider_id')
                ->leftjoin('hosts','hosts.id','=','booking_user_reviews.host_id')
                ->select('users.id as user_id','users.name as user_name',
                    'providers.id as provider_id','providers.name as provider_name',
                    'bookings.id as booking_id',
                    'hosts.id as host_id','hosts.host_name',
                    'booking_user_reviews.review',
                    'booking_user_reviews.ratings',
                    'booking_user_reviews.created_at as created_at',
                    'booking_user_reviews.updated_at as updated_at')
                ->where('booking_user_reviews.id',$request->booking_review_id)
                ->first();

        return view('admin.reviews.view')
                        ->with('page', 'reviews') 
                        ->with('sub_page', 'reviews-user') 
                        ->with('reviews' , $user_reviews);
    
    }
    
    /**
     * @method reviews_providers_view()
     *
     * @uses view the providers review details based on booking_reviews_id
     *
     * @created Anjana 
     *
     * @updated  
     *
     * @param integer $booking_reviews_id
     * 
     * @return View page
     *
     */
    public function reviews_providers_view(Request $request) {

        // Check the provider review id

        $validator = Validator::make($request->all(), [
            'booking_review_id' => 'required|exists:booking_provider_reviews,id',
        ]);

        if($validator->fails()) {

            $error = implode(',', $validator->messages()->all());

            return back()->with('flash_error', $error);
        }

        $booking_review_details = BookingProviderReview::leftjoin('users','users.id','=','booking_provider_reviews.user_id')
                        ->leftjoin('bookings','bookings.id','=','booking_provider_reviews.booking_id')
                        ->leftjoin('providers','providers.id','=','booking_provider_reviews.provider_id')
                        ->leftjoin('hosts','hosts.id','=','booking_provider_reviews.host_id')
                        ->select('users.id as user_id','users.name as user_name',
                            'providers.id as provider_id','providers.name as provider_name',
                            'bookings.id as booking_id',
                            'hosts.id as host_id','hosts.host_name',
                            'booking_provider_reviews.review',
                            'booking_provider_reviews.ratings',
                            'booking_provider_reviews.created_at as date')
                        ->where('booking_provider_reviews.id',$request->booking_review_id)
                        ->first();

        if(!$booking_review_details) {
            
            return back()->with('flash_error', "");

        }

        return view('admin.reviews.view')
                ->with('page', 'reviews') 
                ->with('sub_page','reviews-provider') 
                ->with('reviews' , $booking_review_details);
    
    }

    /**
     * @method settings()
     *
     * @uses used to view the settings page
     *
     * @created Anjana 
     *
     * @updated 
     *
     * @param - 
     *
     * @return view page
     */
    public function settings() {

        $env_values = EnvEditorHelper::getEnvValues();

        return view('admin.settings.settings')
                ->with('env_values',$env_values)
                ->with('page' , 'settings')
                ->with('sub_page' , 'settings-view');
   
    }

    /**
     * @method settings_save()
     * 
     * @uses to update settings details
     *
     * @created Anjana H
     *
     * @updated Anjana H
     *
     * @param (request) setting details
     *
     * @return success/error message
     */
    public function settings_save(Request $request) {

        try {
            
            DB::beginTransaction();
            
            $validator = Validator::make($request->all() , 
                [
                    'site_logo' => 'mimes:jpeg,jpg,bmp,png',
                    'site_icon' => 'mimes:jpeg,jpg,bmp,png',

                ],
                [
                    'mimes' => tr('image_error')
                ]
            );

            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);
            }

            foreach( $request->toArray() as $key => $value) {

                if($key != '_token') {

                    $check_settings = Settings::where('key' ,'=', $key)->count();

                    if( $check_settings == 0 ) {

                        throw new Exception( $key.tr('settings_key_not_found'), 101);
                    }
                    
                    if( $request->hasFile($key) ) {
                                            
                        $file = Settings::where('key' ,'=', $key)->first();
                       
                        Helper::delete_file($file->value, FILE_PATH_SITE);

                        $file_path = Helper::upload_file($request->file($key) , FILE_PATH_SITE);    

                        $result = Settings::where('key' ,'=', $key)->update(['value' => $file_path]); 

                        if( $result == TRUE ) {
                     
                            DB::commit();
                   
                        } else {

                            throw new Exception(tr('settings_save_error'), 101);
                        } 
                   
                    } else {
                    
                        $result = Settings::where('key' ,'=', $key)->update(['value' => $value]);  
                    
                        if( $result == TRUE ) {
                         
                            DB::commit();
                       
                        } else {

                            throw new Exception(tr('settings_save_error'), 101);
                        } 

                    }  
 
                }
            }

            return back()->with('flash_success', tr('settings_update_success'));
            
        } catch (Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return back()->with('flash_error', $error);
        }
    }

    /**
     * @method env_settings_save()
     *
     * @uses used to update the email details for .env file
     *
     * @created Anjana
     *
     * @updated
     *
     * @param Form data
     *
     * @return view page
     */

    public function env_settings_save(Request $request) {

        try {

            $env_settings = ['MAIL_DRIVER' , 'MAIL_HOST' , 'MAIL_PORT' , 'MAIL_USERNAME' , 'MAIL_PASSWORD' , 'MAIL_ENCRYPTION' , 'MAILGUN_DOMAIN' , 'MAILGUN_SECRET' , 'FCM_SERVER_KEY', 'FCM_SENDER_ID' , 'FCM_PROTOCOL'];

            if($env_settings){

                foreach ($env_settings as $key => $data) {

                    if($request->$data){ 

                        \Enveditor::set($data,$request->$data);

                    } else{

                        \Enveditor::set($data,$request->$data);
                    }
                }
            }

            $message = tr('settings_update_success');

            return redirect()->route('clear-cache')->with('flash_success', $message);  

        } catch(Exception $e) {

            $error = $e->getMessage();

            return back()->withInput()->with('flash_error' , $error);

        }  

    }

    /**
     * @method profile()
     *
     * @uses  Used to display the logged in admin details
     *
     * @created Anjana
     *
     * @updated
     *
     * @param 
     *
     * @return view page 
     */

    public function profile() {

        return view('admin.account.profile')
                ->with('page', "dashboard")
                ->with('sub_page' , 'profile');

    }

    /**
     * @method profile_save()
     *
     * @uses Used to update the admin details
     *
     * @created Anjana
     *
     * @updated
     *
     * @param -
     *
     * @return view page 
     */

    public function profile_save(Request $request) {

        try {

            DB::beginTransaction();
            $validator = Validator::make( $request->all(), [
                    'name' => 'max:191',
                    'email' => $request->admin_id ? 'email|max:191|unique:admins,email,'.$request->admin_id : 'email|max:191|unique:admins,email,NULL',
                    'admin_id' => 'required|exists:admins,id',
                    'picture' => 'mimes:jpeg,jpg,png'
                ]
            );
            
            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);
                
            }

            $admin_details = Admin::find($request->admin_id);

            if(!$admin_details) {

                Auth::guard('admin')->logout();

                throw new Exception(tr('admin_details_not_found'), 101);

            }
        
            $admin_details->name = $request->name ?: $admin_details->name;

            $admin_details->email = $request->email ?: $admin_details->email;

            if($request->hasFile('picture') ) {
                
                Helper::delete_file($admin_details->picture, PROFILE_PATH_ADMIN); 
                
                $admin_details->picture = Helper::upload_file($request->file('picture'), PROFILE_PATH_ADMIN);
            }
            
            $admin_details->remember_token = Helper::generate_token();

            $admin_details->timezone = $request->timezone ?: $admin_details->timezone;

            $admin_details->save();

            DB::commit();

            return redirect()->route('admin.profile')->with('flash_success', tr('admin_profile_success'));


        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->withInput()->with('flash_error' , $error);

        }    
    
    }

    /**
     * @method change_password()
     *
     * @uses  Used to change the admin password
     *
     * @created Anjana
     *
     * @updated
     *
     * @param 
     *
     * @return view page 
     */

    public function change_password(Request $request) {

        try {

            DB::begintransaction();

            $validator = Validator::make($request->all(), [  
                'old_password' => 'required',            
                'password' => 'required|min:6|confirmed',
            ]);
            
            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);
                
            }

            $admin_details = Admin::find(Auth::guard('admin')->user()->id);

            if(!$admin_details) {

                Auth::guard('admin')->logout();

                throw new Exception(tr('admin_details_not_found'), 101);

            }

            if(Hash::check($request->old_password,$admin_details->password)) {

                $admin_details->password = Hash::make($request->password);

                $admin_details->save();

                DB::commit();

                Auth::guard('admin')->logout();

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
     * @method help()
     *
     * @uses display contact details
     *
     * @created Anjana 
     *
     * @updated
     *
     * @param 
     *
     * @return view page 
     */
    public function help(Request $request) {

        return view('admin.help')
                ->with('page' , 'help')
                ->with('sub_page' , 'help-view');

    }

    /**
     * @method static_pages_index()
     *
     * @uses Used to list the static pages
     *
     * @created vithya
     *
     * @updated vithya  
     *
     * @param -
     *
     * @return List of pages   
     */

    public function static_pages_index() {

        $static_pages = StaticPage::orderBy('updated_at' , 'desc')->get();

        return view('admin.static_pages.index')
                    ->with('page','static_pages')
                    ->with('sub_page',"static_pages-view")
                    ->with('static_pages',$static_pages);
    
    }

    /**
     * @method static_pages_create()
     *
     * @uses To create static_page details
     *
     * @created vithya
     *
     * @updated Anjana   
     *
     * @param
     *
     * @return view page   
     *
     */
    public function static_pages_create() {

        $static_keys = ['privacy' , 'terms' , 'help'];

        foreach ($static_keys as $key => $static_key) {

            // Check the record exists

            $check_page = StaticPage::where('type', $static_key)->first();

            if($check_page) {
                unset($static_keys[$key]);
            }
        }

        $static_keys[] = 'others';

        $static_page_details = new StaticPage;

        return view('admin.static_pages.create')
                ->with('page','static_pages')
                ->with('sub_page',"static_pages-create")
                ->with('static_keys', $static_keys)
                ->with('static_page_details',$static_page_details);
   
    }

    /**
     * @method static_pages_edit()
     *
     * @uses To display and update static_page details based on the static_page id
     *
     * @created Anjana
     *
     * @updated vithya
     *
     * @param object $request - static_page Id
     * 
     * @return redirect view page 
     *
     */
    public function static_pages_edit(Request $request) {

        try {

            $static_page_details = StaticPage::find($request->static_page_id);

            if(!$static_page_details) {

                throw new Exception(tr('static_page_not_found'), 101);
            }

            $static_keys = ['privacy', 'terms', 'help'];

            foreach ($static_keys as $key => $static_key) {

                // Check the record exists

                $check_page = StaticPage::where('type', $static_key)->first();

                if($check_page) {
                    unset($static_keys[$key]);
                }
            }

            $static_keys[] = 'others';

            $static_keys[] = $static_page_details->type;

            return view('admin.static_pages.edit')
                    ->with('page' , 'static_pages')
                    ->with('sub_page','static-pages-view')
                    ->with('static_keys' , array_unique($static_keys))
                    ->with('static_page_details' , $static_page_details);
            
        } catch(Exception $e) {

            $error = $e->getMessage();

            return redirect()->route('admin.static_pages.index')->with('flash_error' , $error);

        }
    }

    /**
     * @method static_pages_save()
     *
     * @uses Used to create/update the page details 
     *
     * @created vithya
     *
     * @updated vithya
     *
     * @param
     *
     * @return index page    
     *
     */
    public function static_pages_save(Request $request) {

        try {

            DB::beginTransaction();

            $validator = Validator::make( $request->all(), [
                    'title' => 'required|max:191',
                    'description' => 'required',
                    'type' => !$request->static_page_id ? 'required' : ""
                ]
            );
                   
            if($validator->fails()) {

                $error = implode(',', $validator->messages()->all());

                throw new Exception($error, 101);
                
            }

            if($request->static_page_id != '') {

                $static_page_details = StaticPage::find($request->static_page_id);

                $message = tr('static_page_updated_success');                    

            } else {

                $check_page = "";

                // Check the staic page already exists

                if($request->type != 'others') {

                    $check_page = StaticPage::where('type',$request->type)->first();

                    if($check_page) {

                        return back()->with('flash_error',tr('static_page_already_alert'));
                    }

                }

                $message = tr('static_page_created_success');

                $static_page_details = new StaticPage;

                $static_page_details->status = APPROVED;

            }

            $static_page_details->title = $request->title ?: ($static_page_details->title ? $static_page_details->title : "");

            $static_page_details->description = $request->description ? $request->description : ($static_page_details->description ? $static_page_details->description : "");

            $static_page_details->type = $request->type ?: ($static_page_details->type ? $static_page_details->type : "");

            if($static_page_details->save()) {

                DB::commit();

                return redirect()->route('admin.static_pages.view', ['static_page_id' => $static_page_details->id] )->with('flash_success', $message);

            } 

            throw new Exception(tr('static_page_save_failed'), 101);
                      
        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return back()->withInput()->with('flash_error', $error);

        }
    
    }

    /**
     * @method static_pages_delete()
     *
     * Used to view file of the create the static page 
     *
     * @created vithya
     *
     * @updated vithya R
     *
     * @param -
     *
     * @return view page   
     */

    public function static_pages_delete(Request $request) {

        try {

            DB::beginTransaction();

            $static_page_details = StaticPage::find($request->static_page_id);

            if(!$static_page_details) {

                throw new Exception(tr('static_page_not_found'), 101);
                
            }

            if($static_page_details->delete()) {

                DB::commit();

                return redirect()->route('admin.static_pages.index')->with('flash_success',tr('static_page_deleted_success')); 

            } 

            throw new Exception(tr('static_page_error'));

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->route('admin.static_pages.index')->with('flash_error', $error);

        }
    
    }

    /**
     * @method static_pages_view()
     *
     * @uses view the static_pages details based on static_pages id
     *
     * @created Anjana 
     *
     * @updated vithya
     *
     * @param object $request - static_page Id
     * 
     * @return View page
     *
     */
    public function static_pages_view(Request $request) {

        $static_page_details = StaticPage::find($request->static_page_id);

        if(!$static_page_details) {
           
            return redirect()->route('admin.static_pages.index')->with('flash_error',tr('static_page_not_found'));

        }

        return view('admin.static_pages.view')
                    ->with('page', 'static_pages')
                    ->with('sub_page','static_pages-view')
                    ->with('static_page_details' , $static_page_details);
    }

    /**
     * @method static_pages_status_change()
     *
     * @uses To update static_page status as DECLINED/APPROVED based on static_page id
     *
     * @created vithya
     *
     * @updated vithya
     *
     * @param - integer static_page_id
     *
     * @return view page 
     */

    public function static_pages_status_change(Request $request) {

        try {

            DB::beginTransaction();

            $static_page_details = StaticPage::find($request->static_page_id);

            if(!$static_page_details) {

                throw new Exception(tr('static_page_not_found'), 101);
                
            }

            $static_page_details->status = $static_page_details->status == DECLINED ? APPROVED : DECLINED;

            $static_page_details->save();

            DB::commit();

            $message = $static_page_details->status == DECLINED ? tr('static_page_decline_success') : tr('static_page_approve_success');

            return redirect()->back()->with('flash_success', $message);

        } catch(Exception $e) {

            DB::rollback();

            $error = $e->getMessage();

            return redirect()->back()->with('flash_error', $error);

        }

    }

    /**
     * @method: admin_control()
     *
     * @uses To update(enable/disable) admin control details in settings 
     *     
     * @created Anjana H
     *
     * @updated Anjana H
     *
     * @param settings key value
     *
     * @return viwe page.
     */
    public function admin_control() {

        if (Auth::guard('admin')) {
           
            return view('admin.settings.control')->with('page', tr('admin_control'));

        } else {

            return back();
        }
        
    }

}