<!-- header section -->
<nav class="navbar navbar-expand-xl bg-light navbar-light white-header">

  	<a class="navbar-brand" href="{{url('/')}}">
  		<img src="{{Setting::get('site_icon', asset('favicon.png'))}}" class="profile-img" alt="image"/>
  	</a>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
		<i class="fas fa-chevron-down"></i>
	</button>

  	<div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
	    
	    <ul class="navbar-nav">

	    	@if(Auth::guard('user')->check())

		      	<li class="nav-item">
		        	<a class="nav-link"  href="{{route('user.bookings.index', ['user_id' => Auth::guard('user')->user()->id])}}">{{tr('my_bookings')}}</a>
		      	</li> 

		      	<li class="nav-item dropdown">
		        	<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">{{tr('saved')}}</a>
		      	</li>

		      	<li class="nav-item">
		        	<a class="nav-link"  href="{{route('user.hosts.index')}}">{{tr('available_hosts')}}</a>
		      	</li>

		      	<li class="nav-item dropdown">
		        	<a class="nav-link1 dropdown-toggle" id="navbardrop" data-toggle="dropdown">
		        		<img src="{{ Auth::guard('user')->user()->picture ?: asset('placeholder.jpg') }}" class="profile-img" alt="image"/>
		        	</a>
		        	<div class="dropdown-menu profile-drop">
		        		<a href="{{route('user.profile')}}" class="item">
			        		<div class="msg-head">
			        			<h5>{{tr('profile')}}</h5>
			        		</div>
				        	<p class="msg-line"></p>
			        	</a>
			        	<a href="{{route('user.change_password')}}" class="item">
			        		<div class="msg-head">
			        			<h5>{{tr('change_password')}}</h5>
			        		</div>
				        	<p class="msg-line"></p>
			        	</a>
			        	<a href="{{route('user.logout')}}" class="item">
			        		<div class="msg-head">
			        			<h5>{{tr('logout')}}</h5>
			        		</div>
				        	<p class="msg-line"></p>
			        	</a>
				   	</div>
		      	
		      	</li>

	      	@else

	      	<li class="nav-item">
	        	<a class="nav-link"  href="{{route('user.hosts.index')}}">{{tr('become_a_host')}}</a>
	      	</li>


	      	@endif
	    </ul>
  	</div>  
</nav>
<!-- header-section -->