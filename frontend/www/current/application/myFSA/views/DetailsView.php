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
<? include("header.php");
?>
</head>
<body>
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

	<div data-role="header" data-theme="b" data-position="fixed">
		<h1 style="color: white;"><?= _("Details") ?></h1>
		<a href="?action=Search" data-inline="true" rel="external" data-role="button" data-icon="back"><?= _("Back")?></a>
		<? include_once "notifications.php"; ?>
	</div>
	
	<div data-role="content">
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
			<div data-role="collapsible" data-collapsed="false">
				<h3><?= _("Description") ?></h3>
				
				<!-- Reputation -->
				<div style="position: absolute; right: 24px;"><br>
					<div class="starsAverage"></div>
				</div>
				
			 <? if ($_SESSION['author'] == $_SESSION['user']->id){ ?>
					 <form action="index.php?action=publish" method="POST" data-ajax="false">
				 		<input type="hidden" name="method" value="Delete" />
						<input type="submit" data-icon="delete" data-theme="r" data-inline="true" data-mini="true" value="<?= _('Delete publication') ?>" />
				 	</form>
			 <? } ?> 	
		<?php  $truncated = substr($this->result->publisherID, 6)?>
	
				<h3><?= $_SESSION['pred3'] ?> :</h3>
				
				<b><?= _('Category') ?>:</b> <?= _($_SESSION['pred2']) ?><br><br>
				<b><?= _("Description")?></b>: <?= $this->result->data1 ?><br><br>
		 		<b><?= _('Author') ?>:</b> <?= $truncated ?>
		
			 	<div style="height: 50px;"></div>
			 	
			 	<div data-role="collapsible" data-theme="d">
			 		<? if(!isset($_SESSION['user'])  || (isset($_SESSION['user']) && $_SESSION['user']->is_guest)){ ?>
							<h3><?= _('Comments: <i>You have to be logged in to comment!</i>') ?></h3>
						<? }else{ ?>
			 				<h3><?= _('Comments') ?> </h3>
			 			<? } ?>
					<br/>
				 	<?foreach ($this->result_comment as $item) :?>
		 				<div data-role="collapsible" data-theme="b" data-content-theme="b" data-mini="true" data-collapsed="false">
		 					<h3><?= $item->publisherName ?></h3>
		 					<div class="ui-grid-a">
		 						<div class="ui-block-a">
		 							<img src="<?= $item->wrapped2 ?>" align=left alt="Your photo here" width="100px" height="100px"/>
		 						</div>
		 						<div class="ui-block-b"><?= $item->wrapped1 ?></div>
		 					</div>
		 				</div>
					<? endforeach ?>
					<? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) : ?>
				 	<!-- input for comments -->
				 	<form action="index.php?action=publish" method="POST" data-ajax="false">
				 		<textarea name="data2"></textarea>
				 		<input type="hidden" name="method" value="Comment" />
						<input type="submit" value="<?= translate('Comment') ?>" />
				 	</form>
			 		<? endif; ?>
			 	</div><br>
			</div>
		</div>	
	</div>
</div>
</body>



