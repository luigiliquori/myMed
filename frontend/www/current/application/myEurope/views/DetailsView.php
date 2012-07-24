<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3empty("Détails du projet ".$this->id) ?>
	</div>

	<div data-role="content" >
		 <?= $this->details->text ?>
	</div>
</div>

<? include("footer.php"); ?>