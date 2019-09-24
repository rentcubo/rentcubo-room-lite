@extends('layouts.admin') 

@section('title', tr('payments'))

@section('breadcrumb')

<li class="breadcrumb-item active" aria-current="page">
    <span>{{tr('view_booking')}}</span>
</li>

@endsection 

@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    
    <div class="card">

        <div class="card-header bg-card-header ">
            <h4 class="">{{tr('view_bookings')}} </h4>
        </div>

        <div class="card-body">

            <div class="row">

                <div class=" col-sm-6 table-responsive">
                    
                    <h6 class="card-title">{{ tr('details') }}</h6>

                    <table class="table table-bordered table-striped tab-content">
                       
                        <tbody>
	                        <tr>
	                        	<td>{{ tr('user')}} </td>
	                        	<td>
	                        		<a href="{{ route('admin.users.view', ['user_id' => $booking_payment_details->user_id])}}">
	                        		{{ $booking_payment_details->user_name}}
	                        		</a>
	                        	</td>
	                        </tr> 
	                        <tr>
	                        	<td>{{ tr('provider')}} </td>
	                        	<td>
	                        		<a href="{{ route('admin.providers.view',['provider_id' => $booking_payment_details->provider_id ])}}">
	                        			{{ $booking_payment_details->provider_name}}
	                        		</a>
	                        	</td>
	                        </tr> 
	                        <tr>
	                        	<td>{{ tr('host')}} </td>
	                        	<td>{{ $booking_payment_details->host_name}}</td>
	                        </tr>
	                        <tr>
	                        	<td>{{ tr('description')}} </td>
	                        	<td>{{ $booking_payment_details->host_description}}</td>
	                        </tr>
	                        <tr>
	                    		<td>{{ tr('payment_mode') }}</td>
	                    		<td>{{$booking_payment_details->payment_mode}}</td>
	                    	</tr>

						    <tr>
						        <td>{{ tr('paid_date') }}</td>
	                    		<td>{{$booking_payment_details->currency}} {{$booking_payment_details->paid_date}}</td>
						    </tr>

							<tr>
						        <td>{{ tr('status') }}</td>
	                    		<td>{{ $booking_payment_details->status}}</td>
						    </tr>
                        </tbody>

                    </table>

                </div>


                <div class=" col-sm-6 table-responsive">
                    
                    <h6 class="card-title">{{ tr('invoice') }}</h6>
                    
                    <table class="table table-bordered table-striped">
                      
	                    <tbody>
	                    	
	                       	<tr>
						        <td>{{ tr('actual_total') }}</td>
	                    		<td>{{$booking_payment_details->actual_total}}</td>
						    </tr>
			               
						    <tr>
						        <td>{{ tr('tax_price') }}</td>
	                    		<td>{{$booking_payment_details->currency}} {{$booking_payment_details->tax_price}}</td>
						    </tr> 

						    <tr>
			                	<td>{{ tr('sub_total')}}</td>
			                	<td> {{$booking_payment_details->currency}} {{ $booking_payment_details->sub_total }} </td>
			                </tr>

						    <tr>
						        <td>{{ tr('total') }}</td>
	                    		<td>{{$booking_payment_details->currency}} {{$booking_payment_details->total}}</td>
						    </tr> 

						     <tr>
						        <td>{{ tr('paid_amount') }}</td>
	                    		<td>{{$booking_payment_details->currency}} {{$booking_payment_details->paid_amount}}</td>
						    </tr>
						    <tr>
						        <td>{{ tr('admin_amount') }}</td>
	                    		<td>{{$booking_payment_details->currency}} {{$booking_payment_details->admin_amount}}</td>
						    </tr>

						    <tr>
						        <td>{{ tr('provider_amount') }}</td>
	                    		<td>{{$booking_payment_details->currency}} {{$booking_payment_details->provider_amount}}</td>
						    </tr>
	                           
	                    </tbody>
                   
                    </table>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection