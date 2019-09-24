 @extends('layouts.admin') 

@section('title', tr('view_sub_categories'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.sub_categories.index' )}}">{{tr('sub_categories')}}</a></li>

    <li class="breadcrumb-item active" aria-current="page">
        <span>{{ tr('view_sub_categories') }}</span>
    </li>         
@endsection 

@section('content')

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">

            <div class="card-header bg-card-header ">

                <h4 class="">

                    {{tr('sub_categories')}}

                    @if($category_details)

                        <a class="custom-a" href="{{route('admin.categories.view', ['category_id' => $category_details->id])}}">

                            - {{$category_details->name}}

                        </a>

                    @endif

                    <a class="btn btn-secondary pull-right" href="{{route('admin.sub_categories.create')}}">
                        <i class="fa fa-plus"></i> {{tr('add_sub_category')}}
                    </a>
                </h4>

            </div>

            <div class="card-body">
                
                <div class="table-responsive">

                    <table id="order-listing" class="table">
                        
                        <thead>
                            <tr>
                                <th>{{tr('s_no')}}</th>
                                <th>{{ tr('picture') }}</th>
                                <th>{{tr('name')}}</th>
                                <th>{{tr('status')}}</th>
                                <th>{{tr('type')}}</th>
                                <th>{{tr('action')}}</th>
                            </tr>
                        </thead>

                        <tbody>
                         
                        @foreach($sub_categories as $i => $sub_category_details)
                            
                            <tr>
                                <td>{{$i+1}}</td>
                                <td>
                                    <img src="{{ $sub_category_details->picture ?: asset('placeholder.jpg') }}" alt="image"> 
                                </td>

                                <td>
                                    <a href="{{route('admin.sub_categories.view' , ['sub_category_id' => $sub_category_details->id] )}}"> 
                                        {{$sub_category_details->name}}
                                    </a>
                                </td>

                                <td>
                                    @if($sub_category_details->status == APPROVED)

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
                                
                                </td>
                                <td>                                    
                                    <div class="template-demo">

                                        <div class="dropdown">

                                            <button class="btn btn-outline-primary  dropdown-toggle" type="button" id="dropdownMenuOutlineButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{tr('action')}}
                                            </button>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1">
                                            
                                            <a class="dropdown-item" href="{{ route('admin.sub_categories.view', ['sub_category_id' => $sub_category_details->id] ) }}">
                                                {{tr('view')}}
                                            </a>

                                            @if(Setting::get('is_demo_control_enabled') == NO)
                                              
                                                <a class="dropdown-item" href="{{ route('admin.sub_categories.edit', ['sub_category_id' => $sub_category_details->id] ) }}">
                                                    {{tr('edit')}}
                                                </a>

                                                <a class="dropdown-item" onclick="return confirm(&quot;{{tr('sub_category_delete_confirmation' , $sub_category_details->name)}}&quot;);" href="{{ route('admin.sub_categories.delete', ['sub_category_id' => $sub_category_details->id] ) }} ">
                                                    {{ tr('delete') }}
                                                </a>  

                                            @else

                                                <a class="dropdown-item" href="javascript:;">{{tr('edit')}}
                                                </a>
                                                
                                                <a class="dropdown-item" href="javascript:;">{{ tr('delete') }}
                                                </a>

                                            @endif

                                            <div class="dropdown-divider"></div>

                                            @if($sub_category_details->status == APPROVED)

                                                <a class="dropdown-item" href="{{ route('admin.sub_categories.status', ['sub_category_id' => $sub_category_details->id] ) }}" onclick="return confirm(&quot;{{$sub_category_details->name}} - {{tr('sub_category_decline_confirmation')}}&quot;);"> 
                                                    {{tr('decline')}}
                                                </a>

                                            @else

                                                <a class="dropdown-item" href="{{ route('admin.sub_categories.status', ['sub_category_id' => $sub_category_details->id] ) }}">
                                                    {{tr('approve')}}
                                                </a>
                                                   
                                            @endif   

                                            <div class="dropdown-divider"></div>

                                            <a class="dropdown-item" href="{{route('admin.hosts.index', ['sub_category_id' => $sub_category_details->id])}}">{{tr('hosts')}}</a>

                                            <a class="dropdown-item"  href="{{route('admin.bookings.index', ['sub_category_id' => $sub_category_details->id])}}">{{tr('bookings')}}</a>

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