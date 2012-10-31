<? include("header.php");
?>

<script type="text/javascript">

	var average = '<?=$_SESSION['rank']?>';
	var votes = 0;
	var r = 0
	
  //method will run each time when evaluation will be set
  function setRank(rank){
	  
	  votes++;	  
	  var newValue = $(".Rate").text();		  
	  average = (average+parseInt(newValue))/votes;	  
	  $(".starsAverage").attr("id",average);  
	  $(".rankValue").append(rank)
	  
  }
  
  function setNewAvaregeStars(){

	  $(".starsAverage").empty();	  
	  $(".starsAverage").jRating({
		  showRateInfo : true,
		  step : true,
		  length : 5,
		  rateMax : 5,
		  onSuccess : function(rank){ setRank(rank)},
		});
	  
	  
  }

  	$(document).ready(function(){	
  	
	$(".starsAverage").attr("id",average);
	
    setNewAvaregeStars()
	
  });
</script>

<div data-role="page" data-theme="b">

<? include("header-bar.php"); ?>
	
	<!-- stars rating -->
<!-- rank: -->
	<!--  <div class="starsAverage"></div>-->
<!-- 			nb of votes: -->
<!-- 		<div class="votes"></div> -->
<!-- 		<div class="yourRank"> -->
<!-- 			Your rank is: -->
<!-- 		<div class="rankValue"></div> -->
<!-- 	</div> -->

	<!-- stars rating -->
	
	<div data-role="content">
	<?php  $truncated = substr($this->result->publisherID, 6)?>
	<!-- <div class="ui-grid-b">
		<div class="ui-block-a"><b><?= translate('Author') ?>:</b></div> <div class="ui-block-b"> <?= $truncated ?></div>
		<div class="ui-block-a"><b><?= translate('Cathegory') ?>:</b></div> <div class="ui-block-b"><?= $_SESSION['pred2'] ?></div>
		<div class="ui-block-a"><b><?= translate('Tittle') ?>:</b></div> <div class="ui-block-b"><?= $_SESSION['pred3'] ?></div>-->
		<!--  	<b>Wrapped1</b>:--> <?//= $this->result->wrapped1 ?>
		<!-- 	<b>Wrapped2</b>: --><?//= $this->result->wrapped2 ?>
	<!--  </div>-->
		<h3><?= $_SESSION['pred3'] ?></h3>
		<div class="starsAverage"></div>
		<div Style="position: relative; top: 10px;"><?= $this->result->data1 ?></div>
		<br>
 		<b><?= translate('Author') ?>:</b> <?= $truncated ?>
 		<br>
 		<b><?= translate('Cathegory') ?>:</b> <?= $_SESSION['pred2'] ?>
	 	
	 	<div data-role="collapsible" data-content-theme="d">
	 		<h3><?= translate('Comments') ?></h3>
		 	<!-- displaying comments -->
			<br/>
		 	<?
		 			foreach ($this->result_comment as $item){
		 	?>
		 				<div data-role="collapsible" data-theme="b" data-content-theme="b" data-mini="true" data-collapsed="false">
		 					<h3><?= $item->publisherName ?></h3>
		 					<div class="ui-grid-a">
		 					<div class="ui-block-a"><img src="<?= $item->wrapped2 ?>" align=left alt="Your photo here" width="100px" height="100px"/>
		 					</div>
		 						<div data-role="content">
		 							<?= $item->wrapped1 ?>
		 					</div>
		 					</div>
		 				</div>
		 	<?		}
		 	?>
		 	<!-- input for comments -->
		 	<form action="index.php?action=publish" method="POST" data-ajax="false">
		 		<textarea name="data2"></textarea>
		 		<input type="hidden" name="method" value="Comment" />
				<input type="submit" value="<?= translate('Comment') ?>" />
		 	</form>
		 	<!-- input for comments -->
	 	
	 	</div>
	 	
	<!-- 	<b>Data2</b>: --><?//= $this->result->data2 ?><br/>
	<!-- 	<b>Data3</b>: --><?//= $this->result->data3 ?>
	</div>
<? include("footer.php"); ?>
</div>


