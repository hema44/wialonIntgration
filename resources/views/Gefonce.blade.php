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
                                        <div id="map" style="height: 600px;"></div>
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
        var map;
        var geofence;
        const citymap = @json($items);
        var data =  @json($data);
        function initMap() {
            // Create the map.
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 4,
                center: { lat: Number(data["latitude"]), lng: Number(data["longitude"]) },
                mapTypeId: "terrain",
            });

            // Construct the circle for each value in citymap.
            // Note: We scale the area of the circle based on the population.
            for (city in citymap) {

                // Add marker to the zone
                var marker = new google.maps.Marker({
                    position:{ lat: citymap[city]["b"]["cen_y"], lng: citymap[city]["b"]["cen_x"] },
                    map: map,
                    icon: "https://gps.tawasolmap.com"+citymap[city]["icon"],
                    title:citymap[city]["n"]
                });

                // Add the circle for this city to the map.
                const cityCircle = new google.maps.Circle({
                    strokeColor: "#0c0c0c",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#3fbf09",
                    fillOpacity: 0.35,
                    map,
                    center: { lat: citymap[city]["b"]["cen_y"], lng: citymap[city]["b"]["cen_x"] },
                    radius: citymap[city]["p"][0]["r"],
                    marker: marker
                });
            }
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKXKdHQdtqgPVl2HI2RnUa_1bjCxRCQo4&callback=initMap" async defer></script>

@endsection
