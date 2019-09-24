@extends('user.account.layout')

@section('title', tr('index'))

@section('breadcrumb')

    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{tr('index')}}</span>
    </li>
           
@endsection 

@section('account-content')

<div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-9">
	
	<form action="{{route('user.delete_account_update')}}" method="post" enctype="multipart/form-data" role="form">
		@csrf

		<input type="hidden" name="id" value="{{Auth::user()->id}}">

		<div class="panel">
			<div class="panel-heading">{{tr('delete_account')}}</div>
			<div class="panel-body account">

				<h2 class="mt-0 medium-cls bottom">{{tr('delete_account_message')}}</h2>
				<!-- old password -->
				<div class="form-group row">
					<div class="col-3 text-right">
			    	<label for="old-pass">{{tr('password')}}</label>
			    </div>
			    @if(Auth::user()->login_by == 'manual')
			  	<div class="col-9">
				    <input type="password" class="form-control" id="pwd" name="password" required minlength="6" title="{{tr('enter_minimum_characters')}}">
			  		<h5 class="profile-note">{{tr('delete_account_message_2')}}</h5>
			  	</div>
			  	@endif
			</div>
			
			<div class="row">
				<div class="col-9 offset-3">
				  	@if(Auth::user()->login_by == 'manual')
			    		<button type="reset" class="btn btn-default">{{tr('clear')}}</button>
			    	@endif
			    	<button type="submit" class="btn btn-primary" onclick="return confirm(&quot;{{tr('are_you_sure')}}&quot;);">{{tr('delete_account')}}</button>
			    </div>
			</div>
			
			</div>
		</div>
		
	</form>

</div>

@endsection