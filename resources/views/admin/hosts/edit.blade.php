@extends('layouts.admin')

@section('title', tr('edit_host'))

@section('breadcrumb')
	
    <li class="breadcrumb-item"><a href="{{ route('admin.hosts.index') }}">{{tr('hosts')}}</a></li>

    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{ tr('edit_host') }}</span>
    </li>
           
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

	/*var autocomplete;
    
    var location_latitude = document.getElementById('latitude');
    
    var location_longitude = document.getElementById('longitude');

    function geolocate() {
        
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('full_address')),
            {types: ['geocode']});

        autocomplete.addListener('place_changed', function(event) {

            var place = autocomplete.getPlace();

            if ( place.hasOwnProperty('place_id') ) {

                if (!place.geometry) {
                    
                    alert("Autocomplete's returned place contains no geometry");
                    document.getElementById('full_address').value = '';
                    return;
                }

                console.log('location');

                console.log(JSON.stringify(place));

                latitude = location_latitude.value = place.geometry.location.lat();

                longitude = location_longitude.value = place.geometry.location.lng();

                getLatLng(latitude, longitude)          
            } 

        });
    }

    function getLatLng(lat, lng) {

        geocoder = new google.maps.Geocoder();

        var latlng = new google.maps.LatLng(lat, lng);

        geocoder.geocode({
            'latLng': latlng
        }, function (results, status) {
            
            if (status == google.maps.GeocoderStatus.OK) {
                //console.log(results);
                if (results[1]) {
                    var indice = 0;
                    for (var j = 0; j < results.length; j++) {
                        if (results[j].types[0] == 'locality') {
                            indice = j;
                            break;
                        }
                    }
                    alert('The good number is: ' + j);
                    console.log(results[j]);
                    for (var i = 0; i < results[j].address_components.length; i++) {
                        if (results[j].address_components[i].types[0] == "locality") {
                            //this is the object you are looking for City
                            city = results[j].address_components[i];
                        }
                        if (results[j].address_components[i].types[0] == "administrative_area_level_1") {
                            //this is the object you are looking for State
                            region = results[j].address_components[i];
                        }
                        if (results[j].address_components[i].types[0] == "country") {
                            //this is the object you are looking for
                            country = results[j].address_components[i];
                        }
                    }

                    //city data
                    alert(city.long_name + " || " + region.long_name + " || " + country.short_name)


                } else {
                    alert("No results found");
                }
                //}
            
            } else {
                alert("Geocoder failed due to: " + results);
            }
        });
 	}*/

</script>

@endsection