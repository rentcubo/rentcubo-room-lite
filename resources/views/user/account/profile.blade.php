@extends('user.account.layout')

@section('account-content')

<div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-9">

	<div class="media">
		<div>
			<img src="{{ $user_details->picture ?: asset('placeholder.jpg') }}" class="user-pro-img" alt="image"/>
			<div class="panel top dis-xs-none dis-sm-none">  
				<div class="panel-heading">{{$user_details->name}}</div>
				<div class="panel-body p-3">
					<ul class="verified-list">
						<li style="text-transform: none;">{{tr('email')}} - {{$user_details->email}}<span class="theme-green-clr"><i class="far fa-check-circle float-right align-3"></i></span></li>
						<li>{{tr('mobile')}} - {{$user_details->mobile}}<span class="theme-green-clr"><i class="far fa-check-circle float-right"></i></span></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="media-body ml-4">
		    <h1 class="profile-head">Hey, I’m {{$user_details->name}}!</h1>
		    <a href="{{route('user.update_profile')}}" class="edit-link mt-3">{{tr('edit_profile')}}</a>

		    <div class="profile-content">
			    <h5 class="top lh-1-4">{{$user_details->description}}</h5>   
		    </div>
		</div>
	</div>
</div>

@endsection