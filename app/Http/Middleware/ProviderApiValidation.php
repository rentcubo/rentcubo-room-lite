<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use App\Helpers\Helper;

use Validator;

use Log;

use App\Provider;

use DB;

use Setting;

class ProviderApiValidation
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
        Log::info("Provider API - Provider ID".$request->id);

        $validator = Validator::make(
                $request->all(),
                array(
                        'token' => 'required|min:5',
                        'id' => 'required|integer|exists:providers,id'
                ),[
                    'exists' => Helper::error_message(1006),
                    'id' => Helper::error_message(1005)
                ]);

        if ($validator->fails()) {

            $error = implode(',', $validator->messages()->all());

            $response = array('success' => false, 'error' => $error , 'error_code' => 1006 );

            return response()->json($response,200);

        } else {

            $token = $request->token;

            $provider_id = $request->id;

            if (!Helper::is_token_valid(PROVIDER, $provider_id, $token, $error)) {

                $response = response()->json($error, 200);
                
                return $response;

            } else {

                $provider_details = Provider::find($request->id);

                if(!$provider_details) {
                    
                    $response = array('success' => false, 'error' => Helper::error_message(1006) , 'error_code' => 1006 );

                    return response()->json($response,200);

                }

                if(in_array($provider_details->status , [PROVIDER_DECLINED , PROVIDER_PENDING])) {
                    
                    $response = array('success' => false , 'error' => Helper::error_message(1000) , 'error_code' => 1000);

                    return response()->json($response, 200);
               
                }

                if($provider_details->is_verified == PROVIDER_EMAIL_NOT_VERIFIED) {

                    if(Setting::get('is_account_email_verification') && !in_array($provider_details->login_by, ['facebook' , 'google'])) {

                        // Check the verification code expiry

                        Helper::check_email_verification("" , $provider_details, $error, PROVIDER);
                    
                        $response = array('success' => false , 'error' => Helper::error_message(1001) , 'error_code' => 1001);

                        return response()->json($response, 200);

                    }
                
                }
            }
       
        }

        return $next($request);
    }
}
