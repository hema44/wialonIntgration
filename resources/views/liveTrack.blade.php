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
            var data =  @json($data);

            var markers = [];
            var infowindows = [];
            function updateMarkerLocation(marker, newLatLng) {
                marker.setPosition(newLatLng);
            }
            var map;


             map = new google.maps.Map(document.getElementById('marker-map'), {
                center: {
                     lat: Number(data["latitude"]), lng: Number(data["longitude"])
                },
                zoom: 10
            });


            for (var i = 0; i < locations.length; i++) {
                var location = locations[i];
                if(location.pos != null){
                    var marker = new google.maps.Marker({
                        icon: {
                            "url":"https://gps.tawasolmap.com"+location.uri,
                            "scaledSize" : {"width":40,"height":60}
                        },
                        position: { lat: location.pos.y, lng: location.pos.x },
                        map: map,
                        title: location.nm
                    });

                    var infowindow = new google.maps.InfoWindow();
                    // Show infowindow when marker is clicked
                    google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
                        return function() {
                            infowindow.setContent('<div id="markerDetails" style=""> ' +
                                '<h2>'+locations[i].nm+'</h2> ' +
                                '<table> ' +
                                '<tr> ' +
                                '<th>total_km</th> ' +
                                '<td id="markerTitle">'+ locations[i].cnm_km+'</td> ' +
                                '</tr> '+
                                '<tr> ' +
                                '<th>last Trip km</th> ' +
                                '<td >'+ locations[i].crt+'</td> ' +
                                '</tr> ' +
                                '</table> ' +
                                '</div>');
                            infowindow.open(map, marker);
                        }
                    })(marker, i));

                    google.maps.event.addListener(marker, 'mouseout', (function(marker, i) {
                        return function() {
                            infowindow.close(map, marker);
                        }
                    })(marker, i));

                    markers.push(marker);
                    infowindows.push(infowindow);
                }
            }

            // Update marker location every 50 seconds
            setInterval(function() {
                infowindows.forEach(function(infowindow) {
                    infowindow.close();
                });
                $.ajax({
                    url: '{{"liveTrackingJson"}}',
                    method: 'GET',
                    success: function(response) {
                        var newLocations = response.items;
                        for (var i = 0; i < newLocations.length; i++) {
                            var newLocation = newLocations[i];
                            var marker = markers[i];

                            if(newLocation.pos != null){
                                console.log("newLocation")
                                updateMarkerLocation(marker, new google.maps.LatLng(newLocation.pos.y, newLocation.pos.x));
                                infowindows[i].setContent('<div id="markerDetails" style=""> ' +
                                    '<h2>'+newLocation.nm+'</h2> ' +
                                    '<table> ' +
                                    '<tr> ' +
                                    '<th>total_km</th> ' +
                                    '<td id="markerTitle">'+ newLocation.cnm_km+'</td> ' +
                                    '</tr> '+
                                    '<tr> ' +
                                    '<th>last Trip km</th> ' +
                                    '<td >'+ newLocation.crt+'</td> ' +
                                    '</tr> ' +
                                    '</table> ' +
                                    '</div>');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:');
                    }
                });
            }, 10000);
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKXKdHQdtqgPVl2HI2RnUa_1bjCxRCQo4&callback=initMap" async defer></script>

@endsection
