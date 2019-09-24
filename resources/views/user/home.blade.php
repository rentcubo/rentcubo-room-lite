@extends('layouts.user')

@section('content')

<div class="main">
	
	<div class="section-spacing">
		
		<div class="top-bottom-spacing">

			<!-- section1 - exlore section -->
	        <div>

	        	@if(count($categories) > 0 || count($sub_categories) > 0)
	            
	            	<h1 class="section-head">Explore {{Setting::get('site_name', 'RentCubo')}}</h1>

	            @endif

	            <section class="category slider">

	            	@if(count($categories) > 0)

		                @foreach($categories as $category_details)

			                <div>
			                    <a href="{{route('user.home', ['category_id' => $category_details->category_id])}}">
			                        
			                        <div class="display-inline home-explore-card">
			                            
			                            <div class="home-explore-left">
											<div class="home-explore-img" style='background-image: url({{$category_details->picture ?: asset("dummy.jpg")}})'></div>
			                            </div>

			                            <div class="home-explore-right">
			                                <p class="">
			                                	{{$category_details->category_user_display_name}}
			                                </p>
			                            </div>

			                        </div>

			                        <div class="clearfix"></div>
			                    </a>
			                </div>

		                @endforeach

	                @endif

	                @if(count($sub_categories) > 0)

		                @foreach($sub_categories as $sub_category_details)

			                <div>
			                    <a href="{{route('user.home', ['sub_category_id' => $sub_category_details->sub_category_id])}}">
			                        
			                        <div class="display-inline home-explore-card">
			                            
			                            <div class="home-explore-left">
											<div class="home-explore-img" style='background-image: url({{$sub_category_details->picture ?: asset("dummy.jpg")}})'></div>
			                            </div>

			                            <div class="home-explore-right">
			                                <p class="">
			                                	{{$sub_category_details->sub_category_user_display_name}}
			                                </p>
			                            </div>

			                        </div>

			                        <div class="clearfix"></div>
			                    </a>
			                </div>

		                @endforeach

	                @endif
	            
	            </section>
	        
	        </div>
	        <!-- section1 - explore section -->

			<!--section1 - homes section -->
			<div class="display-inline">
				<!-- left section -->
				<div class="subcategory-leftsec">
					
					<h1 class="section-head">{{tr('explore_hosts')}}</h1>
					
					<!-- hosts sections start-->

					<div class="row">

						@if(count($hosts) > 0)

							@foreach($hosts as $host_details)

								@include('user.common._hosts')

							@endforeach

							<div class="col-md-12">

								<ul class="home-pagination">

									{{ $hosts->links() }}

								</ul>

							</div>

						@else 

							<p class="text-center">No hosts found.</p>

						@endif

					</div>

					<!-- hosts sections end-->

				</div>

				<!-- left-section -->
			
			</div>
			<!-- section1 - homes section -->
		</div>
	</div>
</div>
<!-- body -->

@endsection

@section('styles')

@endsection

@section('scripts')

@endsection