@extends('layouts.user') 

@section('styles')

<style>

    .other-proname {
        color: #767676;
        text-transform: capitalize;
        width: 4.28em;
        /*overflow: hidden;*/
        /*text-overflow: ellipsis;*/
        white-space: inherit;
        display: inline-block;
        float: left;
    }
    
</style>

@endsection

@section('content')

<!-- body -->
<div class="main">
   
    <div class="site-content">
        <div class="top-bottom-spacing">
            <div class="row">
                <div class="col-xl-7 col-lg-10 col-md-10 auto-margin">
                    <div class="media">
                        <div class="media-body mr-3">
                            <a href="#">
                                <p class="red-text txt-overflow">{{$booking_details->host_type}}</p>
                            </a>
                            <h1 class="category-section-head">{{$booking_details->host_name}}</h1>
                            <h4 class="captalize section-subhead">{{$booking_details->full_address}}</h4>

                            <h5><span class="text-primary"> <b>{{$booking_details->status_text}}</b></span></h5>

                        </div>
                        <div>

                            <img src="{{$booking_details->user_details->picture}}" alt="{{$booking_details->user_details->user_name}}" class="mt-3 rounded-circle review-img">

                            <p class="text-center top3 mb-0">
                                <a href="#" class="other-proname">
                                    {{$booking_details->user_details->user_name}}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="basic-box">

                        <h4></h4>
                        
                        @if($booking_details->buttons->cancel_btn_status == YES)

                            <a class="green-btn md-btn danger-btn" onclick="return confirm(&quot;{{tr('booking_cancell_confirmation', $booking_details->host_name)}}&quot;);" href="{{ route('provider.bookings.cancel', ['booking_id' => $booking_details->booking_id, 'id' => Auth::guard('provider')->user()->id]) }}">
                                <i class="fas fa-trash-alt"></i> {{tr('cancel')}}
                            </a>

                        @endif

                    </div>

                    <div class="highlights-box">
                        <h2 class="chathead mt-0">{{tr('trip_details')}}</h2>
                        <p class="overview-line"></p>

                        <h5 class="choosen-details">
                        	<i class="fas fa-user mr-3"></i>
                        	{{$booking_details->total_guests}} {{$booking_details->total_guests > 1 ? tr('guests') : tr('guest')}}
                        </h5>

                        <h5 class="choosen-details"><i class="far fa-calendar-alt mr-3"></i>
                        	{{$booking_details->checkin}}<i class="fas fa-arrow-right ml-3 mr-3"></i>{{$booking_details->checkout}}</h5>

                        <p class="overview-line"></p>

                        <h5 class="choosen-details text-uppercase">
                            <i class="far fa-money-bill-alt mr-3"></i>
                        	{{$booking_details->pricing_details->payment_mode}}
                        </h5>

                        <p class="overview-line"></p>

                        <div class="row">
                            <div class="col-6">
                                
                                <h5 class="choosen-details">{{$booking_details->pricing_details->per_day_formatted}} x {{$booking_details->total_days_text}}</h5>

                                <h5 class="">Service fee</h5>
                            </div>
                            <div class="col-6 text-right">
                                <h5 class="choosen-details">{{$booking_details->total_formatted}}</h5>
                                <h5 class="choosen-details">{{Setting::get('currency')}} 0.00</h5>
                            </div>
                        </div>

                        <p class="overview-line"></p>
                        <div class="row">
                            <div class="col-6">
                                <h5 class="choosen-details">{{tr('total')}}</h5>
                            </div>
                            <div class="col-6 text-right">
                                <h5 class="choosen-details">{{$booking_details->total_formatted}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 pl-5 relative">
                    <div class="trips-map-img">
                        <img src="{{$booking_details->host_picture}}" class="homes-img">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- body -->

@endsection
