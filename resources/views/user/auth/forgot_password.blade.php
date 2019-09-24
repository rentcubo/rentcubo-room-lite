@extends('layouts.user')

@section('content')

<div class="page-content">

	<div class="prov-login">   

		<h3 class="text-center">{{Setting::get('site_name', 'RentCubo')}}</h3>   

        <form class="login-form" action="{{$disable ? '' : route('user.send_mail')}}" method="post">
        	@csrf
		    
		    <div class="form-group">
		     	<label for="email">{{tr('email_address')}} *</label>
		      	<input type="email" name="email" class="form-control" id="email" placeholder="{{tr('email_address')}}" required>
		    </div>

		    <p class="grey-clr top1">{{tr('reset_password_notes')}}</p>

		    @if($disable) 

		     	<button class="pink-btn bottom1 block cmn-btn" type="button" disabled title="{{tr('email_not_configured')}}">{{tr('send_reset_link')}}</button>

		    @else
		     	<button class="pink-btn bottom1 block cmn-btn" type="submit">{{tr('send_reset_link')}}</button>
            
		    @endif
		    <hr>
		    <h4 class="m-0 text-center captalize">{{tr('dont_have_an_account')}}? <a href="{{route('register')}}" class="bold-cls close-login" style="color: orange"> {{tr('signup')}}</a></h4>
		
		</form>
 	</div>
</div>

@endsection