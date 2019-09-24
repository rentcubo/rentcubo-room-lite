@extends('provider.account.layout')

@section('account-content')

	<div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-9">
		<form action="{{route('provider.delete_account')}}" method="post" enctype="multipart/form-data" role="form">
			@csrf

			<input type="hidden" name="id" value="{{Auth::guard('provider')->user()->id}}">

			<div class="panel">
				<div class="panel-heading">{{tr('delete_account')}}</div>
				<div class="panel-body account">

					<h2 class="mt-0 medium-cls bottom">{{tr('delete_account_message')}}</h2>
					<!-- old password -->
					<div class="form-group row">
						<div class="col-3 text-right">
				    	<label for="old-pass">{{tr('password')}}</label>
				    </div>
				    @if(Auth::guard('provider')->user()->login_by == 'manual')
				  	<div class="col-9">
					    <input type="password" class="form-control" id="pwd" name="password" required minlength="6" title="{{tr('password_notes')}}">
				  		<h5 class="profile-note">{{tr('delete_account_message_2')}}</h5>
				  	</div>
				  	@endif
				</div>
				
				<div class="row">
					<div class="col-9 offset-3">
					  	@if(Auth::guard('provider')->user()->login_by == 'manual')
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