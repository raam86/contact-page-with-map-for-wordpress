function drawMap(baseURI,imageURL,addDetails,zLevel,headerImage){
	console.log(arguments)
	$.get(baseURI, function (data) {
		var map;
		var latLng;
		function makeMap(lat, lng, zLevel) {
			latLng = new google.maps.LatLng(lat,lng);
			var mapOptions = {
				zoom: zLevel,
				center: latLng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById('map-canvas'),
				mapOptions);
			console.log(data);
		}
		var image = {
			url:  imageURL,
			size: new google.maps.Size(30, 30),
			origin: new google.maps.Point(0,0),
			anchor: new google.maps.Point(0, 0)
		}
		var loc = data.results[0].geometry.location;
		makeMap(loc.lat, loc.lng, 19, zLevel);
		var marker = new google.maps.Marker({
			position: latLng,
			title:'עו"ד ארז צבאג',
			icon: image
		});
		var contentString = addDetails;
		var infowindow = new google.maps.InfoWindow({
			content: '<h2 style="" class="info_window_title">' + contentString + '</h2>' + "<img class='info_window_image' src='" + headerImage + "'/>"
		});
		infowindow.open(map,marker);
		marker.setMap(map);
	});
}