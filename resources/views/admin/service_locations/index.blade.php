@extends('layouts.admin') 

@section('title', tr('view_service_locations'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.service_locations.index') }}">{{tr('service_locations')}}</a></li>
    
    <li class="breadcrumb-item active" aria-current="page">
        <span>{{ tr('view_service_locations') }}</span>
    </li>
    
@endsection 

@section('content')

    <div class="col-lg-12 grid-margin stretch-card">
        
        <div class="card">

            <div class="card-header bg-card-header ">

                <h4 class="">{{tr('view_service_locations')}}

                    <a class="btn btn-secondary pull-right" href="{{route('admin.service_locations.create')}}">
                        <i class="fa fa-plus"></i> {{tr('add_service_location')}}
                    </a>
                </h4>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="order-listing" class="table">
                        <thead>
                            <tr>
                                <th>{{tr('s_no')}}</th>
                                <th>{{tr('name')}}</th>
                                <th>{{tr('address')}}</th>
                                <th>{{tr('picture') }}</th>
                                <th>{{tr('radius') }}</th>
                                <th>{{tr('status')}}</th>
                                <th>{{tr('action')}}</th>
                            </tr>
                        </thead>

                        <tbody>   

                        @foreach($service_locations as $i => $service_location_details)

                            <tr>
                                <td>{{$i+1}}</td>
                                
                                <td>
                                    <a href="{{route('admin.service_locations.view' , ['service_location_id' => $service_location_details->id] )}}"> 
                                        {{$service_location_details->name}}
                                    </a>
                                </td>

                                <td>{{$service_location_details->address}}</td>

                                <td>
                                    <img src="{{ $service_location_details->picture ?: asset('placeholder.jpg') }}" alt="image"> 
                                </td>
                               
                                <td>{{$service_location_details->cover_radius}}</td>

                                <td>                                    
                                    @if($service_location_details->status == APPROVED)

                                        <span class="badge badge-outline-success">
                                            {{ tr('approved') }} 
                                        </span>

                                    @else

                                        <span class="badge badge-outline-danger">
                                            {{ tr('declined') }} 
                                        </span>
                                          
                                    @endif
                                </td>

                                <td>   
                                    <div class="dropdown">

                                        <button class="btn btn-outline-primary  dropdown-toggle btn-sm" type="button" id="dropdownMenuOutlineButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{tr('action')}}
                                        </button>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1">

                                            <a class="dropdown-item" href="{{ route('admin.service_locations.view', ['service_location_id' => $service_location_details->id] ) }}">
                                                {{tr('view')}}
                                            </a>

                                            @if(Setting::get('is_demo_control_enabled') == NO)
                                            
                                                <a class="dropdown-item" href="{{ route('admin.service_locations.edit', ['service_location_id' => $service_location_details->id] ) }}">
                                                    {{tr('edit')}}
                                                </a>

                                                <div class="dropdown-divider"></div>
                                        
                                                <a class="dropdown-item" 
                                                onclick="return confirm(&quot;{{tr('service_location_delete_confirmation' , $service_location_details->name)}}&quot;);" href="{{ route('admin.service_locations.delete', ['service_location_id' => $service_location_details->id] ) }}" >
                                                    {{ tr('delete') }}
                                                </a>


                                            @else

                                                <a class="dropdown-item" href="javascript:;">{{tr('edit')}}</a>

                                                <a class="dropdown-item" href="javascript:;">{{ tr('delete') }}</a>

                                            @endif

                                            <div class="dropdown-divider"></div>

                                            @if($service_location_details->status == APPROVED)

                                                <a class="dropdown-item" href="{{ route('admin.service_locations.status', ['service_location_id' =>  $service_location_details->id] ) }}" 
                                                onclick="return confirm(&quot;{{$service_location_details->name}} - {{tr('service_location_decline_confirmation')}}&quot;);"> 
                                                    {{tr('decline')}}
                                                </a>

                                            @else

                                                <a class="dropdown-item" href="{{ route('admin.service_locations.status', ['service_location_id' =>  $service_location_details->id] ) }}">
                                                    {{tr('approve')}}
                                                </a>
                                                   
                                            @endif

                                            <div class="dropdown-divider"></div>

                                            <a class="dropdown-item" href="{{route('admin.hosts.index', ['service_location_id' => $service_location_details->id])}}">{{tr('hosts')}}</a>

                                            <a class="dropdown-item"  href="{{route('admin.bookings.index', ['service_location_id' => $service_location_details->id])}}">{{tr('bookings')}}</a>

                                        </div>
                                         
                                    </div>
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