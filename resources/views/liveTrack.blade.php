@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('live tracking') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-10 mx-auto h-100">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="marker-map" style="height: 600px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("section")


    <script>
        function initMap() {

            var locations = @json($items);
            var markers = [];

            function updateMarkerLocation(marker, newLatLng) {
                marker.setPosition(newLatLng);
            }
            var map;
            
            if(locations[0].pos.y){
                 map = new google.maps.Map(document.getElementById('marker-map'), {
                    center: {
                        lat: locations[0].pos.y,
                        lng: locations[0].pos.x
                    },
                    zoom: 17
                });
            }else {
                 map = new google.maps.Map(document.getElementById('marker-map'), {
                    center: {
                        lat: 0,
                        lng: 0
                    },
                    zoom: 3
                });
            }


            for (var i = 0; i < locations.length; i++) {
                var location = locations[i];

                var marker = new google.maps.Marker({
                    icon: {
                        "url":"http://gps.tawasolmap.com"+location.uri,
                        "scaledSize" : {"width":40,"height":60}
                    },
                    position: { lat: location.pos.y, lng: location.pos.x },
                    map: map,
                    title: location.nm
                });

                markers.push(marker);

                var infoWindowContent = '<div>' +
                    '<h3>'+location.nm+'</h3>' +
                    '<tabale class="table table-borader">'+
                    '    <tr>'+
                    '        <td>km</td>'+
                    '        <td>'+location.cnm_km+'</td>'+
                    '    </tr>'+
                    '    <tr>'+
                    '        <td>speed</td>'+
                    '        <td>'+location.prms.speed.v+'</td>'+
                    '    </tr>'+
                    '</tabale>'+
                    '</div>';
                var infoWindow = new google.maps.InfoWindow({
                    content: infoWindowContent
                });

                google.maps.event.addListener(marker, 'mouseover', function() {
                    infoWindow.open(map, this);
                });
            }

            // Update marker location every 50 seconds
            setInterval(function() {
                $.ajax({
                    url: '{{"liveTrackingJson"}}',
                    method: 'GET',
                    success: function(response) {
                        var newLocations = response.items;

                        for (var i = 0; i < markers.length; i++) {
                            var marker = markers[i];
                            var newLocation = newLocations[i];

                            updateMarkerLocation(marker, new google.maps.LatLng(newLocation.pos.y, newLocation.pos.x));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ' - ' + error);
                    }
                });
            }, 10000);
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKXKdHQdtqgPVl2HI2RnUa_1bjCxRCQo4&callback=initMap" async defer></script>

@endsection
