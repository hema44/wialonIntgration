<!DOCTYPE html>
<html>
<head>
    <title>Marker with InfoWindow and Table</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
</head>
<body>
<div id="map" style="width: 100%; height: 400px;"></div>

<script>
    var map;
    var markers = [];
    var infowindows = [];

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat:  37.7749, lng: -122.4194 },
            zoom: 10
        });

        var markerData = [
            { lat: 37.7749, lng: -122.4194, title: 'Marker 1', description: 'Data for Marker 1' },
            { lat: 34.0522, lng: -118.2437, title: 'Marker 2', description: 'Data for Marker 2' },
            // Add more marker data here
        ];

        markerData.forEach(function(data) {
            var marker = new google.maps.Marker({
                position: { lat: data.lat, lng: data.lng },
                map: map,
                title: data.title
            });

            var infowindow = new google.maps.InfoWindow();

            marker.addListener('click', function() {

                var content ='<div id="markerDetails" style=""> ' +
                    '<h2>'+data.title+'</h2> ' +
                    '<table> ' +
                    '<tr> ' +
                    '<th>total_km</th> ' +
                    '<td id="markerTitle">'+ data.title+'</td> ' +
                    '</tr> '+
                    '<tr> ' +
                    '<th>last Trip km</th> ' +
                    '<td >'+ data.description+'</td> ' +
                    '</tr> ' +
                    '</table> ' +
                    '</div>';
                infowindow.setContent(content);
                infowindow.open(map, marker);
            });

            markers.push(marker);
            infowindows.push(infowindow);
        });
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKXKdHQdtqgPVl2HI2RnUa_1bjCxRCQo4&callback=initMap"></script>
</body>
</html>
