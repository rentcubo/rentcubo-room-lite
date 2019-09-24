@extends('layouts.user') 

@section('title', tr('index'))

@section('styles')

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/css/star-rating-svg.css')}}">

@endsection

@section('content') 
<div class="container">
	<div class="section-title">
		<a href="{{route('user.bookings.index', ['user_id' => Auth::guard('user')->user()->id])}}" class="green-outline-btn btn-small float-right">{{tr('my_bookings')}}</a>
		<h1 class="section-head">{{tr('available_hosts')}}</h1>
		<h4 class="captalize section-subhead">{{tr('host_message')}}</h4>
	</div>
	<div class="row">
		<!-- home1 -->
		@foreach($hosts as $h => $host_details)
		<div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 top">
			<div class="relative">
			<section class="home-slider slider ">
    			<div>
					<div class="homes-img-sec1">
						<img src="{{ $host_details->picture ?: asset('placeholder.jpg') }}" alt="image"  class="homes-img">
					</div>
				</div>
			</section>
			<a href="#">
				<div class="wishlist-icon-sec">
					<div class="wishlist-icon"><a href="#"><i class="far fa-heart"></i></a></div>
				</div>
			</a>
			</div>
			<a href="{{route('user.hosts.view', ['host_id' => $host_details->id])}}">
				<div class="homes-text-sec">
					<p class="red-text txt-overflow">{{ $host_details->host_name}}</p>
					<h4 class="homes-title txt-overflow">{{ $host_details->full_address}}</h4>
					<h5 class="homes-price txt-overflow">
						<span>{{ formatted_amount($host_details->base_price)}}</span> <span class="dot"><i class="fas fa-circle"></i></span> <span>Free cancellation</span>
					</h5>
					<div class="my-rating-{{$h}}"></div>
				</div>
			</a>
		</div>
		@endforeach
	</div>
</div>

@endsection


@section('scripts')

    <script type="text/javascript" src="{{asset('admin-assets/js/jquery.star-rating-svg.min.js')}}"> </script>


   <script>
        <?php foreach ($hosts as $i => $host_details) { ?>
            $(".my-rating-{{$i}}").starRating({
                starSize: 20,
                initialRating: "{{$host_details->ratings}}",
                readOnly: true,
                callback: function(currentRating, $el){
                    // make a server call here
                }
            });
        <?php } ?>
    </script>

@endsection
