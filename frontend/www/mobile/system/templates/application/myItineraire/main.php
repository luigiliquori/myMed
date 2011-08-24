<!DOCTYPE html> 
<html> 
	<head profile="http://dublincore.org/documents/dcq-html/">
	
		<title>myItineraire</title>
		<meta name="keywords" content="Google maps, jQuery, plugin, mobile, iphone, ipad, android, HTML5, Geo search, Google direction" />
		<meta name="description" content="Geo directions example with jQuery mobile, Google maps and HTML5" />
		<meta http-equiv="content-language" content="en"/>
		
		<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
		<meta name="DC.title" content="jQuery mobile with Google maps geo search example" />
		<meta name="DC.subject" content="Google maps;jQuery;plugin;mobile;iphone;ipad;android;HTML5;Geo search;Google direction;" />
		<meta name="DC.description" content="Geo directions example with jQuery mobile, Google maps and HTML5" />
		<meta name="DC.creator" content="Johan S&auml;ll Larsson" />
		<meta name="DC.language" content="en"/>
		
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a4/jquery.mobile-1.0a4.min.css" />
		<style rel="stylesheet">
			body {  background: #ddd; }
			.ui-body-c a.ui-link { color: #008595; font-weight: bold; text-decoration: none; }
			.hidden { display:none; }
			.min-width-480px label.ui-input-text { font-weight:bold; display: block; }
			.adp-directions { width:100%; }
			.adp-placemark, .adp-summary, .adp-legal { display:none; margin: 0; }
			.adp-placemark, .adp-step, .adp-stepicon, .adp-substep{ border-top: none;text-align:center; vertical-align: middle; padding: 0.8em 0; background:#e9eaeb;color:#3e3e3e;text-shadow:0 1px 1px #fff;background-image:-moz-linear-gradient(top,#f0f0f0,#e9eaeb);background-image:-webkit-gradient(linear,left top,left bottom,color-stop(0,#f0f0f0),color-stop(1,#e9eaeb));-ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorStr='#f0f0f0', EndColorStr='#e9eaeb')"}
			.adp-directions tr { border:1px solid #b3b3b3; }
			h2 { font-size: 16px; overflow: hidden; white-space: nowrap; display: block; }
			.more { text-align: center; }
		</style>
		<script src="http://www.google.com/jsapi?key=ABQIAAAAahcO7noe62FuOIQacCQQ7RTHkUDJMJAZieEeKAqNDtpKxMhoFxQsdtJdv3FJ1dT3WugUNJb7xD-jsQ" type="text/javascript"></script>        
        <script type="text/javascript">
			google.load("maps", "3", {'other_params':'sensor=true'});
			google.load("jquery", "1.5");
		</script>
		<script type="text/javascript">
			// Demonstration purpose only...
			$(document).bind("mobileinit", function () {
				$.mobile.ajaxEnabled  = true;
			});
		</script>
		<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a4/jquery.mobile-1.0a4.min.js"></script>
		<script type="text/javascript" src="jquery-ui-map/ui/jquery.ui.map.js"></script>
	</head> 

	<body> 

		<div id="gmap-3" data-role="page">

			<div data-role="header">
				<h1>myItineraire</h1>
			</div>

			<script type="text/javascript">
				$('#gmap-3').live("pageshow", function() {
					$('#map_canvas_1').gmap({'center': getLatLng()});
					function getLatLng() {
						if ( google.loader.ClientLocation != null ) {
							return new google.maps.LatLng(google.loader.ClientLocation.latitude, google.loader.ClientLocation.longitude);	
						}
						return new google.maps.LatLng(59.3426606750, 18.0736160278);
					}
				});
				// To stop the click from looping into nonsense
				$('#gmap-3').live("pagecreate", function() {
					$('#submit').click(function() {
						$('#map_canvas_1').gmap('displayDirections', { 'origin': $('#from').val(), 'destination': $('#to').val(), 'travelMode': google.maps.DirectionsTravelMode.DRIVING }, { 'panel': document.getElementById('directions')}, function(success, response) {
							if ( success ) {
								$('#results').show();
							} else {
								$('#map_canvas_1').gmap('getService', 'DirectionsRenderer').setMap(null);
								$('#results').hide();
							}
						});
						return false;
					});
				});
			</script>
			<div data-role="content">
				
				<div class="ui-bar-c ui-corner-all ui-shadow" style="padding:1em;">
					
					<div id="map_canvas_1" style="height:300px;"></div>
				
					<p>
						<label for="from">From</label>
						<input id="from" class="ui-bar-c" value="Nice" />
					</p>
					<p>
						<label for="to">To</label>
						<input id="to" class="ui-bar-c" value="Turin" />
					</p>
				
					<a id="submit" href="#" data-role="button" data-icon="search">Get directions</a>
				</div>
				
				<div id="results" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" style="display:none;">
					<div class="ui-li ui-li-divider ui-btn ui-bar-b ui-corner-top ui-btn-up-undefined">Results</div>
					<div id="directions"></div>
					<div class="ui-li ui-li-divider ui-btn ui-bar-b ui-corner-bottom ui-btn-up-undefined"></div>
				</div>

			</div>
			
			<!-- FOOTER_PERSITENT-->
			<div data-role="footer" data-position="fixed" data-theme="a">
				<div data-role="navbar">
					<ul>
					<li><a href="?application=0" rel="external">Close</a></li>
					</ul>
				</div>
			</div>
			
		</div>
		<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
			try {
				var pageTracker = _gat._getTracker("UA-17614686-3");
				pageTracker._trackPageview();
			} catch(err) {}
		</script>
	</body>
	
</html>