<div class="col-lg-12 grid-margin stretch-card">

    <div class="row flex-grow">

        <div class="col-12 grid-margin">

            <div class="card">

                @if(Setting::get('is_demo_control_enabled') == NO)

                <form class="forms-sample" action="{{ route('admin.sub_categories.save') }}" method="POST" enctype="multipart/form-data" role="form">

                @else

                <form class="forms-sample" role="form">

                @endif 

                    @csrf

                    <div class="card-header bg-card-header ">

                        <h4 class="">{{tr('sub_category')}}</h4>

                    </div>

                    <div class="card-body">

                        <input type="hidden" name="sub_category_id" id="sub_category_id" value="{{$sub_category_details->id}}">

                        <div class="row">

                            <div class="form-group col-md-6">

                                <label for="category">{{ tr('category') }} <span class="admin-required">*</span></label>

                                <select id="category_id" class="form-control select2" name="category_id" required>

                                    <option value="">{{ tr('select_category') }}</option>

                                    @foreach($categories as $i => $category_details)

                                        <option value="{{ $category_details->id }}" @if($category_details->is_selected == YES) selected @endif> {{ $category_details->name }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="form-group col-md-6">
                                <label for="name">{{ tr('name') }} <span class="admin-required">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ tr('name') }}" value="{{ old('name') ?: $sub_category_details->name}}" required>
                            </div>

                            

                            <div class="form-group col-md-6">

                                <label>{{tr('upload_image')}}</label>

                                <input type="file" name="picture" class="file-upload-default" accept="image/*">

                                <div class="input-group col-xs-12">

                                    <input type="text" name="img" class="form-control file-upload-info" disabled placeholder="{{tr('upload_image')}}" accept="image/*">

                                    <div class="input-group-append">
                                        <button class="file-upload-browse btn btn-info" type="button">{{tr('upload')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="description">{{ tr('description') }} </label>
                                <textarea class="form-control" id="description" name="description">{{ old('description') ?: $sub_category_details->description}}</textarea>
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