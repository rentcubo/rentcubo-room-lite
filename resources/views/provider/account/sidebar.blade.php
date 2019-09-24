<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
	<ul class="account-list">
		
		<li id="account-update_profile">
			<a href="{{route('provider.update_profile')}}">
			{{tr('edit_profile')}}</a>
		</li>

		<li>
			<a href="{{route('provider.hosts.index', ['provider_id' => Auth::guard('provider')->user()->id])}}">
				{{tr('host_management')}}
			</a>
		</li>

		<li>
			<a href="{{route('provider.bookings.index', ['provider_id' => Auth::guard('provider')->user()->id])}}">
				{{tr('booking_management')}}
			</a>
		</li>

		<li id="account-change_password">
			<a href="{{route('provider.change_password')}}">{{tr('change_password')}}</a>
		</li>

		<li id="account-delete_account">
			<a href="{{route('provider.provider_account')}}">
			{{tr('delete_account')}}</a>
		</li>

		<li>
			<a href="{{route('provider.logout')}}">
			{{tr('logout')}}</a>
		</li>

	</ul>
	<a href="{{route('provider.profile')}}" class="grey-outline-btn w-100 bottom btn-small">{{tr('view_profile')}}</a>
</div>