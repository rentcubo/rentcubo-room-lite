@extends('layouts.admin') 

@section('title', tr('view_category'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{route('admin.categories.index')}}">{{tr('categories')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        <span>{{tr('view_category')}}</span>
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
                        <img class="card-img-top" src="{{ $category_details->picture }}">
                        <a href="#!">
                            <div class="mask rgba-white-slight"></div>
                        </a>
                    </div>

                    <!-- Card content -->
                    <div class="card-body">

                        <!-- Title -->
                        <h4 class="card-title">{{ tr('description') }}</h4>
                        <!-- Text -->
                        <p class="card-text">{{ $category_details->description }}</p>
                        
                    </div>
                    <!-- Card content -->

                </div>
                <!-- Card -->

                <!-- Card -->
                <div class="card mb-4">

                    <!-- Card content -->
                    <div class="card-body">

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('name')}}</h5>
                            
                            <p class="card-text">{{$category_details->name}}</p>

                        </div> 

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('status')}}</h5>
                            
                            <p class="card-text">

                                @if($category_details->status == APPROVED)

                                    <span class="badge badge-success badge-md text-uppercase">{{tr('approved')}}</span>

                                @else 

                                    <span class="badge badge-danger badge-md text-uppercase">{{tr('pending')}}</span>

                                @endif
                            
                            </p>

                        </div>
                                                
                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('updated_at')}}</h5>
                            
                            <p class="card-text">{{ common_date($category_details->updated_at) }}</p>

                        </div>

                        <div class="custom-card">
                        
                            <h5 class="card-title">{{tr('created_at')}}</h5>
                            
                            <p class="card-text">{{ common_date($category_details->created_at) }}</p>

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

                            <a href="{{ route('admin.categories.edit',['category_id' => $category_details->id] ) }}" class="btn btn-primary btn-block">{{tr('edit')}}</a>

                            <a onclick="return confirm(&quot;{{tr('category_delete_confirmation' , $category_details->name)}}&quot;);" href="{{ route('admin.categories.delete',['category_id' => $category_details->id] ) }}"  class="btn btn-danger btn-block">{{tr('delete')}}</a>

                            <a class="btn btn-info btn-block" href="{{route('admin.sub_categories.index', ['category_id' => $category_details->id])}}"> {{tr('sub_categories')}}</a>

                        @else
                            <a href="javascript:;" class="btn btn-primary btn-block">{{tr('edit')}}</a>

                            <a href="javascript:;" class="btn btn-danger btn-block">{{tr('delete')}}</a>

                            <a href="javascript:;" class="btn btn-info btn-block">{{tr('sub_categories')}}</a>

                        @endif

                        @if($category_details->status == APPROVED)

                            <a class="btn btn-danger btn-block" href="{{ route('admin.categories.status',['category_id' => $category_details->id] ) }}" 
                            onclick="return confirm(&quot;{{$category_details->name}} - {{tr('category_decline_confirmation')}}&quot;);"> 
                                {{tr('decline')}}
                            </a>

                        @else

                            <a class="btn btn-success btn-block" href="{{ route('admin.categories.status',['category_id' => $category_details->id] ) }}">
                                {{tr('approve')}}
                            </a>
                                                   
                        @endif


                        <a class="btn btn-success btn-block" href="{{ route('admin.hosts.index', ['category_id'=>$category_details->id]) }}"> 
                        {{tr('hosts')}}
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