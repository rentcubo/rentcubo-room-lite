<div class="row">
    <div class="col-12 grid-margin">

        <div class="card card-outline-info">

            <div class="card-body">
                <h3 class="card-title">{{tr('add_host')}}</h3>

                <hr>

                <form class="forms-sample" id="example-form" action="{{ route('admin.hosts.save') }}" method="POST" enctype="multipart/form-data" role="form">

                    @csrf

                    <div>

                        <h3 class="text-uppercase">{{tr('host_details')}}</h3>

                        <section>

                            <!-- <h4>Basic Host details</h4> -->

                            <div class="row">
                                <input type="hidden" name="host_id" id="host_id" value="{{ $host_details->id }}">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="host_name">{{tr('host_name')}} *</label>

                                        <input id="host_name" name="host_name" type="text" class=" form-control" value="{{old('host_name') ?: $host_details->host_name}}" required>
                                    </div>
                                </div>

                                <div class=" col-md-6">
                                    <div class="form-group">

                                        <label for="host_type">{{tr('choose_host_type')}}</label>

                                        <select class="form-control select2" id="host_type" name="host_type" required>

                                            <option value="">{{tr('choose_host_type')}}</option>

                                            @foreach($host_types as $host_type_details)
                                                <option value="{{$host_type_details->value}}" @if($host_type_details->is_selected == YES) selected @endif>{{$host_type_details->value}}</option>
                                            @endforeach
                                    </option>
                                        </select>
                                    </div>
                                </div>

                                <div class=" col-md-6">
                                    <div class="form-group">

                                        <label for="category">{{tr('choose_category')}}</label>

                                        <select class="form-control select2" id="category_id" name="category_id" required>

                                            <option value="">{{tr('choose_category')}}</option>

                                            @foreach($categories as $category_details)
                                                <option value="{{$category_details->id}}" @if($category_details->is_selected == YES) selected @endif>{{$category_details->name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>       

                                <div class=" col-md-6">
                                    <div class="form-group">

                                        <label for="category">{{tr('choose_provider')}}</label>

                                        <select class="form-control select2" id="provider_id" name="provider_id" required>

                                            <option value="">{{tr('choose_provider')}}</option>

                                            @foreach($providers as $provider_details)
                                                <option value="{{$provider_details->id}}" @if($provider_details->is_selected == YES) selected @endif>{{$provider_details->name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class=" col-md-6">
                                    <div class="form-group">

                                        <label for="sub_category_id">{{tr('choose_sub_category')}}</label>

                                        <select class="form-control select2" id="sub_category_id" name="sub_category_id" required>

                                            <!-- Based on the category the sub categoris will load -->
                                            <option value="">{{tr('choose_sub_category')}}</option>

                                            @foreach($sub_categories as $i => $sub_category_details)

                                                <option value="{{ $sub_category_details->id }}" @if($sub_category_details->is_selected == YES) selected @endif> 

                                                    {{ $sub_category_details->name }}
                                               
                                                </option>

                                            @endforeach

                                        </select>
                               
                                    </div>
                               
                                </div>

                            </div>

                            <h4>{{tr('location')}}</h4><hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="full_address">{{tr('address')}} *</label>
                                        <input id="full_address" name="full_address" type="text" class="required form-control" value="{{old('full_address') ?: $host_details->full_address}}" required>
                                    </div>
                                </div>

                            </div>
                            
                        </section>

                        <h3 class="text-uppercase">{{tr('host_pricing')}}</h3>

                        <section>

                            <h4>{{tr('upload_image')}}</h4><hr>
                           
                            <div class="row">
                                <div class="col-md-6">

                                    <label>{{tr('upload_image')}} *</label>

                                    <input type="file" class="form-control" name="picture" accept="image/*" placeholder="{{tr('upload_image')}}" required>

                                </div>

                            </div>
                            <br>
                            <h4>{{tr('pricing')}}</h4><hr>
                            <div class="row">

                                <div class="col-md-4">

                                    <div class="form-group">

                                        <label>{{tr('total_guests')}}* </label>

                                        <input type="number" name="total_guests" class="form-control" value="{{old('total_guests') ?: $host_details->total_guests}}" min="0">

                                    </div>

                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label>{{tr('base_price')}} *</label>
                                        <input type="number" name="base_price" class="form-control" value="{{old('base_price') ?: $host_details->base_price}}" min="0" required>
                                    </div>

                                </div>
                            </div>
                        
                        </section>

                        <h3>{{tr('description')}}</h3>

                        <section>
                            <div class="form-group">

                                <label for="description">{{tr('description')}} *</label>

                                <textarea id="summernote" name="description" class="form-control" required>{{old('description') ?: $host_details->description}}</textarea>
                            </div>
                        </section>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

