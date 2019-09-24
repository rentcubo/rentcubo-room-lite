
<div class="col-lg-12 grid-margin stretch-card">

    <div class="row flex-grow">

        <div class="col-12 grid-margin">

            <div class="card">

                @if(Setting::get('is_demo_control_enabled') == NO)

                <form class="forms-sample" action="{{ route('admin.providers.save') }}" method="POST" enctype="multipart/form-data" role="form">

                @else       
               
                <form class="forms-sample" role="form">
                
                @endif

                    @csrf

                    <div class="card-header bg-card-header">

                        <h4>{{tr('provider')}}</h4>

                    </div>

                    <div class="card-body">

                        <input type="hidden" name="provider_id" id="provider_id" value="{{ $provider_details->id }}">

                        <input type="hidden" name="login_by" id="login_by" value="{{ $provider_details->login_by ?: 'manual' }}">

                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="name">{{ tr('name') }} <span class="admin-required">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ tr('name') }}" value="{{ old('name') ?: $provider_details->name}}" required> 

                            </div>

                            <div class="form-group col-md-6">
                                <label for="mobile">{{ tr('mobile') }} <span class="admin-required">*</span></label>

                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="{{ tr('mobile') }}" value="{{old('mobile') ?: $provider_details->mobile}}" required>
                            </div>

                        </div>

                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="email">{{ tr('email')}} <span class="admin-required">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="{{ tr('email')}}" value="{{ old('email') ?: $provider_details->email}}" required>
                            </div>

                            @if(!$provider_details->id)

                            <div class="form-group col-md-6">
                                <label for="password">{{ tr('password') }} <span class="admin-required">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="{{ tr('password') }}">
                            </div>

                            @endif

                        </div>

                        <div class="row">

                            <div class="form-group col-md-6">

                                <label>{{tr('upload_image')}}</label>

                                <input type="file" name="picture" class="file-upload-default" accept="image/*">

                                <div class="input-group col-xs-12">

                                    <input type="text" name="picture" class="form-control file-upload-info" disabled placeholder="{{tr('upload_image')}}">

                                    <div class="input-group-append">
                                        <button class="file-upload-browse btn btn-info" type="button">{{tr('upload')}}</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="description">{{ tr('description') }} <span class="admin-required">*</span></label>
                                <textarea class="form-control" required id="description" name="description" >{{ old('description') ?: $provider_details->description}}</textarea>
                            </div>
                        </div>  

                    </div>

                    <div class="card-footer"> 

                        <button type="reset" class="btn btn-light">{{ tr('reset')}}</button>

                        @if(Setting::get('is_demo_control_enabled') == NO )

                            <button type="submit" class="btn btn-success mr-2">{{ tr('submit') }} </button>

                        @else

                            <button type="button" class="btn btn-success mr-2" disabled>{{ tr('submit') }}</button>
                            
                        @endif  

                    </div>

                </form>

            </div>
        </div>

    </div>
    
</div>