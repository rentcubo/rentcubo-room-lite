@extends('layouts.admin') 

@section('title', tr('view_categories'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{tr('categories')}}</a></li>

    <li class="breadcrumb-item active" aria-current="page">
        <span>{{ tr('view_categories') }}</span>
    </li>
           
@endsection 

@section('content')

    <div class="col-lg-12 grid-margin stretch-card">
        
        <div class="card">

            <div class="card-header bg-card-header ">

                <h4 class="">{{tr('view_categories')}}

                    <a class="btn btn-secondary pull-right" href="{{route('admin.categories.create')}}">
                        <i class="fa fa-plus"></i> {{tr('add_category')}}
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
                                <th>{{tr('picture') }}</th>
                                <th>{{tr('status')}}</th>
                                <th>{{tr('action')}}</th>
                            </tr>
                        </thead>
                        <tbody>

                         
                        @foreach($categories as $i => $category_details)

                            <tr>
                                <td>{{$i+1}}</td>
                                
                                <td>
                                    <a href="{{route('admin.categories.view' , ['category_id' => $category_details->id] )}}"> {{$category_details->name}}
                                    </a>
                                </td>                                

                                <td>
                                    <img src="{{ $category_details->picture ?: asset('placeholder.jpg') }}" alt="image"> 
                                </td>

                                <td>                                    
                                    @if($category_details->status == APPROVED)

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

                                            <a class="dropdown-item" href="{{ route('admin.categories.view', ['category_id' => $category_details->id] ) }}">{{tr('view')}}
                                            </a>
                                            
                                            @if(Setting::get('is_demo_control_enabled') == NO)
                                            
                                                <a class="dropdown-item" href="{{ route('admin.categories.edit', ['category_id' => $category_details->id] ) }}">{{tr('edit')}}
                                                </a>

                                                <a class="dropdown-item" 
                                                onclick="return confirm(&quot;{{tr('category_delete_confirmation' , $category_details->name)}}&quot;);" href="{{ route('admin.categories.delete', ['category_id' => $category_details->id] ) }}" >
                                                    {{ tr('delete') }}
                                                </a>

                                            @else

                                                <a class="dropdown-item" href="javascript:;">{{tr('edit')}}
                                                </a>

                                                <a class="dropdown-item" href="javascript:;">{{ tr('delete') }}
                                                </a>

                                            @endif

                                            <div class="dropdown-divider"></div>

                                            @if($category_details->status == APPROVED)

                                                <a class="dropdown-item" href="{{ route('admin.categories.status', ['category_id' => $category_details->id] ) }}" 
                                                onclick="return confirm(&quot;{{$category_details->name}} - {{tr('category_decline_confirmation')}}&quot;);"> 
                                                    {{tr('decline')}}
                                                </a>

                                            @else

                                                <a class="dropdown-item" href="{{ route('admin.categories.status', ['category_id' => $category_details->id] ) }}">
                                                    {{tr('approve')}}
                                                </a>
                                                   
                                            @endif

                                            <div class="dropdown-divider"></div>

                                            <a class="dropdown-item" href="{{route('admin.sub_categories.index', ['category_id' => $category_details->id])}}">
                                                {{tr('sub_categories')}}
                                            </a>

                                            <a class="dropdown-item" href="{{route('admin.hosts.index', ['category_id' => $category_details->id])}}">{{tr('hosts')}}</a>

                                            <a class="dropdown-item"  href="{{route('admin.bookings.index', ['category_id' => $category_details->id])}}">{{tr('bookings')}}</a>
                                          
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