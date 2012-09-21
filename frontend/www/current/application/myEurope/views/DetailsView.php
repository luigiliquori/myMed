<? include("header.php"); ?>

<div data-role="page">

	<? tabs_simple(array('results', $this->details->title)); ?>
	<? include("notifications.php"); ?>

	<div data-role="content" >		
		
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d">
			<li>
				<?= $this->details->text ?>
				<p class="ui-li-aside" data-role="controlgroup" style="width:auto;" data-type="horizontal" data-mini="true">
					<a data-role="button" data-icon="faplus" onclick="rate($(this), '<?= $this->id ?>', '<?= $this->details->partner ?>', 1);">
						<span style="color: blue;font-size: 14px;"><?= $this->reputation['up'] ?></span> <span style="font-weight: normal;">"J'aime"</span>
					</a>
				</p>
			</li>
		</ul>
		
			 
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
				<a href="#deletePopup" data-role="button" data-rel="popup" data-inline="true" data-mini="true" style="float:right;">  <?= _("Delete my offer") ?> </a>
			<? else :?>
				<a href="?action=Details&partnerRequest=&id=<?= urlencode($this->id) ?>&namespace=<?= $_GET['namespace'] ?>" type="button" data-inline="true" data-mini="true" data-icon="check"> <?= _("Partnership request") ?> </a>
			<? endif ?>
		<? endif ?>
		
		 <div data-role="popup" id="deletePopup" class="ui-content" data-overlay-theme="e" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
			<?= _('Sure?') ?><br />
			<a href="?action=Details&rm=&id=<?= urlencode($this->id) ?>&namespace=<?= $_GET['namespace'] ?>" data-role="button" data-theme="d" data-icon="remove" data-inline="true">Yes</a>
		</div>
		
	</div>
</div>

<? include("footer.php"); ?>