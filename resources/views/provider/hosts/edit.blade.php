@extends('layouts.provider')

@section('title', tr('edit_host'))

@section('content')

	@include('provider.hosts._form')

@endsection

@section('scripts')

<script src="{{ asset('admin-assets/node_modules/jquery-steps/build/jquery.steps.min.js')}}"></script>
 
<script src="{{ asset('admin-assets/node_modules/jquery-validation/dist/jquery.validate.min.js')}}"></script>

<script>
    $(document).ready(function() {
        jQuery('select[name="category_id"]').on('change',function(){

            var category_id = jQuery(this).val();
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