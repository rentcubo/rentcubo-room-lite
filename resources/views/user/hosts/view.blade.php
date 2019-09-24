@extends('layouts.user') 

@section('title', tr('index'))

@section('content') 

@section('styles')

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/css/star-rating-svg.css')}}">

@endsection
  
<!-- banner-section -->

<div class="singlehome-img-sec">

	<img src="{{ $host->picture ?: asset('placeholder.jpg') }}" alt="{{$host->host_name}}"  class="homes-img br-0">

	<div class="top-right">
		<!-- <a href="#" class="white-btn btn-small m-2"><i class="fas fa-share-square"></i>&nbsp; share</a> -->
		<!-- <a href="#" class="white-btn btn-small m-2"><i class="far fa-heart"></i>&nbsp; save</a> -->
	</div>
</div>
<!-- banner-section -->		
<!-- body section -->
<div class="main">
	<div class="site-content">
		<div class="top-bottom-spacing">
			<!-- section1-->
			<div class="row">
				<div class="col-xl-7 col-lg-10 col-md-10 auto-margin">
					<!-- overview -->
					<div id="overview">
						<!-- intro -->
						<div class="media">

							<div class="media-body mr-3">
			 					<h1 class="category-section-head">{{$host->host_name}}</h1>
			 					<h4 class="captalize section-subhead">{{$host->full_address}}</h4>   
							</div>

							<div>
								<img src="{{ $host->provider_image ?: asset('placeholder.jpg') }}" alt="$host->provider_name" class="mt-3 rounded-circle review-img">

								<p class="text-center top3 mb-0">{{$host->provider_name}}</p>
							</div>

						</div>

						<ul class="home-requirements">
							<li class="">
								<i class="fas fa-users"></i>{{$host->total_guests}} {{tr('guests')}}
							</li>
						</ul>
				
						<!-- highlights -->
						<div class="highlights-box">
							<div>
								<h4 class="highlights-text">
									<span class="medium-cls">{{tr('host_type')}}</span>
									{{$host->host_type}}
								</h4>
							</div>
							<div>
								<h4 class="highlights-text">
									<span class="medium-cls">{{tr('category')}}</span>
									{{$host->category_name}}
								</h4>
							</div>
							<div>
								<h4 class="highlights-text">
									<span class="medium-cls">{{tr('sub_category')}}</span>
									{{$host->sub_category_name}}
								</h4>
							</div>
						</div>

					  	<!-- accessiblity -->

					  	<p class="overview-line"></p>
					  	
					  	<h4 class="single-cat-text medium-cls">{{tr('description')}}</h4>
					  	
					  	<h4 class="captalize rules-text"><?php echo $host->description; ?></h4>
					  	
					  	<div class="clearfix"></div>

					  	<h4 class="captalize rules-text">{{common_date($host->updated_at)}}</h4>


					</div>
					<!-- overview -->

					<!-- reviews -->
					<div id="reviews">
						<!-- reviews head -->
						<div class="row">
							<div class="col-sm-12 col-md-7 col-lg-7 col-xl-7">
								<h1 class="section-head">{{$host->total_ratings}} {{tr('reviews')}}
									<div class="my-rating"></div>
								</h1>
							</div>
						</div>
						<!-- <a href="{{ route('user.hosts.index') }}" class="float-right"><h4 class="collapse-head">{{tr('available_hosts')}}</h4></a> -->
						
					</div>
					<!-- reviews -->
				</div>

				<div class="col-xl-5 pl-5 relative dis-lg-none dis-md-none dis-sm-none dis-xs-none">
					<div class="pricedetails-box">

						<h3 class="home-price-details">

							{{formatted_amount($host->base_price)}}

							<small>{{tr('list_per_day_symbol')}}</small>

						</h3>
						
						<p class="overview-line1"></p>

						@include('notifications.notify')

						<form action="{{ route('user.bookings.save') }}" method="POST">

							@csrf

							<input type="hidden" name="host_id" id="host_id" value="{{$host->id}}">

							<input type="hidden" name="provider_id" id="provider_id" value="{{$host->provider_id}}">

							<input type="hidden" name="id" id="id" value="{{Auth::user()->id}}">

						  	<div class="form-group">

							    <label class="medium-cls">dates</label>

							    <div class="input-group">

							    	<input class="form-control" type="date" id="checkin" placeholder="checkin" name="checkin" required value="{{old('checkin') ?: $host->checkin}}">

							    	<input class="form-control" type="date" id="checkout" placeholder="checkout" name="checkout" required value="{{old('checkout') ?: $host->checkout}}">

							    </div>
						  	</div>

						  	<div class="form-group">

							    <label class="medium-cls">guests</label>

							    <input class="form-control" type="number" name="total_guests" id="total_guests" required max="{{$host->total_guests}}" min="1" value="{{old('total_guests') ?: 1}}">

						  	</div>

						  	<button type="submit" class="pink-btn btn-block book-btn" >{{ tr('book_host')}}</button>

						</form>

						<h5 class="small-text">you won’t be charged yet</h5>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

    <script type="text/javascript" src="{{asset('admin-assets/js/jquery.star-rating-svg.min.js')}}"> </script>


   	<script>
        
        $(".my-rating").starRating({
            starSize: 20,
            initialRating: "{{$host->overall_ratings}}",
            readOnly: true,
            callback: function(currentRating, $el){
                // make a server call here
            }
        });
    </script>

@endsection