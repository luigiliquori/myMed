<? include("header.php"); ?>

<script type="text/javascript">

	var average = 4;
	var votes = 0;
	var r = 0
	
  //method will run each time when evaluation will be set
  function setRank(rank){
	  
	  votes++;
	  setVotes(votes);
	  
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
	setVotes(votes);
	
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
		<b>Cathegory</b>: <?= $this->result->pred2 ?><br/>
		<b>Title</b>: <?= $this->result->pred3 ?><br/>
		<b>field1</b>: <?= $this->result->begin ?><br/>
		<b>field2</b>: <?= $this->result->end ?><br/>
	<!--  	<b>Wrapped1</b>:--> <?//= $this->result->wrapped1 ?>
	<!-- 	<b>Wrapped2</b>: --><?//= $this->result->wrapped2 ?>
	 	<b>Content</b>: <?= $this->result->data1 ?><br/>
	<!-- 	<b>Data2</b>: --><?//= $this->result->data2 ?><br/>
	<!-- 	<b>Data3</b>: --><?//= $this->result->data3 ?>
	</div>
<? include("footer.php"); ?>
</div>