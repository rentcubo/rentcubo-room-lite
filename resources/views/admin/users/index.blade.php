@extends('layouts.admin') 

@section('title', tr('view_users'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.users.index' )}}">{{tr('users')}}</a></li>

    <li class="breadcrumb-item active" aria-current="page">
        <span>{{ tr('view_users') }}</span>
    </li> 
           
@endsection 

@section('content')

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">

            <div class="card-header bg-card-header ">

                <h4 class="">{{tr('view_users')}}

                    <a class="btn btn-secondary pull-right" href="{{route('admin.users.create')}}">
                        <i class="fa fa-plus"></i> {{tr('add_user')}}
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

                        @foreach($users as $i => $user_details)
                            <tr>
                                <td>{{$i+1}}</td>

                                <td>
                                    <a href="{{route('admin.users.view' , ['user_id' => $user_details->id])}}"> {{ $user_details->name }}
                                    </a>
                                </td>

                                <td> {{ $user_details->email }} </td>

                                <td>

                                    @if($user_details->status == USER_APPROVED)

                                        <span class="badge badge-outline-success">{{ tr('approved') }} </span>

                                    @else

                                        <span class="badge badge-outline-danger">{{ tr('declined') }} </span>

                                    @endif

                                </td>

                                <td>     

                                    <div class="template-demo">

                                        <div class="dropdown">

                                            <button class="btn btn-outline-primary  dropdown-toggle" type="button" id="dropdownMenuOutlineButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{tr('action')}}
                                            </button>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1">

                                            <a class="dropdown-item" href="{{ route('admin.users.view', ['user_id' => $user_details->id]) }}">
                                                  {{tr('view')}}
                                            </a>

                                            @if(Setting::get('is_demo_control_enabled') == NO)

                                                <a class="dropdown-item" href="{{ route('admin.users.edit', ['user_id' => $user_details->id]) }}">
                                                    {{tr('edit')}}
                                                </a>
                                                
                                                <a class="dropdown-item" href="{{route('admin.users.delete', ['user_id' => $user_details->id])}}" 
                                                onclick="return confirm(&quot;{{tr('user_delete_confirmation' , $user_details->name)}}&quot;);">
                                                    {{tr('delete')}}
                                                </a>
                                            @else

                                                <a class="dropdown-item" href="javascript:;">{{tr('edit')}}</a>
                                              
                                                <a class="dropdown-item" href="javascript:;">{{tr('delete')}}</a>                                                
                                            @endif

                                            <div class="dropdown-divider"></div>
                                            
                                            @if($user_details->status == USER_APPROVED)

                                                <a class="dropdown-item" href="{{ route('admin.users.status', ['user_id' => $user_details->id]) }}" onclick="return confirm(&quot;{{$user_details->first_name}} - {{tr('user_decline_confirmation')}}&quot;);" >
                                                    {{ tr('decline') }} 
                                                </a>

                                            @else
                                                
                                                <a class="dropdown-item" href="{{ route('admin.users.status', ['user_id' => $user_details->id]) }}">
                                                    {{ tr('approve') }} 
                                                </a>
                                                   
                                            @endif

                                            <!-- @todo User Bookings -->

                                            <!-- @todo User Wishlist -->

                                            <!-- @todo User Reviews -->

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