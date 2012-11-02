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
	<div data-role="content" >

		<!-- Parameters choice -->
		<form action="?action=main" method="post" data-ajax="false">
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
					foreach (MainController::getBootstrapApplication() as $key => $app){
						echo '<option value="'.$app.'">'.$app.'</option>';
					}
					?>
				</select>
				<!-- Validate -->
				<input type="submit" button data-icon="check" data-iconpos="right" value="Validate" data-inline="true" />
			</fieldset>
			<input type="hidden" name="first-select-curve" id="first-select-curve" value="ok"/>
		</form>

		<!-- Chart -->
		<!-- Bar graph -->
		<?php if(isset($this->array_resp_return)){?>
		<?php 
				$sizeGraph = 90;
				$height = 500;
		?>
			<?php
				//title of the graph
				$title = "test";
				//column values
				$tabrep = $this->array_resp_return;
				//$tabrep=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
				//column name when I will find how to write vertically
				$tabinfo=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
				//max value of the array
				//$max_tab=31;
				$max_tab = $this->max_array_value;
				
				$nbcol = count($tabrep);
				$sizeBar = 100/$nbcol;
				$sizeBar = str_replace(",", ".", $sizeBar);
			?>
			<center><h1><?php echo $title;?></h1></center>
			<div Style="position: absolute; width: <?= $sizeGraph ?>%; height: <?php echo $height ?>px; border: thin black solid; margin-left:3.5%; background-color:white">
				<?php for($i=0;$i<$nbcol;$i++){
					$red = ceil(255-(255*$tabrep[$i])/$max_tab);
					$green = ceil((255*$tabrep[$i])/$max_tab);
					$height_column = ceil(($height * $tabrep[$i])/$max_tab);
					$top = $height - $height_column;
				?>
					<div Style="position: absolute; top: <?php echo $top ?>px; left: <?= str_replace(",", ".",$i*$sizeBar) ?>%; width: <?= $sizeBar ?>%; height: <?php echo $height_column?>px; background-color:rgb(<?php echo $red ?>,<?php echo $green ?>,0); border: thin black solid;"></div>		
				<?php }?>
			</div>
	</div>
	<?php }?>

	<!-- Footer page with tab bar ?action=main to highlight the tab -->
	<? print_footer_bar_main("?action=main"); ?>

</div>

<? include_once 'footer.php'; ?>
