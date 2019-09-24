@if(count($sub_categories) > 0)

	<option value="">{{tr('choose_sub_category')}}</option>

	@foreach($sub_categories as $sub_category_details)

		<option value="{{$sub_category_details->id}}" >{{$sub_category_details->name}}</option>

	@endforeach

@endif