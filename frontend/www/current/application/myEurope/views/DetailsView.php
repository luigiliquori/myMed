<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3empty(_('Details for the offer') ." ".$this->id) ?>
	</div>

	<div data-role="content" >
		<br />
		<?= $this->details->text ?>
		 
		 
		<? if (isset($this->details->user)) :?>
			<br /><br />
			<span style="font-size: 14px;"> <?= _('Partners list') ?>:</span>
			<? if (isset($this->details->authorProfile)) :?>
				<?= printProfile($this->details->authorProfile, $this->id, $this->details->user) ?>
			<? endif ?>
			
			<? if ($this->details->user == $_SESSION['user']->id) :?>

				<a href="?action=Details&id=<?= urlencode($this->id) ?>&namespace=<?= $_GET['namespace'] ?>" rel="external" type="button" data-inline="true" data-mini="true">  <?= _("Edit my offer") ?> </a>

				<a href="?action=Details&rm=&id=<?= urlencode($this->id) ?>&namespace=<?= $_GET['namespace'] ?>" rel="external" type="button" data-inline="true" data-mini="true" style="float:right;">  <?= _("Delete my offer") ?> </a>
			
			<? else :?>
				<a href="?action=Details&test=&id=<?= $this->details->user ?>&namespace=<?= $_GET['namespace'] ?>" rel="external" type="button" data-inline="true" data-mini="true" data-icon="check"> <?= _("Partnership request") ?> </a>
			<? endif ?>
		<? endif ?>
		
		 
	</div>
</div>

<? include("footer.php"); ?>