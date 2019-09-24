@extends('layouts.admin')

@section('title', tr('edit_service_location'))

@section('breadcrumb')
	
    <li class="breadcrumb-item"><a href="{{ route('admin.service_locations.index') }}">{{tr('service_locations')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{ tr('edit_service_location') }}</span>
    </li>
           
@endsection 

@section('content')

	@include('admin.service_locations._form')

@endsection


@section('scripts')

<script type="text/javascript">
    
    var autocomplete;
    var s_latitude = document.getElementById('latitude');
    var s_longitude = document.getElementById('longitude');

    function geolocate() {
        
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('my-dest')),
            {types: ['geocode']});

        autocomplete.addListener('place_changed', function(event) {

            var place = autocomplete.getPlace();

            if ( place.hasOwnProperty('place_id') ) {

                if (!place.geometry) {
                    
                    alert("Autocomplete's returned place contains no geometry");
                    document.getElementById('my-dest').value = '';
                    return;
                }

                s_latitude.value = place.geometry.location.lat();
                s_longitude.value = place.geometry.location.lng();            
            } 

        });
    }

</script>


@endsection