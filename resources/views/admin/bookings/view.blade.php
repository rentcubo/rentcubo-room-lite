@extends('layouts.admin') 

@section('title', tr('view_booking'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{route('admin.bookings.index')}}">{{tr('bookings')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        <span>{{tr('view_booking')}}</span>
    </li>
           
@endsection  

@section('content')
	
	<div class="row ">
		
		<div class="col-12">
          	
          	<div class="card grid-margin ">
          		
          		<div class="card-body">

          		  	<div class="d-flex justify-content-between align-items-center">
	          		    <div class="d-inline-block">
	          		      	<div class="d-lg-flex">
		          		        <h5 class="mb-2 text-uppercase">{{ tr('booking_id')}} : </h5>
		          		        <div class="d-flex align-items-center text-success ml-lg-2">
		          		          	<h5 class="ml-1 mb-2"><b>#{{$booking_details->unique_id }}</b></h5>
		          		        </div>
	          		      	</div>
	          		        <small class="ml-1 mb-0">

	          		          	<i class="mdi mdi-clock text-muted"></i>

		          		        {{ tr('checkin')}}: <span class="text-muted">{{ common_date($booking_details->checkin)}}</span>
	          		    	</small>

	          		        <small class="ml-1 mb-0">
	          		          	<i class="mdi mdi-clock text-muted"></i>

	          		          	{{ tr('checkout')}}: <span class="text-muted">{{ common_date($booking_details->checkout)}}</span>

	          		        </small>

	          		    </div>

	          		    <div class="d-inline-block">
	          		      	<div class="px-3 px-md-4 py-2 rounded">
		          		        <small class="ml-1 mb-0 text-info"> 
		          		        	<b>{{ booking_status( $booking_details->status) }}</b>
		          		        </small>
	          		      	</div>
	          		    </div>

          		  	</div>

          		</div>

          	</div>

        </div>

	</div>

    <div class="row">
        
        <div class="col-12 grid-margin">
          	
          	<div class="card">
	            
	            <div class="card-body">
	              	
	              	<div class="row">

		                <div class="col-md-4 col-sm-6 d-flex justify-content-center border-right">
		                 	<div class="wrapper text-center">
		                    	<h4 class="card-title">{{ tr('user') }}</h4>
		                        <img src="{{ $booking_details->user_picture}}" alt="image" class="img-lg rounded-circle mb-2"/>
		 		                <h4>{{ $booking_details->user_name }}</h4>

		 		                <a href="{{ route('admin.users.view', ['user_id' => $booking_details->user_id ]) }}" class="btn btn-outline-success" >{{ tr('view')}}</a>

		 		        	</div>

		                </div>

		                <div class="col-md-4 col-sm-6 d-flex justify-content-center border-right">
		                  <div class="wrapper text-center">
		                    <h4 class="card-title">{{ tr('host')}}</h4>
		                    <img src="{{ $booking_details->host_picture}}" alt="image" class="img-lg rounded-circle mb-2"/>

		                    <p class="card-description">{{ $booking_details->host_name }}</p>
		                    <a href="{{ route('admin.hosts.view', ['host_id' => $booking_details->host_id ]) }}" class="btn btn-outline-success">{{ tr('view')}}</a>

		                  </div>
		                </div>

		                <div class="col-md-4 col-sm-6 d-flex justify-content-center">
		                  <div class="wrapper text-center">
		                    <h4 class="card-title">{{ tr('provider')}}</h4>
		                    <img src="{{ $booking_details->provider_picture}}" alt="image" class="img-lg rounded-circle mb-2"/>

		                    <p class="card-description">{{ $booking_details->provider_name }}</p>
		                    <a href="{{ route('admin.providers.view', ['provider_id' => $booking_details->provider_id ]) }}" class="btn btn-outline-success">{{ tr('view')}}</a>

		                  </div>
		                </div>

	              	</div>

	            </div>

          	</div>

        </div>

    </div>


	<div class="row">


		<div class="col-md-4 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="preview-list">
						<div class="preview-item border-bottom px-0">	
							<div class="preview-item-content d-flex flex-grow">
								<div class="flex-grow">
									<h6 class="preview-subject">{{tr('total_days')}}
										<span class="float-right small">
											<span class="text-muted pr-3">{{ $booking_details->total_days }}</span>
										</span>
									</h6>
									<!-- <p>Thanks for the support!</p> -->
								</div>
							</div>
						</div>

						<div class="preview-item border-bottom px-0">	
							<div class="preview-item-content d-flex flex-grow">
								<div class="flex-grow">
									<h6 class="preview-subject">{{tr('total_guests')}}
										<span class="float-right small">
											<span class="text-muted pr-3">{{ $booking_details->total_guests }}</span>
										</span>
									</h6>
									<!-- <p>Thanks for the support!</p> -->
								</div>
							</div>
						</div>

						<div class="preview-item border-bottom px-0">
							
							<div class="preview-item-content d-flex flex-grow">
								<div class="flex-grow">
									<h6 class="preview-subject">{{tr('updated_at')}}
										<span class="float-right small">
											<span class="text-muted pr-3">{{ common_date($booking_details->updated_at) }}</span>
										</span>
									</h6>
									<!-- <p>Hope to see you tomorrow</p> -->
								</div>
							</div>
						</div>	

						<div class="preview-item border-bottom px-0">
							
							<div class="preview-item-content d-flex flex-grow">
								<div class="flex-grow">
									<h6 class="preview-subject">{{tr('created_at')}}
										<span class="float-right small">
											<span class="text-muted pr-3">{{ common_date($booking_details->created_at) }}</span>
										</span>
									</h6>
									<!-- <p>Hope to see you tomorrow</p> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-8 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="preview-list">
	

						<div class="preview-item border-bottom px-0">	
							<div class="preview-item-content d-flex flex-grow">
								<div class="flex-grow">
									<h6 class="preview-subject">{{tr('payment_id')}}
										<span class="float-right small">
											<span class="text-muted pr-3 badge badge-outline-primary">
												{{ $booking_details->payments_details->payment_id}}
											</span>
										</span>
									</h6>
									<!-- <p>Thanks for the support!</p> -->
								</div>
							</div>
						</div>

						<div class="preview-item border-bottom px-0">	
							<div class="preview-item-content d-flex flex-grow">
								<div class="flex-grow">
									<h6 class="preview-subject">{{tr('per_day')}}
										<span class="float-right small">
											<span class="text-muted pr-3">{{ formatted_amount($booking_details->per_day) }}</span>
										</span>
									</h6>
									<!-- <p>Thanks for the support!</p> -->
								</div>
							</div>
						</div>

						<div class="preview-item border-bottom px-0">
							
							<div class="preview-item-content d-flex flex-grow">
								<div class="flex-grow">
									<h6 class="preview-subject">{{tr('total')}} ({{ $booking_details->total_days }} Nights * {{formatted_amount($booking_details->per_day)}})
										<span class="float-right small">
											<span class="text-muted pr-3">{{ formatted_amount($booking_details->total) }}</span>
										</span>
									</h6>
									<!-- <p>Meeting is postponed</p> -->
								</div>
							</div>
						</div>

						<div class="preview-item border-bottom px-0">
							
							<div class="preview-item-content d-flex flex-grow">
								<div class="flex-grow">
									<h6 class="preview-subject">{{tr('payment_mode')}}
										<span class="float-right small">
											<span class="text-muted pr-3">{{ $booking_details->payment_mode }}</span>
										</span>
									</h6>
									<!-- <p>Please approve the request</p> -->
								</div>
							</div>
						</div>

						
					</div>
				</div>
			</div>
		</div>

	</div>

@endsection