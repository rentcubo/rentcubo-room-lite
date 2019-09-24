@extends('layouts.admin') 

@section('title', tr('view_user'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{route('admin.users.index')}}">{{tr('users')}}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        <span>{{tr('view_user')}}</span>
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

                        <img class="card-img-top" src="{{$user_details->picture}}">
                        <a href="#!">
                            <div class="mask rgba-white-slight"></div>
                        </a>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6">

                                @if(Setting::get('is_demo_control_enabled') == YES)

                                    <a href="javascript:;" class="btn btn-primary btn-block">{{tr('edit')}}</a>

                                    <a href="javascript:;" class="btn btn-danger btn-block">{{tr('delete')}}</a>

                                @else

                                    <a class="btn btn-primary btn-block" href="{{ route('admin.users.edit', ['user_id' => $user_details->id])}}">{{tr('edit')}}</a>

                                    <a class="btn btn-danger btn-block" href="{{route('admin.users.delete', ['user_id' => $user_details->id])}}" onclick="return confirm(&quot;{{tr('user_delete_confirmation' , $user_details->name)}}&quot;);">{{tr('delete')}}</a>

                                @endif

                            </div>
                            
                            <div class="col-md-6">

                                @if($user_details->status == USER_APPROVED)

                                    <a class="btn btn-danger btn-block" href="{{ route('admin.users.status', ['user_id' => $user_details->id]) }}" onclick="return confirm(&quot;{{$user_details->first_name}} - {{tr('user_decline_confirmation')}}&quot;);" >
                                        {{ tr('decline') }} 
                                    </a>

                                @else
                                    
                                    <a class="btn btn-success btn-block" href="{{ route('admin.users.status', ['user_id' => $user_details->id]) }}">
                                        {{ tr('approve') }} 
                                    </a>
                                       
                                @endif

                            </div>

                            <!-- @todo User Bookings -->

                            <!-- @todo User Wishlist -->

                            <!-- @todo User Reviews -->

                        </div>

                        <hr>

                        <div class="row">

                            <h5 class="col-md-12">{{tr('description')}}</h5>

                            <p class="col-md-12 text-muted">{{$user_details->description}}</p>

                        </div>

                    
                    </div>
                </div>
                <!-- Card -->

                <!-- Card -->
                <div class="card mb-8">

                    <!-- Card content -->
                    <div class="card-body">

                        <div class="template-demo">

                            <table class="table mb-0">

                              <thead>
                               
                              </thead>

                              <tbody>

                                <tr>
                                    <td class="pl-0"><b>{{ tr('name') }}</b></td>
                                    <td class="pr-0 text-right"><div >{{$user_details->name}}</div></td>
                                </tr>

                                <tr>
                                    <td class="pl-0"><b>{{ tr('email') }}</b></td>
                                    <td class="pr-0 text-right"><div >{{$user_details->email}}</div></td>
                                </tr>

                                <tr>
                                    <td class="pl-0"><b>{{ tr('device_type') }}</b></td>
                                    <td class="pr-0 text-right"><div >{{$user_details->device_type}}</div></td>
                                </tr>

                                <tr>
                                    <td class="pl-0"><b>{{ tr('login_by') }}</b></td>
                                    <td class="pr-0 text-right"><div>{{ $user_details->login_by }}</div></td>
                                </tr>

                                <tr>
                                    <td class="pl-0"><b>{{ tr('register_type') }} </b></td>
                                    <td class="pr-0 text-right"><div>{{ $user_details->register_type }}</div></td>
                                </tr>

                                <tr>
                                    <td class="pl-0"><b>{{ tr('payment_mode') }} </b></td>
                                    <td class="pr-0 text-right"><div >{{$user_details->payment_mode}}</div></td>
                                </tr>

                                <tr>
                                    <td class="pl-0"> <b>{{ tr('timezone') }}</b></td>
                                    <td class="pr-0 text-right"><div>{{$user_details->timezone}}</div></td>
                                </tr>

                                <tr>

                                  <td class="pl-0"> <b>{{ tr('status') }}</b></td>

                                  <td class="pr-0 text-right">

                                        @if($user_details->status == USER_PENDING)

                                            <span class="card-text badge badge-danger badge-md text-uppercase">{{tr('pending')}}</span>

                                        @elseif($user_details->status == USER_APPROVED)

                                            <span class="card-text  badge badge-success badge-md text-uppercase">{{tr('approved')}}</span>

                                        @else

                                            <span class="card-text label label-rouded label-menu label-danger">{{tr('declined')}}</span>

                                        @endif

                                  </td>

                                </tr>

                                <tr>
                                    <td class="pl-0"> <b>{{ tr('created_at') }}</b></td>
                                    <td class="pr-0 text-right"><div>{{$user_details->created_at}}</div></td>
                                </tr>

                                <tr>
                                    <td class="pl-0"> <b>{{ tr('updated_at') }}</b></td>
                                    <td class="pr-0 text-right"><div>{{$user_details->updated_at}}</div></td>
                                </tr>

                              </tbody>

                            </table>

                        </div>
                        <!-- </div> -->

                    </div>
                    <!-- Card content -->

                </div>

                <!-- Card -->

                <!-- Card -->
              
                <!-- Card -->

            </div>
            <!-- Card group -->

        </div>

    </div>
@endsection