<?php
require_once("header.php");
require_once("header-bar.php");
require_once("footer-bar.php");
?>

<!-- Main View of the statistic application -->
<div id="home" data-role="page" data-dom-cache="true">

	<!-- Header bar  with argument back_button and logout_button-->
	<?php print_header_bar(false, true) ?>

	<!--  Includes notification use $this->error="message" $this->success="message" -->
	<?php include('notifications.php'); ?>

	<!-- Main part of the page -->
	<div data-role="content">

		<!-- Parameters choice -->
		<form action="<?= url("main:generateGraph");?>" method="post" data-ajax="false">
			<fieldset data-role="controlgroup" data-type="horizontal" style="text-align:center">
				<!-- <legend>Statistics for:</legend>-->

				<!-- Method -->
				<label for="select-method">Method</label> 
				<select name="select-method" id="select-method">
					<option value="All">Method</option>
					<option value="All">All</option>
					<option value="Publish">Publish</option>
					<option value="Subscribe">Subscribe</option>
					<option value="Find">Find</option>
				</select>

				<!-- Year -->
				<label for="select-year">Year</label> 
				<select name="select-year" id="select-year">
					<option value="All">Year</option>
					<option value="All">All</option>
					<?php
					//all years from 2009 to now
					for ($i=2009;$i<=date('Y');$i++){
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
					?>
				</select>

				<!-- Month -->
				<label for="select-month">Month</label> 
				<select name="select-month" id="select-month">
					<option value="All">Month</option>
					<option value="All">All</option>
					<option value="1">January</option>
					<option value="2">February</option>
					<option value="3">March</option>
					<option value="4">April</option>
					<option value="5">May</option>
					<option value="6">June</option>
					<option value="7">July</option>
					<option value="8">August</option>
					<option value="9">September</option>
					<option value="10">October</option>
					<option value="11">November</option>
					<option value="12">December</option>
				</select>

				<!-- Applications -->
				<label for="select-application">Application</label> 
				<select name="select-application" id="select-application">
					<option value="All">Application</option>
					<option value="All">All</option>
					<!-- All applications from myMed panel -->
					<?php
					foreach (statistic\MainController::getBootstrapApplication() as $key => $app){
						echo '<option value="'.$app.'">'.$app.'</option>';
					}
					?>
				</select>
				<!-- Validate -->
				<input type="submit" button data-icon="check" data-iconpos="right" value="Validate" data-inline="true" />
			</fieldset>
		</form>

		<!-- Chart -->

		<!-- Plot graph -->
		<script type="text/javascript" language="javascript">
			//get informations about curves 
			<?php
				if($this->response!=""){
				//	echo 'var curve1= new Array('.implode(',',$curve1).');';
					$curveObj = json_decode($this->response);
					$curve = $curveObj->curve;
					echo 'var curve1='.$curve.';';
				}
				else{
					echo 'var curve1 = [[1,3],[2,7],[3,9],[4,1],[5,4],[6,6],[7,8],[8,2],[9,5],[10,1],[11,8],[12,3]];';
				}
			?>
			
			//create an array of curves 
			var serie = [curve1];
			//draw plot graph 
			createPlotGraph("conteneur",serie,"Pub/Sub requests",0);
		</script>
		<br /> 
		<br /> 
		<br />
		<div id="conteneur" data-role="content"></div>

	</div>

	<!-- Footer page with tab bar ?action=main to highlight the tab -->
	<? print_footer_bar_main("?action=main"); ?>

</div>

<? include_once 'footer.php'; ?>
