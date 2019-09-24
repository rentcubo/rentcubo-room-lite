@extends('provider.account.layout')

@section('account-content')

	<div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-9">

		<div class="media">
			<div>
	  		<img src="{{ $provider_details->picture ?: asset('placeholder.jpg') }}" class="user-pro-img" alt="image"/>
	  		<div class="panel top dis-xs-none dis-sm-none">  
	  			<div class="panel-heading">{{$provider_details->name}}</div>
	  			<div class="panel-body p-3">
	  				<ul class="verified-list">
	  					<li style="text-transform: none;">{{tr('email')}} - {{$provider_details->email}}<span class="theme-green-clr"><i class="far fa-check-circle float-right align-3"></i></span></li>
	  					<li>{{tr('mobile')}} - {{$provider_details->mobile}}<span class="theme-green-clr"><i class="far fa-check-circle float-right"></i></span></li>
	  				</ul>
	  			</div>
	  		</div>
	  	</div>
	  	<div class="media-body ml-4">
		    <h1 class="profile-head">Hey, I’m {{$provider_details->name}}!</h1>
		    <a href="{{route('provider.update_profile')}}" class="edit-link mt-3">{{tr('edit_profile')}}</a>

		    <div class="profile-content">
			    <h5 class="top lh-1-4">{{$provider_details->description}}</h5>   
		    </div>
		</div>
	</div>
	
	</div>

@endsection