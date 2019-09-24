@extends('layouts.provider') 

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

    <div class="site-content">

        <div class="top-bottom-spacing single-booking">
            
            <div class="single-book-nav">
                <a href="{{route('provider.hosts.index', ['provider_id' => $host->provider_id])}}" class="back-link"><i class="fas fa-chevron-left"></i> {{tr('back')}}
                </a>
            </div>

            <div class="single-booking-content" style="margin-bottom: 50px">
                
                <h3 class="single-book-tit">{{tr('host_details')}}</h3>
                
                <div class="row">
                    <!-- Single-book Left Starts -->
                    <div class="col-md-4">
                        <div class="single-book-left">
                            <div class="single-box">
                                <h3 class="single-place-tit">{{$host->host_name}}</h3>
                                <p class="single-place-txt">{{$host->host_type}}</p>
                                <p class="single-place-txt">{{$host->updated_at}}</p>
                            </div>
                            <div class="single-box">
                                <div class="table-responsive guest-table">
                                    <table class="table">
                                       
                                        <tbody>
                                            <tr>
                                                <th scope="row">{{tr('category')}}</th>
                                                <td>{{$host->category_name}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{tr('sub_category')}}</th>
                                                <td>{{$host->sub_category_name}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{tr('location')}}</th>
                                                <td>{{$host->full_address}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                {{tr('guests')}}</th>
                                                <td>{{$host->total_guests}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{tr('base_price')}}</th>
                                                <td>{{formatted_amount($host->base_price)}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{tr('status')}}</th>
                                                <td>
                                                    @if($host->admin_status == ADMIN_HOST_APPROVED) 

                                                        <span class="badge status-btn badge-success">
                                                            {{ tr('approved') }} 
                                                        </span>

                                                    @else

                                                        <span class="badge status-btn badge-danger">
                                                            {{ tr('pending') }} 
                                                        </span>

                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <a class="btn btn-warning" href="{{ route('provider.hosts.edit', ['host_id' => $host->id]) }}"><i class="far fa-edit"></i> {{tr('edit')}}</a>

                                    <a class="btn btn-danger" onclick="return confirm(&quot;{{tr('host_delete_confirmation' , $host->host_name)}}&quot;);" href="{{ route('provider.hosts.delete', ['host_id' => $host->id]) }}"><i class="fas fa-trash-alt"></i> {{ tr('delete') }}</a>

                                    <a class="btn btn-primary" href="{{ route('provider.bookings.index', ['host_id' => $host->id]) }}"><i class="far fa-book"></i> {{tr('bookings')}}</a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Single-book Left Ends -->
                    <!-- Single-book Right Starts -->
                    <div class="col-md-8">
                        <div class="single-book-right">
                            <div class="single-book-img">
                                <img src="{{ $host->picture ?: asset('placeholder.jpg') }}" class="img-sm rounded" alt="image"/>
                            </div>
                            <div class="single-right-wrap">
                                <div class="table-responsive single-billing-table check-block">  
                                    <h3>{{tr('description')}}</h3>
                                    <p><?= $host->description ?></p>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                    <!-- Single-book Right Starts -->
                    <div class="col-md-12">

                        <h1 class="section-head top4 bottom">{{tr('user_reviews')}}</h1>

                        @foreach($user_reviews as $h => $user_review)

                            <div class="media">
                                <div>
                                    <img src="{{$user_review->userDetails->picture ?? asset('placeholder.jpg')}}" alt="John Doe" class="review-img rounded-circle">
                                </div>
                                <div class="media-body ml-3">
                                    <h4 class="mt-0 lh-1-4">{{$user_review->review}}</h4>
                                    <p class="grey-clr mb-0 top2">{{$user_review->updated_at}}</p>
                                    <div class="user-rating-{{$h}}"></div>
                                </div>
                            </div>
                            <p class="review-line"></p>

                        @endforeach

                        @if(count($user_reviews) == 0)

                            <p>{{tr('no_reviews')}}</p>

                            <p class="review-line"></p>

                        @endif  

                    </div>
                
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

     <script type="text/javascript" src="{{asset('admin-assets/js/jquery.star-rating-svg.min.js')}}"> </script>

    <script>
        <?php foreach ($user_reviews as $i => $review_details) { ?>
            $(".user-rating-{{$i}}").starRating({
                starSize: 25,
                initialRating: "{{$review_details->ratings}}",
                readOnly: true,
                callback: function(currentRating, $el){
                    // make a server call here
                }
            });
        <?php } ?>
    </script>

@endsection