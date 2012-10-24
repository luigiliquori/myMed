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
	<div class="starsAverage"></div>
<!-- 			nb of votes: -->
<!-- 		<div class="votes"></div> -->
<!-- 		<div class="yourRank"> -->
<!-- 			Your rank is: -->
<!-- 		<div class="rankValue"></div> -->
<!-- 	</div> -->

	<!-- stars rating -->
	
	<div data-role="content">
		<b>Auteur</b> : <?= $this->result->publisherID ?><br/>
		<b>Catégorie</b>: <?= $_SESSION['pred2'] ?><br/>
		<b>Titre</b>: <?= $_SESSION['pred3'] ?><br/>
	<!--  	<b>Wrapped1</b>:--> <?//= $this->result->wrapped1 ?>
	<!-- 	<b>Wrapped2</b>: --><?//= $this->result->wrapped2 ?>
	 	<b>Text</b>: <?= $this->result->data1 ?><br/>
	 	<?
 	 	//needed for displaying comments with user
			$string = $this->result->data2;
			$_SESSION['data2'] = $this->result->data2;
			preg_match_all('/#([a-zA-Z0-9 \r\s\~\`\!\@\$\%\^\&\*\(\)\_\-\+\=\{\}\[\]\:\;\'\<\,\>\.\?\/]+)"/', $string, $this->comments);
			preg_match_all('/"([a-zA-Z0-9: ]+)"/', $string, $this->users);
			preg_match_all('/"([a-zA-Z0-9\~\`\!\@\$\%\^\&\*\(\)\_\-\+\=\{\}\[\]\:\;\'\<\,\>\.\?\/]+)#/', $string, $this->pictures);
			
 	 	///needed for displaying comments with user
	 	?>
	 	<!-- displaying comments -->	 	
	 	<?	if(isset($this->comments[1])) {	
	 	?>
	 	<b>Commentaires:</b><br/>
	 	<?
	 			foreach ($this->comments[1] as  $key=>$value){
	 	?>
	 				<div class="ui-grid-a">
	 				<div class="ui-block-a"><img src="<?=$this->pictures[1][$key]?>" align=left alt="Your photo here" width="100px" height="100px"/>
	 				<b><?= $this->users[1][$key] ?></b> <?= $this->comments[1][$key] ?></div>
	 				</div>
	 	<?		}
	 		}
	 	?>
	 	<!-- place for comments -->
	 	<form action="index.php?action=publish" method="POST" data-ajax="false">
	 		<textarea name="data2"></textarea>
	 		<input type="submit" name="method" value="Comment"/>
	 	</form>
	 	<!-- place for comments -->
	 	
	<!-- 	<b>Data2</b>: --><?//= $this->result->data2 ?><br/>
	<!-- 	<b>Data3</b>: --><?//= $this->result->data3 ?>
	</div>
<? include("footer.php"); ?>
</div>


