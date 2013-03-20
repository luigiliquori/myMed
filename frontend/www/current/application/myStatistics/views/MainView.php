<!--
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
 -->
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
					<option value="all">Method</option>
					<option value="all">All</option>
					<option value="publish">Publish</option>
					<option value="subscribe">Subscribe</option>
					<option value="find">Find</option>
				</select>

				<!-- Year -->
				<label for="select-year">Year</label> 
				<select name="select-year" id="select-year">
					<option value="all">Year</option>
					<option value="all">All</option>
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
					<option value="all">Month</option>
					<option value="all">All</option>
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
					<option value="all">Application</option>
					<option value="all">All</option>
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
				$titl = $this->title;
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
			<center><h3><?php echo $titl;?></h3></center>
			
			
			<!-- GRAPH AREA -->
			
			<div Style="position: absolute; width: <?= $sizeGraph ?>%; height: <?= $height ?>px; border: thin black solid; margin-left:3.5%; background-color:white">
				<?php for($i=0;$i<$nbcol;$i++){
					$red = ceil(255-(255*$tabrep[$i+1])/$max_tab);
					$green = ceil((255*$tabrep[$i+1])/$max_tab);
					$height_column = ceil(($height * $tabrep[$i+1])/$max_tab);
					$top = $height - $height_column;
				?>
					
					<!-- ENTRY -->
					<div Style="position: absolute; top: <?= $top ?>px; left: <?= str_replace(",", ".",$i*$sizeBar) ?>%; width: <?= $sizeBar ?>%; height: <?= $height_column?>px; background-color:rgb(<?= $red ?>,<?= $green ?>,0); border: thin black solid;"><?php if($tabrep[$i+1] != 0){echo $tabrep[$i+1];}?></div>		
				
					<!-- LEGEND -->
					<div Style="position: absolute; top: <?= $top + $height_column + 20 ?>px; left: <?= str_replace(",", ".",$i*$sizeBar) ?>%; width: <?= $sizeBar ?>%; text-align: center;"><?= $i+1 ?></div>
				
				<?php }?>
			</div>
	</div>
	<?php }?>

	<!-- Footer page with tab bar ?action=main to highlight the tab -->
	<? print_footer_bar_main("?action=main"); ?>

</div>

<? include_once 'footer.php'; ?>
