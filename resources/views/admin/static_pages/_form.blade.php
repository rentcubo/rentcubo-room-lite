<div class="col-lg-12 grid-margin stretch-card">

    <div class="row flex-grow">

        <div class="col-12 grid-margin">

            <div class="card">

                @if(Setting::get('is_demo_control_enabled') == NO )

                    <form class="forms-sample" action="{{ route('admin.static_pages.save') }}" method="POST" enctype="multipart/form-data" role="form">

                @else

                    <form class="forms-sample" role="form">

                @endif 

                    @csrf
                
                    <div class="card-header bg-card-header ">

                        <h4 class="">
                            @if($static_page_details->id) 

                                {{tr('add_static_page')}}

                            @else
                                {{tr('edit_static_page')}}

                            @endif

                            <a class="btn btn-secondary pull-right" href="{{route('admin.static_pages.index')}}">
                                <i class="fa fa-eye"></i> {{tr('view_static_pages')}}
                            </a>
                        </h4>

                    </div>

                    <div class="card-body">

                        @if($static_page_details->id)

                            <input type="hidden" name="static_page_id" value="{{$static_page_details->id}}">

                        @endif

                        <div class="form-body">

                            <div class="row">

                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label for="title">{{tr('title')}}<span class="admin-required">*</span> </label>
                                        <input type="text" id="title" name="title" class="form-control" placeholder="Enter {{tr('title')}}" required  value="{{old('title')?: $static_page_details->title}}" onkeydown="return alphaOnly(event);">
                                    </div>
                                </div>

                                <div class="form-group col-md-6">

                                    <label for="page">
                                        {{tr('select_static_page_type')}}
                                        <span class="required" aria-required="true"> <span class="admin-required">*</span> </span>
                                    </label>
                                    
                                    <select class="form-control select2" name="type" required>
                                        <option value="">{{tr('select_static_page_type')}}</option>

                                        @foreach($static_keys as $value)

                                            <option value="{{$value}}" @if($value == $static_page_details->type) selected="true" @endif>{{ $value }}</option>

                                        @endforeach 
                                    </select>
                                    
                                </div>

                                 <div class="col-md-12"> 

                                    <div class="form-group">

                                        <label for="description">{{tr('description')}}<span class="admin-required">*</span></label>

                                        <textarea id="summernote" rows="5" class="form-control" name="description" placeholder="{{ tr('description') }}">{{old('description') ?: $static_page_details->description}}</textarea>

                                    </div>

                                </div>

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



