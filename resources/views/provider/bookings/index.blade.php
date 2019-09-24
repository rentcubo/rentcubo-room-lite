@extends('layouts.provider')  

@section('content') 

<div class="main">
    <div class="container-fluid">
        <div class="rooms top-bottom-spacing">
            <!-- Room Head Starts -->
            <div class="rooms-head">
                <h3 class="room-head-tit">{{$bookings->total()}} {{tr('bookings')}}</h3>
            </div>
            <!-- Room Head Ends -->
            <!-- Room Content Starts -->
            <div class="room-content">
                <div class="rooms-table table-responsive">
                    
                    @if($bookings->total())

                        <table class="cmn-table table">
                            <thead>
                                <tr>
                                    <th>{{tr('s_no')}}</th>
                                    <th scope="col">Listing</th>
                                    <th scope="col">{{tr('username')}}</th>
                                    <th scope="col">{{tr('check_in')}}</th>
                                    <th scope="col">{{tr('check_out')}}</th>
                                    <th scope="col">{{tr('total_guests')}}</th>
                                    <th scope="col">{{tr('total')}}</th>
                                    <th scope="col">{{tr('status')}}</th>
                                    <th scope="col">{{tr('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($bookings as $b => $booking_details)
                                    <tr>
                                        <td>
                                            <a href="{{route('provider.bookings.view', ['booking_id' => $booking_details->booking_id, 'id' => Auth::guard('provider')->user()->id])}}">
                                                {{$b+1}}
                                            </a>
                                        </td>
                                        <td>

                                            <div>
                                                <a href="{{route('provider.hosts.view', ['host_id' => $booking_details->host_id])}}" class="room-list-img">
                                                    <img src="{{$booking_details->host_picture}}">
                                                </a>
                                                
                                                <div class="room-list-content">
                                                    <a href="{{route('provider.hosts.view', ['host_id' => $booking_details->host_id])}}" class="room-list-tit">&nbsp;{{$booking_details->host_name}}</a>
                                                </div>
                                            </div>

                                        </td>

                                        <td>{{$booking_details->user_name}}</td>

                                        <td>{{$booking_details->checkin}}</td>

                                        <td>{{$booking_details->checkout}}</td>

                                        <td>{{$booking_details->total_guests}}</td>

                                        <td>{{$booking_details->total_formatted}}</td>

                                        <td>{{$booking_details->status_text}}</td>
                                        <td>
                                            <ul class="action-menu nav">
                                                <li class="nav-item dropdown">

                                                    <a class="dropdown-toggle action-menu-icon" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <img src="{{asset('assets/img/menu.svg')}}">
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-right animate slideIn" aria-labelledby="navbarDropdown">

                                                        @if($booking_details->buttons->cancel_btn_status == YES)

                                                            <a class="dropdown-item" onclick="return confirm(&quot;{{tr('booking_cancell_confirmation', $booking_details->host_name)}}&quot;);" href="{{ route('provider.bookings.cancel', ['booking_id' => $booking_details->booking_id, 'id' => Auth::guard('provider')->user()->id]) }}">
                                                                <i class="fas fa-trash-alt"></i> {{tr('cancel')}}
                                                            </a>

                                                        @endif

                                                        <a class="dropdown-item" href="{{route('provider.bookings.view', ['booking_id' => $booking_details->booking_id, 'id' => Auth::guard('provider')->user()->id])}}"><i class="fas fa-eye"></i> {{tr('view')}}</a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

                                @endforeach
                                
                            </tbody>
                        
                        </table>

                        <div class="col-md-12">

                            <ul class="home-pagination">

                                {{ $bookings->links() }}

                            </ul>

                        </div>

                    @else 

                    <p>{{tr('no_result_found')}}</p>

                    @endif
                </div>
            </div>
            <!-- Room Content Ends -->
        </div>
    </div>
</div>

@endsection