<? include("header.php"); ?>
<link rel="stylesheet" href="jquery/jRating.jquery.css" type="text/css" />
<div data-role="page" data-theme="a">
<div data-role="header" data-theme="a">
<a data-rel="back" data-role="button"  data-icon="back">Back</a>
<h3>myFSA</h3>
</div>

<div class="star_rating" id="5"></div>
	
	<script type="text/javascript" src="jquery/jquery.js"></script>
	<script type="text/javascript" src="jquery/jRating.jquery.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){			
			$('.star_rating').jRating({
				length:5,
				//decimalLength:1,
				onSuccess : function(){
					//alert('Success : your rate has been saved :)');
				},
				onError : function(){
					//alert('Error : please retry');
				}
			});

		});
	</script>
	<div data-role="content" >
		<b>Author</b> : <?= $this->result->publisherID ?><br/>
		<b>Begin</b> : <?= $this->result->begin ?><br/>
		<b>End</b>: <?= $this->result->end ?><br/>
		<!--  <b>Pred1</b>: --><?//= $this->result->pred1 ?>
		<!--<b>Pred2</b>: --><?//= $this->result->pred2 ?>
		<!--<b>Pred3</b>: --><?//= $this->result->pred3 ?>
		<b>Wrapped1</b>: <?= $this->result->wrapped1 ?><br/>
		<b>Wrapped2</b>: <?= $this->result->wrapped2 ?><br/>
		<b>Data1</b>: <?= $this->result->data1 ?><br/>
		<b>Data2</b>: <?= $this->result->data2 ?><br/>
		<b>Data3</b>: <?= $this->result->data3 ?><br/>
	</div>
<? include("footer.php"); ?>
</div>