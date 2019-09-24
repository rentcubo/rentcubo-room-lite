@extends('layouts.admin') 

@section('title', tr('reviews'))

@section('breadcrumb')
    
    <li class="breadcrumb-item active">{{tr('reviews')}}</li>

@endsection 

@section('styles')

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/css/star-rating-svg.css')}}">

@endsection

@section('content') 

<div class="col-lg-12 grid-margin stretch-card">
        
    <div class="card">

        <div class="card-header bg-card-header ">
        
            <h4 class="">{{ tr('reviews') }}</h4>
        
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="order-listing" class="table">

                    <thead>
                        <th>{{tr('s_no')}}</th>
                        <th>{{tr('user')}}</th>
                        <th>{{tr('provider')}}</th>
                        <th>{{ tr('date') }}</th>
                        <th>{{tr('rating')}}</th>
                        <th>{{ tr('comment') }}</th>
                        <th>{{tr('action')}}</th>
                    </thead>

                    <tbody>
                     
                        @foreach($reviews as $i => $review_details)

                            <tr>
                                <td>{{$i+1}}</td>
                               
                                <td>
                                    <a href="{{ route('admin.users.view', ['user_id' => $review_details->user_id ] ) }}">
                                    
                                        {{ $review_details->user_name ?: "-" }}
                                    </a>
                                </td>

                                <td>
                                    <a href="{{route('admin.providers.view', ['provider_id' => $review_details->provider_id])}}">
                                        {{$review_details->provider_name}}
                                    </a>
                                </td>
                                
                                <td>{{ common_date($review_details->created_at) }}</td>

                                <td>
                                    <div class="my-rating-{{$i}}"></div>
                                </td> 
                                
                                <td>{{ substr($review_details->review, 0, 50) }}...</td>

                                <td>
                                    <button class="btn btn-outline-primary" type="button">
                                        <a href="{{ route('admin.reviews.users.view', ['booking_review_id' => $review_details->booking_review_id ] ) }}">{{tr('view')}}
                                        </a> 
                                    </button>
                                        
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

@section('scripts')

     <script type="text/javascript" src="{{asset('admin-assets/js/jquery.star-rating-svg.min.js')}}"> </script>

    <script>
        <?php foreach ($reviews as $i => $review_details) { ?>
            $(".my-rating-{{$i}}").starRating({
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

