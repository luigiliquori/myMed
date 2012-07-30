<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3empty("Détails de l'offre ".$this->id) ?>
	</div>

	<div data-role="content" >
		<br />
		<?= $this->details->text ?>
		 
		 
		<? if (isset($this->details->user)) :?>
			<br />
		 	<br />
		 	<a href="?action=extendedProfile&id=<?= $this->details->user ?>" rel="external" type="button" data-inline="true" data-mini="true"> Profil de l'auteur </a>
		
			<? if ($this->details->user == $_SESSION['user']->id) :?>
				<br />
				<br />
				<a href="?action=Details&id=<?= urlencode($this->id) ?>&namespace=<?= $_GET['namespace'] ?>" rel="external" type="button" data-inline="true" data-mini="true"> Editer mon offre </a>
				<br />
				<br />
				<a href="?action=Details&rm=&id=<?= urlencode($this->id) ?>&namespace=<?= $_GET['namespace'] ?>" rel="external" type="button" data-inline="true" data-mini="true"> Supprimer mon offre </a>
			<? endif ?>		
		<? endif ?>
		
		 
	</div>
</div>

<? include("footer.php"); ?>