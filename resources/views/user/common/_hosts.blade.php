<div class="subcategory-card">

    <div class="relative">

        <section class="home-slider slider">

        	<div>
                <div class="homes-img-sec1">
                     <a href="{{route('user.hosts.view', ['host_id' => $host_details->host_id])}}" title="{{tr('view_host')}}"><img srcset="{{$host_details->host_picture}}" src="{{$host_details->host_picture}}" alt="image" class="homes-img"></a>
                </div>
            </div>

        	@foreach($host_details->gallery as $gallery_details)

	            <div>
	                <div class="homes-img-sec1">
	                    <img srcset="{{$gallery_details->picture}}" src="{{$gallery_details->picture}}" alt="image" class="homes-img">
	                </div>
	            </div>

            @endforeach

            
        </section>
        
    </div>

    <a href="{{route('user.hosts.view', ['host_id' => $host_details->host_id])}}" title="{{tr('view_host')}}">

        <div class="homes-text-sec">

            <p class="red-text txt-overflow">{{$host_details->host_type}}</p>

            <h4 class="homes-title txt-overflow">{{$host_details->host_name}}</h4>

            <h5 class="homes-price txt-overflow">
				<span>{{$host_details->base_price_formatted}} {{tr('list_per_day_symbol')}}</span></span>
			</h5>
            <p class="txt-overflow m-0">
                <!-- <span class="homes-ratings">
					<i class="fas fa-star"></i>
					<i class="fas fa-star"></i>
					<i class="fas fa-star"></i>
					<i class="fas fa-star"></i>
					<i class="fas fa-star"></i>
				</span> -->
                <span class="medium-cls">{{$host_details->total_ratings}} {{tr('reviews')}}</span>
            </p>
        </div>
    </a>
</div>