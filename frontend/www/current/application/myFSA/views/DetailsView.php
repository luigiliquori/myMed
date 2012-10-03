<? include("header.php"); ?>

<script type="text/javascript">

	var average = 4;
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

<div data-role="page" data-theme="a">
	<div data-role="header" data-theme="a">
	<a data-rel="back" data-role="button"  data-icon="back">Back</a>
	<h3>myFSA</h3>
	<a href="?action=ExtendedProfile" data-icon="gear" class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
	</div>
	
	<!-- stars rating -->
rank:
	<div class="starsAverage"></div>
			nb of votes:
		<div class="votes"></div>
		<div class="yourRank">
			Your rank is:
		<div class="rankValue"></div>
	</div>

	<!-- stars rating -->
	
	<div data-role="content" >
		<b>Author</b> : <?= $this->result->publisherID ?><br/>
		<b>Cathegory</b>: <?= $_SESSION['pred2'] ?><br/>
		<b>Title</b>: <?= $_SESSION['pred3'] ?><br/>
		<b>field1</b>: <?= $this->result->begin ?><br/>
		<b>field2</b>: <?= $this->result->end ?><br/>
	<!--  	<b>Wrapped1</b>:--> <?//= $this->result->wrapped1 ?>
	<!-- 	<b>Wrapped2</b>: --><?//= $this->result->wrapped2 ?>
	 	<b>Content</b>: <?= $this->result->data1 ?><br/>
	 	<?
 	 	//needed for displaying comments with user
			$string = $this->result->data2;
			$_SESSION['data2'] = $this->result->data2;
			preg_match_all('/#([a-zA-Z0-9: \r\s]+)"/', $string, $this->comments);
			preg_match_all('/"([a-zA-Z0-9: ]+)"/', $string, $this->users);
			preg_match_all('/"([a-zA-Z0-9\_\/\.\&\:\+\-]+)#/', $string, $this->pictures);
			
 	 	///needed for displaying comments with user
	 	?>
	 	<!-- displaying comments -->	 	
	 	<?	if(isset($this->comments[1])) {	
	 	?>
	 	<b>comments:</b><br/>
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


