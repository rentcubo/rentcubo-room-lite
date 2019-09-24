@extends('layouts.user') 

@section('title', tr('index'))

@section('breadcrumb')

    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{tr('index')}}</span>
    </li>
           
@endsection 

@section('styles')

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/css/star-rating-svg.css')}}">

@endsection

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
                                    <th scope="col">{{tr('provider_name')}}</th>
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
                                            <a href="{{route('user.bookings.view', ['booking_id' => $booking_details->booking_id, 'id' => Auth::user()->id])}}">
                                                {{$b+1}}
                                            </a>
                                        </td>
                                        <td>

                                            <div>
                                                <a href="{{route('user.hosts.view', ['host_id' => $booking_details->host_id])}}" class="room-list-img">
                                                    <img src="{{$booking_details->host_picture}}">
                                                </a>
                                                <div class="room-list-content">
                                                    <a href="{{route('user.hosts.view', ['host_id' => $booking_details->host_id])}}" class="room-list-tit">&nbsp; {{$booking_details->host_name}}</a>
                                                </div>
                                            </div>

                                        </td>

                                        <td>{{$booking_details->provider_name}}</td>

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

                                                        @if($booking_details->buttons->checkin_btn_status == YES)

                                                            <a class="dropdown-item" onclick="return confirm(&quot;{{tr('booking_checkin_confirmation' ,$booking_details->host_name)}}&quot;);" href="{{route('user.bookings.checkin', ['booking_id' => $booking_details->booking_id, 'id' => Auth::user()->id]) }}">
                                                                <i class="far fa-edit"></i> 
                                                                {{tr('checkin')}}
                                                            </a>

                                                        @endif

                                                        @if($booking_details->buttons->review_btn_status == YES)

                                                            <a class="dropdown-item" data-toggle="modal" data-target="#review-modal-{{$booking_details->booking_id}}">
                                                                <i class="far fa-edit"></i> 
                                                                {{tr('review')}}
                                                            </a>

                                                        @endif

                                                        @if($booking_details->buttons->checkout_btn_status == YES)

                                                            <a class="dropdown-item" onclick="return confirm(&quot;{{tr('booking_checkout_confirmation' ,$booking_details->host_name)}}&quot;);" href="{{route('user.bookings.checkout', ['booking_id' => $booking_details->booking_id, 'id' => Auth::user()->id]) }}">
                                                                <i class="far fa-copy"></i> 
                                                                {{tr('checkout')}}
                                                            </a>

                                                        @endif

                                                        @if($booking_details->buttons->cancel_btn_status == YES)

                                                            <a class="dropdown-item" onclick="return confirm(&quot;{{tr('booking_cancell_confirmation', $booking_details->host_name)}}&quot;);" href="{{ route('user.bookings.cancel', ['booking_id' => $booking_details->booking_id, 'id' => Auth::user()->id]) }}">
                                                                <i class="fas fa-trash-alt"></i> {{tr('cancel')}}
                                                            </a>

                                                        @endif

                                                        <a class="dropdown-item" href="{{route('user.bookings.view', ['booking_id' => $booking_details->booking_id, 'id' => Auth::user()->id])}}"><i class="fas fa-eye"></i> {{tr('view')}}</a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

                                    <div class="modal fade bd-example-modal-md" id="review-modal-{{$booking_details->booking_id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="{{tr('close')}}">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <form action="{{route('user.bookings.review')}}" method="POST">

                                                    @csrf

                                                    <input type="hidden" name="booking_id" value="{{$booking_details->booking_id}}">

                                                    <input type="hidden" name="id" value="{{$booking_details->user_id}}">

                                                    <div class="modal-body">
                                                        <h3>{{tr('review')}} - {{$booking_details->host_name}}</h3>

                                                        <div class="form-group">
                                                            <label for="rating">{{tr('your_rating')}}</label>

                                                            <div class="my-rating-{{$booking_details->booking_id}}"></div>

                                                            <input type="hidden" name="ratings" id="rating-{{$booking_details->booking_id}}" value="1" class="form-control">
                                                        </div>

                                                        <div class="form-group">

                                                            <label for="review">{{tr('review')}}</label>

                                                            <textarea name="review" class="form-control">{{old('review')}}</textarea>

                                                        </div>
                                                    
                                                    </div>

                                                    <div class="modal-footer">

                                                        <button type="button" class="btn danger-btn" data-dismiss="modal">{{tr('close')}}</button>

                                                        <button type="submit" class="btn primary-btn">{{tr('submit')}}</button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>

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

@section('scripts')

    <script type="text/javascript" src="{{asset('admin-assets/js/jquery.star-rating-svg.min.js')}}"></script>

    <script>
        <?php foreach ($bookings as $i => $booking_details) { ?>

            var booking_id = "{{$booking_details->booking_id}}";

            $(".my-rating-{{$booking_details->booking_id}}").starRating({
                starSize: 25,
                initialRating: "1",
                minRating: 1,
                // useFull
                callback: function(currentRating, $el){
                    // make a server call here

                    console.log('#rating-'+booking_id);

                    console.log("currentRating"+currentRating);

                    $('#rating-'+booking_id).val(currentRating);

                }
            });
        <?php } ?>
    </script>

@endsection