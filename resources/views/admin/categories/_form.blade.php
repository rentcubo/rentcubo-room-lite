<div class="col-lg-12 grid-margin stretch-card">

    <div class="row flex-grow">

        <div class="col-12 grid-margin">

            <div class="card">

                @if(Setting::get('is_demo_control_enabled') == NO )

                <form class="forms-sample" action="{{ route('admin.categories.save') }}" method="POST" enctype="multipart/form-data" role="form">

                @else

                <form class="forms-sample" role="form">

                @endif 

                    @csrf

                    <div class="card-header bg-card-header ">

                        <h4 class="">{{tr('category')}}

                            <a class="btn btn-secondary pull-right" href="{{route('admin.categories.index')}}">
                                <i class="fa fa-eye"></i> {{tr('view_categories')}}
                            </a>
                        </h4>

                    </div>

                    <div class="card-body">

                        <input type="hidden" name="category_id" id="category_id" value="{{ $category_details->id }}">

                        <div class="row">
                            
                            <div class="form-group col-md-6">
                                <label for="name">{{ tr('name') }} <span class="admin-required">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ tr('name') }}" value="{{ old('name') ?: $category_details->name }}" required>

                            </div>

                            <div class="form-group col-md-6">

                                <label>{{tr('upload_image')}} </label>

                                <input type="file" name="picture" class="file-upload-default" accept="image/*" >

                                <div class="input-group col-xs-12">

                                    <input type="text" class="form-control file-upload-info" disabled placeholder="{{tr('upload_image')}}" accept="image/*" >

                                    <div class="input-group-append">
                                        <button class="file-upload-browse btn btn-info" type="button">{{tr('upload')}}</button>
                                    </div>
                                </div>
                            
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">

                                <label for="simpleMde">{{ tr('description') }}</label>

                                <textarea class="form-control" id="description" name="description" >{{ old('description') ?: $category_details->description}}</textarea>

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