@extends('provider.account.layout')

@section('account-content')
	
	<div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-9">

		<form class="forms-sample" action="{{ route('provider.update_profile_save') }}" method="POST" enctype="multipart/form-data" role="form">
			@csrf

			<div class="panel">

				<div class="panel-heading">{{ tr('edit_profile') }} </div>

				<input type="hidden" name="provider_id" id="provider_id" value="{{$provider_details->id}}">

				<div class="panel-body">
					<!-- firstname -->
					<div class="form-group row">
						<div class="col-3 text-right">
				    	<label for="fname">{{ tr('name') }} </label>
				    </div>
				    <div class="col-9">
				    	<input type="text" class="form-control" id="name" name="name" placeholder="{{ tr('name') }}" value="{{ old('name') ?: $provider_details->name}}">
				    </div>
				</div>
				<!-- email -->
				<div class="form-group row">
						<div class="col-3 text-right">
				    	<label for="email">{{ tr('email') }} </label>
				    </div>
				    <div class="col-9">
				    	<input type="email" class="form-control" id="email" name="email" placeholder="{{ tr('email')}}" value="{{ old('email') ?: $provider_details->email}}" required>
				    </div>
				</div>
				<!-- number -->
				<div class="form-group row">
						<div class="col-3 text-right">
				    	<label for="number">{{ tr('mobile') }}</label>
				    </div>
				    <div class="col-9">
				    	<input type="text" class="form-control" id="mobile" name="mobile" placeholder="{{ tr('mobile') }}" value="{{ old('mobile') ?: $provider_details->mobile}}" required>
				    </div>
				</div>
				
				<div class="form-group row">
						<div class="col-3 text-right">
				    	<label for="number">{{ tr('upload_image') }}</label>
				    </div>
				    <div class="col-9">
				    	<input type="file" name="picture" id="picture" class="form-control" placeholder="{{ tr('mobile') }}"accept="image/*">
				    </div>
				</div>
						
				<div class="form-group row">
						<div class="col-3 text-right">
				    	<label >describe yourself</label>
				    </div>
				    <div class="col-9">
				    	<textarea type="text" class="form-control" id="description" name="description" rows="4">{{ old('description') ?: $provider_details->description}}</textarea>	
				    </div>
				</div>
						
				<div class="row">
					<div class="col-9 offset-3">
							<button type="submit" class="pink-btn">{{ tr('submit') }} </button>
						</div>
				</div>
				
				</div>
			</div>
		</form>
	
	</div>
	
@endsection