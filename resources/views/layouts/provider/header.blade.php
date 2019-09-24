<!-- header section -->
<nav class="navbar navbar-expand-xl bg-light navbar-light white-header">
  	<a class="navbar-brand" href="{{route('provider.profile')}}">
  		<img src="{{ Setting::get('site_icon') ?: asset('placeholder.jpg') }}" class="profile-img" alt="image"/ >
  	</a>
	
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
		<i class="fas fa-chevron-down"></i>
	</button>
  	<div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
	    <ul class="navbar-nav">

	    	@if(Auth::guard('provider')->check())
	      	
		        <p class="nav-link">{{Auth::guard('provider')->user()->name}}</p>
		      	
		      	<li class="nav-item dropdown">
		        	<a class="nav-link1 dropdown-toggle" id="navbardrop" data-toggle="dropdown">
		        		<img src="{{ Auth::guard('provider')->user()->picture ?: asset('placeholder.jpg') }}" class="profile-img" alt="image"/>
		        	</a>
		        	<div class="dropdown-menu profile-drop">
		        		<a href="{{route('provider.profile')}}" class="item">
			        		<div class="msg-head">
			        			<h5>{{tr('profile')}}</h5>
			        		</div>
				        	<p class="msg-line"></p>
			        	</a>
			        	<a href="{{route('provider.change_password')}}" class="item">
			        		<div class="msg-head">
			        			<h5>{{tr('change_password')}}</h5>
			        		</div>
				        	<p class="msg-line"></p>
			        	</a>
			        	<a href="{{route('provider.logout')}}" class="item">
			        		<div class="msg-head">
			        			<h5>{{tr('logout')}}</h5>
			        		</div>
				        	<p class="msg-line"></p>
			        	</a>
				   	</div>
		      	
		      	</li>

	      	@else 

	      		<li class="nav-item">
		        	<a class="nav-link" href="{{route('provider.signup')}}">
		        		{{tr('become_a_host')}}
		        	</a>
		      	</li> 

		      	<li class="nav-item">
		        	<a class="nav-link" href="{{route('register')}}">{{tr('signup')}}</a>
		      	</li>

		      	<li class="nav-item">
		        	<a class="nav-link" href="{{route('login')}}">{{tr('login')}}</a>
		      	</li>
	      	@endif
	    </ul>
  	</div>  
</nav>
<!-- header-section -->
