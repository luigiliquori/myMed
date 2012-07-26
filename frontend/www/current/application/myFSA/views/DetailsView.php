<? include("header.php"); ?>

	
<div data-role="page" data-theme="a">
<div data-role="header" data-theme="a">
<a data-rel="back" data-role="button"  data-icon="back">Back</a>
<h3>myFSA</h3>
<a href="?action=ExtendedProfile" data-icon="gear" class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
</div>

	<div data-role="content" >
		<b>Author</b> : <?= $this->result->publisherID ?><br/>
		<!--  <b>Pred1</b>: --><?//= $this->result->pred1 ?>
		<b>Cathegory</b>: <?= $this->result->pred2 ?><br/>
		<b>Title</b>: <?= $this->result->pred3 ?><br/>
				<b>field1</b> : <?= $this->result->begin ?><br/>
		<b>field2</b>: <?= $this->result->end ?><br/>
	<!--  	<b>Wrapped1</b>:--> <?//= $this->result->wrapped1 ?>
	<!-- 	<b>Wrapped2</b>: --><?//= $this->result->wrapped2 ?>
	 	<b>Content</b>: <?= $this->result->data1 ?><br/>
	<!-- 	<b>Data2</b>: --><?//= $this->result->data2 ?><br/>
	<!-- 	<b>Data3</b>: --><?//= $this->result->data3 ?>
	</div>
<? include("footer.php"); ?>
</div>