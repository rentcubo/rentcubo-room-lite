@extends('layouts.admin') 

@section('title', tr('view_service_location'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{route('admin.service_locations.index')}}">{{tr('service_location')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        <span>{{tr('view_service_location')}}</span>
    </li>
           
@endsection  

@section('content')

    <div class="row">

        <div class="col-md-12">

            <!-- Card group -->
            <div class="card-group">


                <!-- Card -->
                <div class="card mb-4">

                    <!-- Card image -->
                    <div class="view overlay">
                        <img class="card-img-top" src="{{ $service_location_details->picture }}">
                        <a href="#!">
                            <div class="mask rgba-white-slight"></div>
                        </a>
                    </div>

                    <!-- Card content -->
                    <div class="card-body">

                        <!-- Title -->
                        <h4 class="card-title">{{ tr('description') }}</h4>
                        <!-- Text -->
                        <p class="card-text">{{ $service_location_details->description }}</p>
                        
                    </div>
                    <!-- Card content -->

                </div>
                <!-- Card -->

                <!-- Card -->
                <div class="card mb-4">

                    <!-- Card content -->
                    <div class="card-body">

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('service_location_name')}}</h5>
                            
                            <p class="card-text">{{$service_location_details->name}}</p>

                        </div> 

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('service_location_center')}}</h5>
                            
                            <p class="card-text">{{ $service_location_details->address }}</p>

                        </div> 

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('latitude')}}</h5>
                            
                            <p class="card-text">{{ $service_location_details->latitude }}</p>

                        </div> 

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('longitude')}}</h5>
                            
                            <p class="card-text">{{ $service_location_details->longitude }}</p>

                        </div> 

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('radius')}}</h5>
                            
                            <p class="card-text">{{ $service_location_details->cover_radius }}</p>

                        </div>

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('status')}}</h5>
                            
                            <p class="card-text">

                                @if($service_location_details->status == APPROVED)

                                    <span class="badge badge-success badge-md text-uppercase">{{tr('approved')}}</span>

                                @else 

                                    <span class="badge badge-danger badge-md text-uppercase">{{tr('pending')}}</span>

                                @endif
                            
                            </p>

                        </div>
                                                
                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('updated_at')}}</h5>
                            
                            <p class="card-text">{{ common_date($service_location_details->updated_at) }}</p>

                        </div>

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('created_at')}}</h5>
                            
                            <p class="card-text">{{ common_date($service_location_details->created_at) }}</p>

                        </div> 

                    </div>
                    <!-- Card content -->

                </div>

                <!-- Card -->

                <!-- Card -->
                <div class="card mb-4">

                    <!-- Card content -->
                    <div class="card-body">

                        @if(Setting::get('is_demo_control_enabled') == NO )

                            <a href="{{ route('admin.service_locations.edit', ['service_location_id' => $service_location_details->id] ) }}" class="btn btn-primary btn-block">
                                {{tr('edit')}}
                            </a>

                            <a onclick="return confirm(&quot;{{tr('service_location_delete_confirmation' , $service_location_details->name)}}&quot;);" href="{{ route('admin.service_locations.delete',['service_location_id' => $service_location_details->id] ) }}"  class="btn btn-danger btn-block">
                                {{tr('delete')}}
                            </a>

                        @else
                            <a href="javascript:;" class="btn btn-primary btn-block">{{tr('edit')}}</a>

                            <a href="javascript:;" class="btn btn-danger btn-block">{{tr('delete')}}</a>

                        @endif

                        @if($service_location_details->status == APPROVED)

                            <a class="btn btn-danger btn-block" href="{{ route('admin.service_locations.status', ['service_location_id' => $service_location_details->id] ) }}" 
                            onclick="return confirm(&quot;{{$service_location_details->name}} - {{tr('service_location_decline_confirmation')}}&quot;);"> 
                                {{tr('decline')}}
                            </a>

                        @else

                            <a class="btn btn-success btn-block" href="{{ route('admin.service_locations.status', ['service_location_id' => $service_location_details->id] ) }}">
                                {{tr('approve')}}
                            </a>
                                                   
                        @endif
                        <a class="btn btn-success btn-block" href="{{ route('admin.hosts.index', ['service_location_id'=>$service_location_details->id]) }}"> 
                        {{tr('host')}}
                        </a>  


                    </div>
                    <!-- Card content -->

                </div>
                <!-- Card -->
            </div>
            <!-- Card group -->

        </div>

    </div>

@endsection