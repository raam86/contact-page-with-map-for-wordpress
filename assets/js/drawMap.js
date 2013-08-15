(function($){

 	$.fn.extend({ 
 		
		//pass the options variable to the function
 		SGmap: function(options) {

			//Set the default values, use comma to separate the settings, example:
			var defaults = {
				baseURI: '',
				imageURL: '',
				addDetails: '',
				zLevel: '',
				headerImage: '',
				Mtitle: ''
			}
				
			var options =  $.extend(defaults, options);

    		return this.each(function() {
				var o = options;
				var obj = $(this);
				var o = {
					addr       : obj.data('addr') || options.addr,
					imageURL   : obj.data('iu') || options.imageURL,
					addDetails : obj.data('ad') || options.addDetails,
					zLevel     : obj.data('zoom') || options.zLevel,
					headerImage: obj.data('hi') || options.headerImage,
					Mtitle     : obj.data('Mtitle') || options.Mtitle
				}
				console.log(o);
				place       = o.addr.replace(/\s/g, '+'),
				o.baseURI     = 'http://maps.googleapis.com/maps/api/geocode/json?address=' + place + '&sensor=true';
				var zztop = o.zLevel;
				jQuery.get(o.baseURI, function (data) {
					var map;
					var latLng;
					function makeMap(lat, lng, zztop) {
						latLng = new google.maps.LatLng(lat,lng);
						var mapOptions = {
							zoom: o.zLevel,
							center: latLng,
							mapTypeId: google.maps.MapTypeId.ROADMAP
						};
						map = new google.maps.Map(obj[0],mapOptions);
						console.log(data);
					}
					var image = {
						url:  o.imageURL,
						size: new google.maps.Size(30, 30),
						origin: new google.maps.Point(0,0),
						anchor: new google.maps.Point(0, 0)
					}
					var loc = data.results[0].geometry.location;
					makeMap(loc.lat, loc.lng, 19, o.zLevel);

					var marker = new google.maps.Marker({
						position: latLng,
						title: o.Mtitle,
						icon: image || ''
					});

					var contentString = o.addDetails;
					var infowindow = new google.maps.InfoWindow({
						content: '<h2 class="info_window_title">' + contentString + '</h2>' + "<img class='info_window_image' src='" + o.headerImage + "'/>"
					});

					infowindow.open(map,marker);
					marker.setMap(map);
				});
    		});
    	}
	});

})(jQuery);
jQuery(document).ready(function($){
	$(".SGMAP").SGmap();
});