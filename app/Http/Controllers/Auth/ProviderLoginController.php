<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use App\Provider;

use App\Helpers\Helper;

class ProviderLoginController extends Controller
{
    
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:provider', ['except' => ['logout']]);
    }

    protected function guard() {

        return Auth::guard('provider');

    }

    /**
     * Show the application’s login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm() {

        if(Auth::guard('provider')->check()) {
            
            // return redirect()->route('provider.profile');
        }

        return view('provider.auth.login');

    }
    
    public function login(Request $request) {

        // Validate the form data
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
         ]);
      
        // Attempt to log the user in
        if (Auth::guard('provider')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {

            $provider_details = Provider::where('email', $request->email)->first();

            if($provider_details->status == PROVIDER_APPROVED) {

                Auth::guard('provider')->loginUsingId($provider_details->id);

                // if successful, then redirect to their intended location
                return redirect()->route('provider.profile')->with('flash_success', tr('login_success'));

            } else {

                Auth::guard('provider')->logout();
                
                return redirect()->back()->with('flash_success', Helper::error_message(1000));

            }
        } 

        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('email', 'remember'));
    
    }
    
    public function logout() {

        Auth::guard('provider')->logout();
        
        return redirect()->route('provider.login');
    }

}