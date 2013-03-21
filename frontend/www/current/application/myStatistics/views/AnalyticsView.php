<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
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
  	<button id="authorize-button" style="visibility:hidden;" data-inline="true" style="floating:right;"> Authorize Analytics</button>
  	
  	<!-- <form action="?action=analytics" name="form_day" onsubmit='return create_graph(document.forms["form_day"]["select_days"].value);' method="post">-->
  		<select name="select_days" id="select_days" data-inline="true" onchange='return create_visit_graph(document.getElementById("select_days").value);'>
  			<option value="30">days</option>
  			<option value="30">30</option>
  			<option value="60">60</option>
  			<option value="180">180</option>
  			<option value="365">365</option>
  		</select>

  <!-- Div element where the Line Chart will be placed -->
  <div id='visits_chart'></div>
  <div id='newold_chart'></div>

  <!-- Load all Google JS libraries -->
  <script src="https://www.google.com/jsapi"></script>
  <script src="javascript/gadash-1.0.js"></script>
  <script src="https://apis.google.com/js/client.js?onload=gadashInit"></script>
  
  
  <script>
    // Configure these parameters before you start.
    var API_KEY = 'AIzaSyCcsBJoy_euL30XW2U2jOk5PR_h5k20lAI';
    //var CLIENT_ID = '376803621438.apps.googleusercontent.com';
    var CLIENT_ID = '376803621438';
    var TABLE_ID = 'ga:59069891';
    // Format of table ID is ga:xxx where xxx is the profile ID.

    gadash.configKeys({
      'apiKey': API_KEY,
      'clientId': CLIENT_ID
    });

    // Create a new Chart that queries visitors for the last 30 days and plots
    // visualizes in a line chart.
    function create_visit_graph(days){
    	days = typeof days !== 'undefined' ? days : 30;
    	var chart1 = new gadash.Chart({
      		'type': 'LineChart',
      		'divContainer': 'visits_chart',
      		'last-n-days':days,
      		'query': {
        		'ids': TABLE_ID,
        		'metrics': 'ga:visitors',
        		'dimensions': 'ga:date'
      		},
      		'chartOptions': {
        		height:600,
        		title: 'Visits during the last '+days+' days',
        		hAxis: {title:'Date'},
        		vAxis: {title:'Visits'},
        		curveType: 'function'
      		}
    		}).render();
    }

    function create_new_vs_old_graph(){
        var chart2 =new gadash.Chart({
            'type':'PieChart',
            'divContainer': 'newold_chart',
            'query':{
                'ids': TABLE_ID,
            	'metrics': 'ga:visitors',
            	'dimensions':'ga:visitorType'
            },
            'chartOPtions': {
            }
        	}).render();
    }
    	create_visit_graph();
		create_new_vs_old_graph();
  </script>
		
	</div>

	<!-- Footer page with tab bar ?action=analytics to highlight the tab -->
	<? print_footer_bar_main("?action=analytics"); ?>
	
</div>

<? include_once 'footer.php'; ?>