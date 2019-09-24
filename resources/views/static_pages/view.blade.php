@extends('layouts.user')

@section('content')

<div class="main">
	<div class="site-content">
		<div class="top-bottom-spacing">
			<!-- edit profile -->
			<div class="row">
				<div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-2">
					<ul class="account-list">
						<li><a href="{{route('user.static_pages', ['page_type' => 'terms'])}}">{{tr('terms')}}</a></li>
						<li><a href="{{route('user.static_pages', ['page_type' => 'privacy'])}}">{{tr('privacy')}}</a></li>
						<li><a href="{{route('user.static_pages', ['page_type' => 'help'])}}">{{tr('help')}}</a></li>
					</ul>
				</div>
				<div class="col-12 col-sm-12 col-md-8 col-lg-9 col-xl-10">
					
					<!-- terms and condition -->
					<div class="terms-head mt-0">
						<h1 class="">{{Setting::get('site_name')}} - {{$page_details ? $page_details->title : Setting::get('site_name')}}</h1>
					</div>
					<div class="terms-bold-text">
						<p class="captalize">Last Updated: {{common_date($page_details->created_at)}} </p>
					</div>

					<div class="terms-bold-text">
						@if($page_details)

						<?php echo $page_details->description; ?>

						@else 

						<p>SORRY..!!!. The requested page not found</p>

						@endif
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

@endsection