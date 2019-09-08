<!-- author: Antiques Promotion (http://www.antiquespromotion.ca) -->

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<{$api_key}>&callback=initMap" type="text/javascript"></script>

<script type="text/javascript">
    var map;
    var geocoder;
    var marker;

    function GMinit() {
        var address = window.opener.document.MainForm.location.value;
        var latitude = window.opener.document.MainForm.gmlat.value;
        var longitude = window.opener.document.MainForm.gmlong.value;
        var zoom = parseInt(window.opener.document.MainForm.gmzoom.value);
        if (zoom == 0) {zoom = 10;}
        var myLatlng = new google.maps.LatLng(latitude, longitude);
        var myOptions = {
            zoom: zoom,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var bounds = new google.maps.LatLngBounds();

        map = new google.maps.Map(document.getElementById("googlemap"), myOptions);
        geocoder = new google.maps.Geocoder();
        marker = new google.maps.Marker({
            position: myLatlng,
            draggable: true,
            map: map
        });

        if (address != '') {
            moveMap(address);
            document.getCoords.address.value = address;
        }

        google.maps.event.addListener(map, 'center_changed', function () {
            marker.setPosition(map.getCenter());
            var location = marker.getPosition();
            document.getElementById("lat").innerHTML = location.lat();
            document.getElementById("lon").innerHTML = location.lng();
        });
        google.maps.event.addListener(map, 'zoom_changed', function () {
            document.getElementById("zoom").innerHTML = map.getZoom();
        });
        google.maps.event.addListener(marker, 'dragend', function () {
            map.setCenter(marker.getPosition());
        });

        document.getElementById("zoom").innerHTML = map.getZoom();
        document.getElementById("lat").innerHTML = marker.getPosition().lat();
        document.getElementById("lon").innerHTML = marker.getPosition().lng();
    }

    function moveMap(address) {
        geocoder.geocode({'address': address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                marker.setPosition(results[0].geometry.location);
            }
            
            
        });
    }

    function sendCoords() {
        self.opener.document.MainForm.location.value =  document.getCoords.address.value;
        self.opener.document.MainForm.gmlat.value = marker.getPosition().lat();
        self.opener.document.MainForm.gmlong.value = marker.getPosition().lng();
        self.opener.document.MainForm.gmzoom.value = map.getZoom();
        window.close();
    }

    window.onload = GMinit;
</script>
<form id="getCoords" class='apcalForm' name="getCoords" action="" method="post">
    <input type="text" id="address" name="address"/>
    <input type="button" name="search" value="<{$smarty.const._APCAL_TH_GETCOORDS}>" onclick="moveMap(document.getCoords.address.value);"/>
    <input type="button" name="getLatLngZoom" value="<{$smarty.const._MD_APCAL_MAP_APPLY}>" onclick="sendCoords();"/>
    <input type="button" name="getLatLngZoom" value="<{$smarty.const._CANCEL}>" onclick="javascript:window.close();"/>
</form>
<div id="googlemap" style="width: 100%; height: 70%;"></div>
latitude:<span id="lat"></span><br>
longitude:<span id="lon"></span><br>
zoom level: <span id="zoom"></span>
