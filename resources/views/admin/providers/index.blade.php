@extends('layouts.admin') 

@section('title', tr('providers'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.providers.index') }}">{{tr('providers')}}</a></li>

    <li class="breadcrumb-item active" aria-current="page">
        <span>{{ tr('view_providers') }}</span>
    </li>
                      
@endsection 

@section('content')

    <div class="col-lg-12 grid-margin stretch-card">

        <div class="card">

            <div class="card-header bg-card-header ">

                <h4 class="">{{tr('providers')}}

                    <a class="btn btn-secondary pull-right" href="{{route('admin.providers.create')}}">
                        <i class="fa fa-plus"></i> {{tr('add_provider')}}
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
                                <th>{{tr('email')}}</th>
                                <th>{{tr('status')}}</th>
                                <th>{{tr('action')}}</th>
                            </tr>
                        </thead>
                       
                        <tbody>
                         
                            @foreach($providers as $i => $provider_details)
                           
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>
                                        <a href="{{route('admin.providers.view' , ['provider_id' => $provider_details->id])}}">    
                                            {{$provider_details->name}}
                                        </a>
                                    </td>

                                    <td> {{$provider_details->email}} </td>

                                    <td>

                                        @if($provider_details->status == PROVIDER_APPROVED)
                                         
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
                                        
                                        <div class="template-demo">

                                            <div class="dropdown">

                                                <button class="btn btn-outline-primary  dropdown-toggle btn-sm" type="button" id="dropdownMenuOutlineButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{tr('action')}}
                                                </button>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1">
                                                  
                                                <a class="dropdown-item" href="{{ route('admin.providers.view', ['provider_id' => $provider_details->id]) }}">
                                                      {{tr('view')}}
                                                </a>
                                                
                                                @if(Setting::get('is_demo_control_enabled') == NO)
                                                    
                                                    <a class="dropdown-item" href="{{ route('admin.providers.edit', ['provider_id' => $provider_details->id]) }}">{{tr('edit')}}
                                                    </a>
                                                                                                    
                                                    <a class="dropdown-item" onclick="return confirm(&quot;{{tr('provider_delete_confirmation' , $provider_details->name)}}&quot;);" href="{{ route('admin.providers.delete', ['provider_id' => $provider_details->id])}}">{{ tr('delete') }}
                                                    </a>

                                                @else

                                                    <a class="dropdown-item" href="javascript:;">{{tr('edit')}}
                                                    </a>
                                                    
                                                    <a class="dropdown-item" href="javascript:;">{{ tr('delete') }}
                                                    </a>

                                                @endif

                                                <div class="dropdown-divider"></div>

                                                @if($provider_details->status == APPROVED)

                                                    <a class="dropdown-item" href="{{ route('admin.providers.status', ['provider_id' => $provider_details->id])}}" onclick="return confirm(&quot;{{$provider_details->name}} - {{tr('provider_decline_confirmation')}}&quot;);" >
                                                        {{tr('decline')}} 
                                                    </a>      

                                                @else

                                                    <a class="dropdown-item" href="{{ route('admin.providers.status', ['provider_id' => $provider_details->id])}}">
                                                        {{tr('approve')}}
                                                    </a>
                                                       
                                                @endif
                                                    
                                                <div class="dropdown-divider"></div>

                                                <a class="dropdown-item" href="{{ route('admin.hosts.index', ['provider_id' => $provider_details->id])}}">
                                                        {{tr('hosts')}}
                                                    </a>

                                                </div>

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