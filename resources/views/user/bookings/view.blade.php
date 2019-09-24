@extends('layouts.user') 

@section('styles')

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/css/star-rating-svg.css')}}">

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
                            <img src="{{$booking_details->provider_details->picture}}" alt="{{$booking_details->provider_details->provider_name}}" class="mt-3 rounded-circle review-img">
                         
                            <p class="text-center top3 mb-0"><a href="#" class="other-proname">{{$booking_details->provider_details->provider_name}}</a></p>
                        </div>
                   
                    </div>

                    <div class="basic-box">
                        <h4></h4>
                        
                        @if($booking_details->buttons->checkin_btn_status == YES)

                            <a class="green-btn md-btn warning-btn" onclick="return confirm(&quot;{{tr('booking_checkin_confirmation' ,$booking_details->host_name)}}&quot;);" href="{{route('user.bookings.checkin', ['booking_id' => $booking_details->booking_id, 'id' => Auth::user()->id]) }}">
                                <i class="far fa-edit"></i> 
                                {{tr('checkin')}}
                            </a>

                        @endif

                        @if($booking_details->buttons->checkout_btn_status == YES)

                            <a class="green-btn md-btn success-btn" onclick="return confirm(&quot;{{tr('booking_checkout_confirmation' ,$booking_details->host_name)}}&quot;);" href="{{route('user.bookings.checkout', ['booking_id' => $booking_details->booking_id, 'id' => Auth::user()->id]) }}">
                                <i class="far fa-copy"></i> 
                                {{tr('checkout')}}
                            </a>

                        @endif

                        @if($booking_details->buttons->cancel_btn_status == YES)

                            <a class="green-btn md-btn danger-btn" onclick="return confirm(&quot;{{tr('booking_cancell_confirmation', $booking_details->host_name)}}&quot;);" href="{{ route('user.bookings.cancel', ['booking_id' => $booking_details->booking_id, 'id' => Auth::user()->id]) }}">
                                <i class="fas fa-trash-alt"></i> {{tr('cancel')}}
                            </a>

                        @endif

                        @if($booking_details->buttons->review_btn_status == YES)

                            <a class="green-btn md-btn info-btn" data-toggle="modal" data-target="#review-modal-{{$booking_details->booking_id}}">
                                <i class="far fa-edit"></i> 
                                {{tr('review')}}
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
                        <img src="{{$booking_details->picture}}" class="homes-img">
                    </div>
                </div>
           
            </div>
        
        </div>
    
    </div>

</div>
<!-- body -->

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

                <input type="hidden" name="id" value="{{Auth::user()->id}}">

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

@endsection


@section('scripts')

<script type="text/javascript" src="{{asset('admin-assets/js/jquery.star-rating-svg.min.js')}}"> </script>

<script src="{{ asset('admin-assets/node_modules/popper.js/dist/umd/popper.min.js') }}"></script>

<script>

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
</script>

@endsection