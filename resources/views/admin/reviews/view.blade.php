@extends('layouts.admin') 

@section('title', tr('reviews'))

@section('breadcrumb')

    <li class="breadcrumb-item active" aria-current="page">
        <span>{{tr('reviews')}}</span>
    </li>


@endsection  

@section('styles')

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/css/star-rating-svg.css')}}">

@endsection

@section('content')
    
     <div class="row">

        <div class="col-md-12">

            <!-- Card group -->
            <div class="card-group">

                  <!-- Card -->
                <div class="card mb-4">

                    <!-- Card content -->
                    <div class="card-body">

                        <h4 class="card-title">{{ tr('rating') }}</h4>
                        <div class="my-rating"></div>

                        <!-- Title -->
                        <h4 class="card-title">{{ tr('review') }}</h4>
                        <!-- Text -->
                        <p class="card-text">{{ $reviews->review }}</p>
                        
                    </div>
                    <!-- Card content -->

                </div>

                <!-- Card -->
                <div class="card mb-4">

                    <!-- Card content -->
                    <div class="card-body">

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('user_name')}}</h5>
                            
                            <p class="card-text">{{ $reviews->user_name}}</p>

                        </div> 

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('provider_name')}}</h5>
                            
                            <p class="card-text">{{ $reviews->provider_name }}</p>

                        </div>

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('host_name')}}</h5>
                            
                            <p class="card-text">{{ $reviews->host_name }} </p>

                        </div>
                                                
                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('updated_at')}}</h5>
                            
                            <p class="card-text">{{ common_date($reviews->updated_at) }}</p>

                        </div>

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('created_at')}}</h5>
                            
                            <p class="card-text">{{ common_date($reviews->created_at) }}</p>

                        </div> 

                    </div>
                    <!-- Card content -->

                </div>

                <!-- Card -->
            
            </div>
            <!-- Card group -->

        </div>

    </div>

@endsection

@section('scripts')

    <script type="text/javascript" src="{{asset('admin-assets/js/jquery.star-rating-svg.min.js')}}"> </script>


    <script>
        $(".my-rating").starRating({
            starSize: 25,
            readOnly: true,
            initialRating: "{{$reviews->ratings}}",
            callback: function(currentRating, $el){
                // make a server call here
            }
        }); 
    </script>

@endsection
