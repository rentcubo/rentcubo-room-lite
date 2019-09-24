@extends('layouts.admin') 

@section('title', tr('profile'))

@section('breadcrumb')

<li class="breadcrumb-item active" aria-current="page">
    <span>{{tr('profile')}}</span>
</li>

@endsection 

@section('content') 

<div class="row">

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ tr('update_profile') }}</h4>
                 
                @if(Setting::get('is_demo_control_enabled') == NO)

                <form action="{{(Setting::get('admin_delete_control') == ADMIN_CONTROL_ENABLED) ? '#' : route('admin.profile.save')}}" method="POST" enctype="multipart/form-data" role="form">

                @else       
            
                <form class="forms-sample" role="form">
                
                @endif

                    @csrf
                    
                    <input type="hidden" name="admin_id" value="{{ Auth::check() ? Auth::guard('admin')->user()->id : 0}}">

                    <div class="form-group">
                        <label ffor="name">{{tr('name')}}</label>
                        <input type="text" class="form-control" name="name" required id="name" placeholder="Enter {{tr('name')}}" value="{{old('name') ? old('name') : (Auth::guard('admin')->user() ? Auth::guard('admin')->user()->name : '')}}" pattern="[a-zA-Z0-9\s\-]{2,255}">
                    </div>

                    <div class="form-group">
                        <label for="email">{{tr('email')}}</label>
                        <input type="email" name="email" required class="form-control" id="email" placeholder="Enter {{tr('email')}}" value="{{old('email') ? old('email') : (Auth::guard('admin')->user() ? Auth::guard('admin')->user()->email : '')}}">
                    </div>

                    <div class="form-group">
                        <label for="picture">{{tr('picture')}}</label>
                        <input type="file" name="picture" class="form-control" id="picture" accept="image/*">
                    </div>

                    <button type="reset" class="btn btn-light">{{tr('reset')}}</button>

                    <button type="submit" class="btn btn-success ">
                        {{tr('submit')}}
                    </button>
                   
                </form>

            </div>

        </div>

    </div>

    <div class="col-md-6 d-flex align-items-stretch grid-margin">
        <div class="row flex-grow">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ tr('change_password')}}</h4>

                        <form class="form-horizontal" action="{{ (Setting::get('admin_delete_control') == YES ) ? '#' : route('admin.change.password')}}" method="POST" enctype="multipart/form-data" role="form">

                            @csrf

                            <div class="form-group">
                                <label for="old_password">{{tr('old_password')}}<span class="required" aria-required="true"> * </span></label>
                                <input type="password" required class="form-control" name="old_password" id="old_password" placeholder="Enter {{tr('old_password')}}" pattern=".{6,}" title="The old password must be 6 characters.">
                            </div>

                            <div class="form-group">
                                <label for="new_password">{{tr('new_password')}}<span class="required" aria-required="true"> * </span></label>
                                <input type="password" required class="form-control" name="password" id="new_password" placeholder="Enter {{tr('new_password')}}" pattern=".{6,}" title="The new password must be 6 characters.">
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">{{tr('confirm_password')}}<span class="required" aria-required="true"> * </span></label>
                                <input type="password" required class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Enter {{tr('confirm_password')}}" pattern=".{6,}" title="The confirm password must be 6 characters.">
                            </div>

                            <button type="reset" class="btn btn-light">
                                {{tr('reset')}}
                            </button>

                            @if(Setting::get('is_demo_control_enabled') == NO)
                                
                                <button type="submit" class="btn btn-success mr-2">
                                    {{ tr('submit') }}
                                </button>

                            @else

                                <button type="button" class="btn btn-success mr-2" disabled>{{ tr('submit') }}</button>
                                
                            @endif 

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection