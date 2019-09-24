<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
	<ul class="account-list">
		<li id="account-update_profile">
			<a href="{{route('user.update_profile')}}">
			{{tr('edit_profile')}}</a>
		</li>

		<li id="account-bookings">
			<a href="{{route('user.bookings.index', ['user_id' => Auth::user()->id])}}">
				{{tr('my_bookings')}}
			</a>
		</li>
		
		<li id="account-change_password">
			<a href="{{route('user.change_password')}}">{{tr('change_password')}}</a>
		</li>
		
		<li id="account-delete_account">
			<a href="{{route('user.delete_account')}}">
			{{tr('delete_account')}}</a>
		</li>

		<li>
			<a href="{{route('logout')}}">
			{{tr('logout')}}</a>
		</li>
	</ul>
	<a href="{{route('user.profile')}}" class="grey-outline-btn w-100 bottom btn-small">{{tr('view_profile')}}</a>
</div>