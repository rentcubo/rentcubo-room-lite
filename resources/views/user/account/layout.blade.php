@extends('layouts.user')

@section('content')

<div class="main">

	<div class="site-content">

		<div class="top-bottom-spacing">
			<!-- edit profile -->
			<div class="row">

				@include('user.account.sidebar')

				<!-- Account content section -->

				@yield('account-content')

			</div>

		</div>

	</div>

</div>

@endsection