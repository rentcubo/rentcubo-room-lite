@extends('layouts.provider')

@section('content')

<div class="page-content">

	<div class="prov-login">   

		<h3 class="text-center">{{Setting::get('site_name', 'RentCubo')}}</h3>   

        <form class="top1 prov-login-form" action="{{ route('provider.login.post') }}" method="POST">

        	@csrf

            <div class="form-group">

                <input type="email" name="email"  class="form-control" placeholder="{{ tr('email') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @if ($errors->has('email'))
		            <div class="text-danger">
		                <strong>{{ $errors->first('email') }}</strong>
		            </div>
		        @endif

            </div>

            <div class="form-group">

                <input type="password" minlength="6" name="password" class="form-control" placeholder="{{ tr('password') }}" required autocomplete="current-password">

                @if ($errors->has('password'))
		            <div class="invalid-feedback">
		                <strong>{{ $errors->first('password') }}</strong>
		            </div>
		        @endif
                
            </div>

            <button class="pink-btn bottom1 block cmn-btn" type="submit">{{tr('login')}}</button>

            <a href="{{route('provider.forgot_password')}}" class="forgot-pass close-login">{{tr('forgot_password')}}</a>

        </form>

        <div class="login-separator">or</div>
        
        <h4 class="m-0 text-center captalize">{{tr('dont_have_an_account')}}

        	<a href="{{route('provider.signup')}}" class="bold-cls close-login"> {{tr('signup')}}</a>
        </h4>

    </div>
</div>

@endsection
