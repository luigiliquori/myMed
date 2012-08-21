<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3empty(_('Partnership details') ." ".$this->id) ?>
		<? include("notifications.php"); ?>
	</div>

	<div data-role="content" >
	
		<div class="ui-li-aside" data-role="controlgroup" style="width:auto;" data-type="horizontal" data-mini="true">
			<a data-role="button" style="color:gray;" data-icon="minus" onclick="rate(0, '<?= $this->id ?>', '<?= $this->id ?>');"><?= $this->reputation['down'] ?></a>
			<a data-role="button" style="color:blue;" data-icon="plus" onclick="rate(1, '<?= $this->id ?>', '<?= $this->id ?>');"><?= $this->reputation['up'] ?></a>
		</div>
		
		<?= $this->details->text ?>
		
			
			 
		<? if (isset($this->details->user)) :?>
			<br /><br />
			<b style="font-size:14px;"><?= _('Partners') ?>:</b>
			<? if (isset($this->details->userProfile)) :?>
				<?= printProfile($this->details->userProfile,
						 $this->id) ?>
			<? endif ?>
			<? foreach($this->partnersProfiles as $item) : ?>
				<?= printProfile($item, $this->id) ?>
			<? endforeach ?>
			<br />
			<? if ($this->details->user == $_SESSION['user']->id) :?>

				<a href="?action=Details&id=<?= urlencode($this->id) ?>&namespace=<?= $_GET['namespace'] ?>" type="button" data-inline="true" data-mini="true">  <?= _("Edit my offer") ?> </a>

				<a href="?action=Details&rm=&id=<?= urlencode($this->id) ?>&namespace=<?= $_GET['namespace'] ?>" type="button" data-inline="true" data-mini="true" style="float:right;">  <?= _("Delete my offer") ?> </a>
			
			<? else :?>
				<a href="?action=Details&partnerRequest=&id=<?= urlencode($this->id) ?>&namespace=<?= $_GET['namespace'] ?>" type="button" data-inline="true" data-mini="true" data-icon="check"> <?= _("Partnership request") ?> </a>
			<? endif ?>
		<? endif ?>
		
		 <div data-role="popup" id="deletePopup" class="ui-content" data-overlay-theme="b" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			Are you sure?<br />
			<a id="deleteYes" data-role="button" data-theme="d" data-icon="delete" data-inline="true">Yes</a>
			<a data-rel="back" data-role="button" data-theme="d" data-icon="delete" data-inline="true">No</a>
		</div>
	</div>
</div>

<? include("footer.php"); ?>