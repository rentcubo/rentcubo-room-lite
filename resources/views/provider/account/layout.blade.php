@extends('layouts.provider')

@section('content')

<div class="main">

	<div class="site-content">

		<div class="top-bottom-spacing">
			<!-- edit profile -->
			<div class="row">

				@include('provider.account.sidebar')

				<!-- account content section -->

				@yield('account-content')

			</div>

		</div>

	</div>

</div>

@endsection