@extends('layouts.admin.focused')

@section('title', tr('login'))

@section('content')
	
	<div class="container-scroller">
	    <div class="container-fluid page-body-wrapper">
	        <div class="row">
	            <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-full-bg">
	                <div class="row w-100">
	                    <div class="col-lg-6 mx-auto">
	                        <div class="auth-form-dark text-left p-5">
	                            
	                            <h2>{{tr('signup')}}</h2>

	                            @include('notifications.notify')

	                            <form class="login-form" method="post" action="{{route('user.signup_save')}}">
	                            	@csrf
									<div class="form-group">
				     					<label for="email">{{tr('name')}} *</label>
								     	<div class="row">
								     		<div class="col-xs-6 ol-sm-6 col-md-6 col-12">
									      		<input type="text" class="form-control" id="fname" title="{{tr('username_title')}}" placeholder="{{tr('first_name')}}" name="first_name" required value="{{old('first_name')}}">
									      	</div>
									      	<div class="col-xs-6 col-sm-6 col-md-6 col-12">
									      		<input type="text" class="form-control" id="lname" title="{{tr('username_title')}}" placeholder="{{tr('last_name')}}" name="last_name" required value="{{old('last_name')}}">
									      	</div>
								      	</div>
				    				</div>
								    <div class="form-group">
								    	<div class="row">
								     		<div class="col-xs-6 ol-sm-6 col-md-6 col-12">
								     			<label for="email">{{tr('email')}} * </label>
									      		<input type="email" class="form-control" id="email" placeholder="{{tr('email')}}" name="email" required value="{{old('email')}}">
									      	</div>
									      	<div class="col-xs-6 col-sm-6 col-md-6 col-12">
									      		<label for="mobile">{{tr('mobile')}} * </label>
									      		<input type="text" class="form-control" id="mobile" title="{{tr('mobile_title')}}" pattern="[0-9]{4,16}" placeholder="{{tr('mobile')}}" name="mobile" required value="{{old('mobile')}}">
									      	</div>
								      	</div>
								    </div>

								    <div class="form-group">
								    	<div class="row">
								     		<div class="col-xs-6 ol-sm-6 col-md-6 col-12">
								     			<label for="pwd">{{tr('password')}} * </label>

									      		<input type="password" class="form-control" id="pwd" placeholder="{{tr('password')}} *" name="password" required value="{{old('password')}}" minlength="6" maxlength="64" title="{{tr('password_min_char')}}">

									      	</div>
									      	<div class="col-xs-6 col-sm-6 col-md-6 col-12">
									      		<label for="pwd">{{tr('confirm_password')}} * </label>
									      		<input type="password" class="form-control" id="pwd" placeholder="{{tr('confirm_password')}}" name="password_confirmation" required minlength="6" maxlength="64">
									      	</div>
								      	</div>	
								    </div>

								    <input type="hidden" name="timezone" value="" id="userTimezone">
                              		
	                                <div class="mt-5">	                
	                                    <button type="submit" class="btn btn-block btn-warning btn-lg font-weight-medium" >{{ tr('create_account')}}</button>
	                                </div>
	               					<hr>
	                				<h4 class="m-0 text-center captalize">Already have an account? <a href="{{route('user.login')}}" class="bold-cls close-login" style="color: orange"> Login</a></h4>

	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!-- content-wrapper ends -->
	        </div>
	        <!-- row ends -->
	    </div>
	    <!-- page-body-wrapper ends -->
	</div>

@endsection
