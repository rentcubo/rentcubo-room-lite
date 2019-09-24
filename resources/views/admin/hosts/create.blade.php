@extends('layouts.admin') 

@section('title', tr('add_host'))

@section('breadcrumb')

    <li class="breadcrumb-item">
    	<a href="{{ route('admin.hosts.index') }}">{{tr('hosts')}}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{tr('add_host')}}</span>
    </li>
           
@endsection 

@section('styles')

    <link rel="stylesheet" href="{{ asset('admin-assets/css/host.css')}} ">   

@endsection

@section('content')

    @include('admin.hosts._form')
   
@endsection

@section('scripts')

<script src="{{ asset('admin-assets/node_modules/jquery-steps/build/jquery.steps.min.js')}}"></script>
 
<script src="{{ asset('admin-assets/node_modules/jquery-validation/dist/jquery.validate.min.js')}}"></script>

<script src="{{ asset('admin-assets/js/wizard.js')}}"></script>

<script>

	$(document).ready(function() {

		$('#category_id').on('select2:select' , function (e) {

	    	var category_id = $(this).val();
	    	var sub_category_url = "{{route('admin.get_sub_categories')}}";

			var data = {'category_id' : category_id, _token: '{{csrf_token()}}'};

	    	var request = $.ajax({
							url: sub_category_url,
							type: "POST",
							data: data,
						});

			request.done(function(result) {

				if(result.success == true) {
					$("#sub_category_id").html(result.view);

					$("#sub_category_id").select2();
				}

			});

			request.fail(function(jqXHR, textStatus) {
			  	alert( "Request failed: " + textStatus );
			});

		});

	});
</script>

@endsection