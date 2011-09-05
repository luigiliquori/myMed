	function initMyTransport()
	{
		parent.jQuery('#myTransport')[0].contentWindow.jQuery('#from1').geo_autocomplete(new google.maps.Geocoder, {
			mapkey : 'Inserez_Votre_Clef_API_Ici',
			selectFirst : false,
			minChars : 3,
			cacheLength : 50,
			width : 300,
			scroll : true,
			scrollHeight : 330
		}).result(function(_event, _data) {
			if (_data)
				maCarte.fitBounds(_data.geometry.viewport);
		});

		parent.jQuery('#myTransport')[0].contentWindow.jQuery('#to1').geo_autocomplete(new google.maps.Geocoder, {
			mapkey : 'Inserez_Votre_Clef_API_Ici',
			selectFirst : false,
			minChars : 3,
			cacheLength : 50,
			width : 300,
			scroll : true,
			scrollHeight : 330
		}).result(function(_event, _data) {
			if (_data)
				maCarte.fitBounds(_data.geometry.viewport);
		});

		parent.jQuery('#myTransport')[0].contentWindow.jQuery('#from2').geo_autocomplete(new google.maps.Geocoder, {
			mapkey : 'Inserez_Votre_Clef_API_Ici',
			selectFirst : false,
			minChars : 3,
			cacheLength : 50,
			width : 300,
			scroll : true,
			scrollHeight : 330
		}).result(function(_event, _data) {
			if (_data)
				maCarte.fitBounds(_data.geometry.viewport);
		});

		parent.jQuery('#myTransport')[0].contentWindow.jQuery('#to2').geo_autocomplete(new google.maps.Geocoder, {
			mapkey : 'Inserez_Votre_Clef_API_Ici',
			selectFirst : false,
			minChars : 3,
			cacheLength : 50,
			width : 300,
			scroll : true,
			scrollHeight : 330
		}).result(function(_event, _data) {
			if (_data)
				maCarte.fitBounds(_data.geometry.viewport);
		});
	}
	function success(position) {
		var latlng = new google.maps.LatLng(position.coords.latitude,
				position.coords.longitude);

		var optionsCarte = {
			zoom : 12,
			center : latlng,
			mapTypeId : google.maps.MapTypeId.ROADMAP
		};

		var maCarte = new google.maps.Map(document.getElementById("mapcanvas"),
				optionsCarte);

		var marker = new google.maps.Marker({
			position: latlng, 
			map: maCarte, 
			title:"You are here!"
		});
		var myTransport	= parent.jQuery('#myTransport');
		if(myTransport.length > 0)
		{
			if(myTransport[0].contentWindow)
				initMyTransport();
			else
				myTransport[0].onload	= initMyTransport;
		}
	}

	function error(msg) {
		var s = document.getElementById('status');
		s.innerHTML = typeof msg == 'string' ? msg : "failed";
		s.className = 'fail';
	}

	function launchGeolocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(success, error);
		} else {
			error('Geolocation not supported');
		}
	}

