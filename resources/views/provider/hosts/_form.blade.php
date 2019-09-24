<div class="main">

	<div class="site-content">

		
		
		<div class="top-bottom-spacing">

			<div class="single-book-nav">
                <a href="{{route('provider.hosts.index', ['provider_id' => Auth::guard('provider')->user()->id])}}" class="back-link"><i class="fas fa-chevron-left"></i> {{tr('back')}}
                </a>
            </div>

			<form class="forms-sample" id="example-form" action="{{ route('provider.hosts.save') }}" method="POST" enctype="multipart/form-data" role="form">

				@if($host_details->id)

					<input type="hidden" name="host_id" value="{{$host_details->id}}">

				@endif
				
				<input type="hidden" name="provider_id" id="provider_id" value="{{ Auth::guard('provider')->user()->id }}">

				@csrf

				<div class="panel">
					
					<div class="panel-heading text-uppercase">
						{{$host_details->id ? tr('edit_host') : tr('add_host')}}
					</div>

					<div class="panel-body">

						<div class="update-host-section">

							<h5 class="text-gray"><b>{{tr('host_details')}}</b></h5>
							<hr>


							<div class="form-group row">
										
								<div class="col-12">

							    	<label for="fname">{{ tr('name') }} *</label>

							    	<input id="host_name" name="host_name" type="text" class="form-control" value="{{old('host_name') ?: $host_details->host_name}}" placeholder="{{tr('host_name_placeholder')}}" required>
							    
							    </div>

							</div>

							<div class="row">

								<div class="col-md-4">

									<div class="form-group">

										<label for="host_type">{{tr('choose_host_type')}} *</label>

								    	<select class="form-control" id="host_type" name="host_type" required>

		                                    <option value="">{{tr('choose_host_type')}}</option>

		                                    @foreach($host_types as $host_type_details)
		                                        <option value="{{$host_type_details->value}}" @if($host_type_details->is_selected == YES) selected @endif>{{$host_type_details->value}}</option>
		                                    @endforeach
		                                    </option>
		                                </select>

		                            </div>

								</div>

								<div class="col-md-4">

									<div class="form-group">

										<label for="category">{{tr('choose_category')}} *</label>

								    	<select class="form-control" id="category_id" name="category_id" required>

		                                    <option value="">{{tr('choose_category')}}</option>

		                                    @foreach($categories as $category_details)
		                                    	<option value="{{$category_details->id}}" @if($category_details->is_selected == YES) selected @endif>{{$category_details->name}}</option>
		                                	@endforeach
		                                    </option>
		                                </select>

		                            </div>

								</div>

								<div class="col-md-4">

									<div class="form-group">

										<label for="sub_category_id">{{tr('choose_sub_category')}} *</label>

		                            	<select class="form-control" id="sub_category_id" name="sub_category_id" required>

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

						</div>

						<div class="update-host-section">

							<h5 class="text-gray text-uppercase"><b>{{tr('location_details')}}</b></h5>
							<hr>

							<div class="form-group row">
										
								<div class="col-12">

							    	<label for="full_address">{{tr('address')}} *</label>

	                            	<input id="full_address" name="full_address" type="text" class="form-control" value="{{old('full_address') ?: $host_details->full_address}}" placeholder="{{tr('host_address_placeholder')}}" required>
							    </div>
							    
							</div>

						</div>

						<div class="update-host-section">

							<h5 class="text-gray text-uppercase"><b>{{tr('upload_image')}} *</b></h5>
							<hr>
							
							<div class="form-group row">
										
								<div class="col-6">
							    	<label>{{tr('upload_image')}}</label>

	                        		<input type="file" class="form-control" name="picture" accept="image/*" @if(!$host_details->id) required @endif placeholder="{{tr('upload_image')}}">
							    </div>
							    
							</div>

						</div>

						<div class="update-host-section">

							<h5 class="text-gray text-uppercase"><b>{{tr('pricing')}}</b></h5>
							<hr>

							<div class="form-group row">
									
								<div class="col-4">
							    	<label>{{tr('total_guests')}}* </label>

	                            	<input type="number" name="total_guests" class="form-control" value="{{old('total_guests') ?: $host_details->total_guests}}" required>

							    </div>

							    <div class="col-4">

							    	<label>{{tr('base_price')}} *</label>

	                            	<input type="number" name="base_price" required class="form-control" value="{{old('base_price') ?: $host_details->base_price}}">
	                            	
							    </div>
						    
							</div>

						</div>
						
						<div class="update-host-section">

							<h5 class="text-gray text-uppercase"><b>{{tr('description')}}</b></h5>
							<hr>
							
							<div class="form-group row">
								
								<label for="simpleMde">{{ tr('description') }} *</label>

                    			<textarea class="form-control" required id="description" name="description" >{!! old('description') ?: strip_tags($host_details->description) !!}</textarea>

							</div>
											
							<div class="row">
								<div>
	 								<button type="submit" class="pink-btn">{{ tr('submit') }} </button>
	 							</div>
							
							</div>
						
						</div>

					</div>

				</div>

			</form>
			
		</div>
	</div>
</div>
