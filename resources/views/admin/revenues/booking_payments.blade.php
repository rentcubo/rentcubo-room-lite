@extends('layouts.admin') 

@section('title', tr('bookings_payments'))

@section('breadcrumb')

    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{tr('bookings_payments')}}</span>
    </li>
           
@endsection 

@section('content') 

	<div class="col-lg-12 grid-margin">
        
        <div class="card">

            <div class="card-header bg-card-header ">

                <h4 class="">{{tr('bookings_payments')}}</h4>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                	<table id="order-listing" class="table">
                        
                        <thead>

                            <tr>
								<th>{{tr('s_no')}}</th>
								<th>{{tr('booking')}}</th>
                                <th>{{tr('user')}}</th>
								<th>{{tr('provider')}}</th>
                                <th>{{tr('host')}}</th>
								<th>{{tr('pay_via')}}</th>
								<th>{{tr('total')}}</th>
								<th>{{tr('status')}}</th>
                            </tr>

                        </thead>
                        
                       
                        <tbody>

                            @if(count($booking_payments) > 0 )
                            
                                @foreach($booking_payments as $i => $booking_payment_details)

                                    <tr>
                                        <td>{{ $i+1 }}</td>

                                        <td>
                                            <a href="{{route('admin.bookings.view', ['booking_id' => $booking_payment_details->booking_id])}}">#{{$booking_payment_details->booking_unique_id}}
                                            </a>
                                        </td>

                                        <td>   

                                        @if(empty($booking_payment_details->user_name))

                                            {{ tr('user_not_avail') }}
                                        
                                        @else
                                            <a href="{{ route('admin.users.view',['user_id' => $booking_payment_details->user_id])}}">{{ $booking_payment_details->user_name }}</a>
                                        @endif
                                            
                                        </td>

                                        <td>
                                        
                                            <a href="{{ route('admin.providers.view',['provider_id' => $booking_payment_details->provider_id])}}">{{ $booking_payment_details->provider_name }}</a>

                                        </td>

                                        <td>
                                            
                                            <a href="{{ route('admin.hosts.view',['host_id' => $booking_payment_details->host_id])}}">{{ $booking_payment_details->host_name }}</a>
                                        </td>
                                        
                                        <td> 
                                            <span class="text-uppercase">{{ $booking_payment_details->payment_mode }}</span>
                                        </td>

                                        <td>
                                            {{formatted_amount($booking_payment_details->total)}}
                                        </td>

                                        <td>
                                            @if($booking_payment_details->status)

                                                <div class="badge badge-success badge-fw">{{ tr('paid')}}</div>
                                            @else
                                          
                                                <div class="badge badge-warning badge-fw">{{ tr('pending')}}</div>

                                            @endif
                                        </td>
                                    </tr>

                                @endforeach

                            @else

                                <tr>
                                    <td>{{  tr('no_result_found') }}</td>
                                </tr>

                            @endif

                        </tbody>

                    </table>
                </div>

            </div>

        </div>

    </div>	

    
@endsection