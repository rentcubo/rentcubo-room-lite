@extends('layouts.admin') 

@section('title', tr('view_static_page'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.static_pages.index' )}}">{{tr('static_pages')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        <span>{{ tr('view_static_pages') }}</span>
    </li>
           
@endsection 

@section('content')

<div class="col-lg-12 grid-margin stretch-card">
        
    <div class="card">

        <div class="card-header bg-card-header ">

            <h4 class="">{{tr('static_pages')}}

                <a class="btn btn-secondary pull-right" href="{{route('admin.static_pages.create')}}">
                    <i class="fa fa-plus"></i> {{tr('add_static_page')}}
                </a>
            </h4>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="order-listing" class="table">
                    <thead>
                        <tr>
                            <th>{{tr('s_no')}}</th>
                            <th>{{tr('title')}}</th>
                            <th>{{tr('static_page_type')}}</th>
                            <th>{{tr('status')}}</th>
                            <th>{{tr('action')}}</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($static_pages as $i => $static_page_details)

                            <tr>
                                <td>{{$i+1}}</td>

                                <td>
                                    <a href="{{route('admin.static_pages.view' , ['static_page_id'=> $static_page_details->id] )}}"> {{$static_page_details->title}}</a>
                                </td>

                                <td>{{$static_page_details->type}}</td>

                                <td>
                                    @if($static_page_details->status == APPROVED)

                                      <span class="badge badge-success">{{tr('approved')}}</span> 
                                    @else

                                      <span class="badge badge-warning">{{tr('pending')}}</span> 
                                    @endif
                                </td>

                                <td>   
                                    
                                    <div class="dropdown">

                                        <button class="btn btn-outline-primary  dropdown-toggle btn-sm" type="button" id="dropdownMenuOutlineButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{tr('action')}}
                                        </button>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1">

                                            <a class="dropdown-item" href="{{ route('admin.static_pages.view', ['static_page_id' => $static_page_details->id] ) }}">
                                                {{tr('view')}}
                                            </a>
                                            
                                            @if(Setting::get('is_demo_control_enabled') == NO)
                                            
                                                <a class="dropdown-item" href="{{ route('admin.static_pages.edit', ['static_page_id' => $static_page_details->id] ) }}">
                                                    {{tr('edit')}}
                                                </a>

                                                <a class="dropdown-item" 
                                                onclick="return confirm(&quot;{{tr('static_page_delete_confirmation' , $static_page_details->title)}}&quot;);" href="{{ route('admin.static_pages.delete', ['static_page_id' => $static_page_details->id] ) }}" >
                                                    {{ tr('delete') }}
                                                </a>

                                            @else

                                                <a class="dropdown-item" href="javascript:;">{{tr('edit')}}</a>

                                                <a class="dropdown-item" href="javascript:;">{{ tr('delete') }}</a>

                                            @endif

                                            <div class="dropdown-divider"></div>

                                            @if($static_page_details->status == APPROVED)

                                                <a class="dropdown-item" href="{{ route('admin.static_pages.status', ['static_page_id' =>  $static_page_details->id] ) }}" 
                                                onclick="return confirm(&quot;{{$static_page_details->title}} - {{tr('static_page_decline_confirmation')}}&quot;);"> 
                                                    {{tr('decline')}}
                                                </a>

                                            @else

                                                <a class="dropdown-item" href="{{ route('admin.static_pages.status', ['static_page_id' =>  $static_page_details->id] ) }}">
                                                    {{tr('approve')}}
                                                </a>
                                                   
                                            @endif

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