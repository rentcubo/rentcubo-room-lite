<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use App\Helpers\Helper;

use Validator;

use Log;

use App\User;

use DB;

use Setting;

class UserApiValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info("USER API - USER ID".$request->id);


        $validator = Validator::make(
                $request->all(),
                array(
                        'token' => 'required|min:5',
                        'id' => 'required|integer|exists:users,id'
                ),[
                    'exists' => Helper::error_message(1002),
                    'id' => Helper::error_message(1005)
                ]);

        if ($validator->fails()) {

            $error = implode(',', $validator->messages()->all());

            $response = array('success' => false, 'error' => $error , 'error_code' => 1002);

            return response()->json($response,200);

        } else {

            $token = $request->token;

            $user_id = $request->id;

            if (!Helper::is_token_valid(USER, $user_id, $token, $error)) {

                $response = response()->json($error, 200);
                
                return $response;

            } else {

                $user_details = User::find($request->id);

                if(!$user_details) {
                    
                    $response = array('success' => false, 'error' => Helper::error_message(1002) , 'error_code' => 1002);

                    return response()->json($response,200);

                }

                if(in_array($user_details->status , [USER_DECLINED , USER_PENDING])) {
                    
                    $response = array('success' => false , 'error' => Helper::error_message(1000) , 'error_code' => 1000);

                    return response()->json($response, 200);
               
                }

                if($user_details->is_verified == USER_EMAIL_NOT_VERIFIED) {

                    if(Setting::get('is_account_email_verification') && !in_array($user_details->login_by, ['facebook' , 'google'])) {

                        // Check the verification code expiry

                        Helper::check_email_verification("" , $user_details, $error, USER);
                    
                        $response = array('success' => false , 'error' => Helper::error_message(1001) , 'error_code' => 1001);

                        return response()->json($response, 200);

                    }
                
                }
            }
       
        }

        return $next($request);
    }
}
