@extends('layouts.admin') 

@section('title', tr('bookings'))

@section('breadcrumb')
  
    <li class="breadcrumb-item active" aria-current="page">
        <span>{{ tr('bookings') }}</span>
    </li>
           
@endsection 

@section('content')

    <div class="col-lg-12 grid-margin stretch-card">
        
        <div class="card">

            <div class="card-header bg-card-header ">

                <h4 class="">{{tr('bookings')}}

                </h4>

            </div>

            <div class="card-body">

                <div class="table-responsive">
                    
                    <table id="order-listing" class="table">
                        
                        <thead>
                            <tr>
                                <th>{{tr('s_no')}}</th>
                                <th>{{tr('user') }}</th>
                                <th>{{tr('provider') }}</th>
                                <th>{{tr('host') }}</th>
                                <th>{{tr('checkin') }}</th>
                                <th>{{tr('checkout') }}</th>
                                <th>{{tr('status')}}</th>
                                <th>{{tr('action')}}</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                         
                            @foreach($bookings as $i => $booking_details)

                            <tr>
                                <td>{{$i+1}}</td>

                                <td>

                                    @if(empty($booking_details->user_name))

                                        {{ tr('user_not_avail') }}
                                    
                                    @else
                                        <a href="{{ route('admin.users.view',['user_id' => $booking_details->user_id])}}"> {{ $booking_details->user_name }}</a>
                                    @endif

                                </td>

                                <td>
                                @if(empty($booking_details->provider_name))

                                    {{ tr('provider_not_avail') }}
                                
                                @else
                                    <a href="{{ route('admin.providers.view',['provider_id' => $booking_details->provider_id])}}">{{ $booking_details->provider_name }}</a>
                                @endif

                                </td>

                                <td> 

                                @if(empty($booking_details->host_name))

                                    {{ tr('host_not_avail') }}
                                
                                @else
                                    <a href="{{ route('admin.hosts.view',['host_id' => $booking_details->host_id])}}">{{$booking_details->host_name }} </a>
                                @endif
                                </td>

                                <td>
                                    {{$booking_details->checkin}}
                                </td>

                                <td>
                                    {{$booking_details->checkout}}
                                </td>
                              
                                <td>  

                                    <span class="badge badge-outline-success">
                                        {{booking_status($booking_details->status)}}  
                                    </span>
                                </td>
                               
                                <td>  

                                    <a class="btn btn-outline-primary" href="{{ route('admin.bookings.view', ['booking_id' => $booking_details->id] ) }}">{{tr('view')}} </a>
                                    
                                </td>

                            </tr>

                            @endforeach
                                                                 
                        </tbody>
                    
                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection