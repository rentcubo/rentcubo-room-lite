@extends('layouts.provider')

@section('content')

<div class="page-content">

    <div class="prov-login">   

        <h3 class="text-center">{{Setting::get('site_name', 'RentCubo')}}</h3>   

        <form class="top1 prov-login-form" action="{{route('provider.signup')}}" method="POST">
            
            @csrf

            <input type="hidden" name="login_by" value="manual">

            <input type="hidden" name="device_type" value="web">

            <div class="row">

            	<div class="col-md-6">

		            <div class="form-group inline-group">

		                <input type="text" name="first_name"  class="form-control" placeholder="{{ tr('first_name') }} *" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

		                @if ($errors->has('first_name'))
		                    <div class="text-danger">
		                        <strong>{{ $errors->first('first_name') }}</strong>
		                    </div>
		                @endif

		            </div>

		        </div>

            	<div class="col-md-6">

		            <div class="form-group inline-group">

		                <input type="text" name="last_name"  class="form-control" placeholder="{{ tr('last_name') }} *" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

		                @if ($errors->has('last_name'))
		                    <div class="text-danger">
		                        <strong>{{ $errors->first('last_name') }}</strong>
		                    </div>
		                @endif

		            </div>
		        </div>

			</div>
            <div class="form-group">

                <input type="email" name="email"  class="form-control" placeholder="{{ tr('email') }} *" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @if ($errors->has('email'))
                    <div class="text-danger">
                        <strong>{{ $errors->first('email') }}</strong>
                    </div>
                @endif

            </div>

            <div class="form-group inline-group">

                <input type="text" name="mobile" class="form-control" placeholder="{{ tr('mobile') }}*" value="{{ old('mobile') }}" required autocomplete="mobile" autofocus>

                @if ($errors->has('mobile'))
                    <div class="text-danger">
                        <strong>{{ $errors->first('mobile') }}</strong>
                    </div>
                @endif

            </div>

            <div class="form-group">

                <input type="password" minlength="6" name="password" class="form-control" placeholder="{{ tr('password') }} *" required autocomplete="current-password">

                @if ($errors->has('password'))
                    <div class="text-danger">
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>
                @endif
                
            </div>

            <div class="form-group">

                <input type="password" minlength="6" name="password_confirmation" class="form-control" placeholder="{{ tr('confirm_password') }} *" required>

                @if ($errors->has('password_confirmation'))
                    <div class="text-danger">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </div>
                @endif
                
            </div>

            <button class="pink-btn bottom1 block cmn-btn" type="submit">{{tr('signup')}}</button>

            <a href="{{route('provider.forgot_password')}}" class="forgot-pass close-login">{{tr('forgot_password')}}</a>

        </form>

        <div class="login-separator">or</div>
        
        <h4 class="m-0 text-center captalize">{{tr('already_have_an_account')}}
            <a href="{{route('provider.login')}}" class="bold-cls close-login"> {{tr('login')}}</a>
        </h4>

    </div>

</div>

@endsection

