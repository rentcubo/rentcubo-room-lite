@extends('layouts.admin') 

@section('title')

{{$page_title}}

@endsection

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.hosts.index') }}">{{tr('hosts')}}</a></li>
    
    <li class="breadcrumb-item active" aria-current="page">
        <span>{{ tr('view_hosts') }}</span>
    </li>
           
@endsection 

@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    
    <div class="card">

        <div class="card-header bg-card-header ">

            <h4 class="text-uppercase"><b>{{$page_title}}</b>

                <a class="btn btn-secondary pull-right" href="{{route('admin.hosts.create')}}">
                    <i class="fa fa-plus"></i> {{tr('add_host')}}
                </a>
            </h4>

        </div>

        <div class="card-body">

            <div class="table-responsive">
            
                <table id="order-listing" class="table">
                    <thead>
                        <tr>
                            <th>{{tr('s_no')}}</th>
                            <th>{{tr('host_name')}}</th>
                            <th>{{tr('provider')}}</th>
                            <th>{{tr('location')}}</th>
                            <th>{{tr('status')}}</th>
                            <th>{{tr('action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    	@foreach($hosts as $h => $host_details)

	                    	<tr>
	                    		<td>{{$h+1}}</td>

	                    		<td>
                                    <a href="{{ route('admin.hosts.view', ['host_id' => $host_details->id]) }}">{{$host_details->host_name}}</a>
	                    			<p class="text-gray"><br>{{$host_details->updated_at}}</p>
	                    		</td>

	                    		<td>
                                    <a href="{{route('admin.providers.view', ['provider_id' => $host_details->provider_id])}}">
                                        {{$host_details->provider_name}}
                                    </a>
                                </td>

	                    		<td>
                                    {{$host_details->full_address}}
                                </td>

	                    		<td>

	                    			@if($host_details->admin_status == ADMIN_HOST_APPROVED) 

                                        <span class="badge badge-outline-success">
                                        	{{ tr('ADMIN_HOST_APPROVED') }} 
                                        </span>

                                    @else

                                        <span class="badge badge-outline-warning">
                                        	{{ tr('ADMIN_HOST_PENDING') }} 
                                        </span>

                                    @endif

	                    		</td>
	                    		
	                    		<td>                                    
                                   
                                    <div class="template-demo">
                                   
                                        <div class="dropdown">
                                   
                                            <button class="btn btn-outline-primary  dropdown-toggle btm-sm" type="button" id="dropdownMenuOutlineButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{tr('action')}}
                                            </button>
                                   
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1">
                                              
                                                <a class="dropdown-item" href="{{ route('admin.hosts.view', ['host_id' => $host_details->id]) }}">
                                                  {{tr('view')}}
                                                </a>
                                                
                                                @if(Setting::get('is_demo_control_enabled') == NO)
                                                
                                                    <a class="dropdown-item" href="{{ route('admin.hosts.edit', ['host_id' => $host_details->id]) }}">
                                                        {{tr('edit')}}
                                                    </a>

                                                    <a class="dropdown-item" onclick="return confirm(&quot;{{tr('host_delete_confirmation' , $host_details->name)}}&quot;);" href="{{ route('admin.hosts.delete', ['host_id' => $host_details->id]) }}">
                                                        {{ tr('delete') }}
                                                    </a>

                                                @else

                                                    <a class="dropdown-item" href="javascript:;">
                                                        {{tr('edit')}}
                                                    </a>

                                                    <a class="dropdown-item" href="javascript:;">
                                                        {{ tr('delete') }}
                                                    </a>

                                                @endif

                                                <div class="dropdown-divider"></div>

                                               
                                                @if($host_details->admin_status == APPROVED)

                                                    <a class="dropdown-item" href="{{ route('admin.hosts.status', ['host_id' => $host_details->id] ) }}" 
                                                    onclick="return confirm(&quot;{{$host_details->host_name}} - {{tr('host_decline_confirmation')}}&quot;);"> 
                                                        {{tr('decline')}}
                                                    </a>

                                                @else

                                                    <a class="dropdown-item" href="{{ route('admin.hosts.status', ['host_id' => $host_details->id] ) }}">
                                                        {{tr('approve')}}
                                                    </a>
                                                       
                                                @endif
                                             
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