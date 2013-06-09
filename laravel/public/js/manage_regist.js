require([
], function() {
	// google map 실행
	function initialize() {
		var myLatlng = new google.maps.LatLng(35.81905,127.8733);
		var mapOptions = {
			zoom: 6,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

		google.maps.event.addListener(map, 'click', function(event) {
			placeMarker(event.latLng);
		});
		var markersArray = [];
		function placeMarker(location) {
			if(markersArray.length) clearOverlays();
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({
				'latLng' : location
			}, function(results, status){
				if( status == google.maps.GeocoderStatus.OK ) {
					$('#address').val(results[0].formatted_address);
				}
				else {
					alert("Geocoder failed due to: " + status);
				}
			});
			$('.lat').val(location.jb);
			$('.lng').val(location.kb);
			markersArray.push(new google.maps.Marker({
				position: location,
				icon: {
					path: google.maps.SymbolPath.CIRCLE,
					scale: 4,
					strokeWeight: 1,
					strokeColor: '#652610',
					fillColor: '#e7641c',
					fillOpacity: 1
				},
				draggable: true,
				map: map
			}));
		}
		function clearOverlays() {
			if (markersArray) {
				for (i in markersArray) {
					markersArray[i].setMap(null);
				}
			}
		}
	}
	initialize();
});