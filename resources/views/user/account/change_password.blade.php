@extends('user.account.layout')

@section('title', tr('index'))

@section('breadcrumb')

    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{tr('index')}}</span>
    </li>
           
@endsection 

@section('account-content')

<div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-9">
	<form method="post" action="{{route('user.change_password_save')}}">
		@csrf
		<div class="panel">
			<div class="panel-heading">{{tr('change_password')}}</div>

			<input type="hidden" name="id" value="{{Auth::user()->id}}">
			
			<div class="panel-body account">
				<!-- old password -->
				<div class="form-group row">
					<div class="col-3 text-right">
			    	<label for="old-pass">{{tr('old_password')}}</label>
			    </div>
			    <div class="col-9">
			    	<input type="password" class="form-control" id="old-pwd" name="old_password" required>
			    </div>
			</div>
			<!-- new password -->
			<div class="form-group row">
					<div class="col-3 text-right">
			    	<label for="new-pass">{{tr('new_password')}}</label>
			    </div>
			    <div class="col-9">
			    	<input type="password" class="form-control" id="new-pwd" name="password" required>
			    </div>
			</div>
			<!-- confirm password -->
			<div class="form-group row">
					<div class="col-3 text-right">
			    	<label for="new-pass">{{tr('confirm_password')}}</label>
			    </div>
			    <div class="col-9">
			    	<input type="password" class="form-control" id="confirm-pwd" name="password_confirmation" required>
			    </div>
			</div>
			<?php $confirm_pwd_message = tr('confirm_pwd_message');?>
			<div class="row">
				<div class="col-9 offset-3">
						<button type="submit" class="btn btn-primary pull-right" onclick="return alert('{{$confirm_pwd_message}}')">
		  				{{tr('submit')}}
		  			</button>
					</div>
			</div>
			
			</div>
		</div>
		
	</form>
</div>

@endsection