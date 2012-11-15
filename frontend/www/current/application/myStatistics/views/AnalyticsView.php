<?php

require_once("header.php");
require_once("header-bar.php");
require_once("footer-bar.php");

?>

<!-- Stat temporary View of the statistic application -->
<div id="stat" data-role="page" data-dom-cache="true">
	
	<!-- Header bar  with argument back_button and logout_button-->
	<?php print_header_bar(false, true) ?>
	
	<!--  Includes notification use $this->error="message" $this->success="message" -->
	<?php include('notifications.php'); ?>
	
	<!-- Main part of the page -->
	<div data-role="content" style="text-align: center;">
		<!-- Add Google Analytics authorization button -->
  <button id="authorize-button" style="visibility: hidden">
        Authorize Analytics</button>

  <!-- Div element where the Line Chart will be placed -->
  <div id='line-chart-example'></div>

  <!-- Load all Google JS libraries -->
  <script src="https://www.google.com/jsapi"></script>
  <script src="gadash-1.0.js"></script>
  <script src="https://apis.google.com/js/client.js?onload=gadashInit"></script>
  
  
  <script>
    // Configure these parameters before you start.
    var API_KEY = 'AIzaSyCcsBJoy_euL30XW2U2jOk5PR_h5k20lAI';
    var CLIENT_ID = '376803621438.apps.googleusercontent.com';
    var TABLE_ID = 'ga:59069891';
    // Format of table ID is ga:xxx where xxx is the profile ID.

    gadash.configKeys({
      'apiKey': API_KEY,
      'clientId': CLIENT_ID
    });

    // Create a new Chart that queries visitors for the last 30 days and plots
    // visualizes in a line chart.
    var chart1 = new gadash.Chart({
      'type': 'LineChart',
      'divContainer': 'line-chart-example',
      'last-n-days':30,
      'query': {
        'ids': TABLE_ID,
        'metrics': 'ga:visitors',
        'dimensions': 'ga:date'
      },
      'chartOptions': {
        height:600,
        title: 'Visits in January 2011',
        hAxis: {title:'Date'},
        vAxis: {title:'Visits'},
        curveType: 'function'
      }
    }).render();
  </script>
		
	</div>

	<!-- Footer page with tab bar ?action=analytics to highlight the tab -->
	<? print_footer_bar_main("?action=analytics"); ?>
	
</div>

<? include_once 'footer.php'; ?>