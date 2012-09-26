<? include("header.php"); ?>

<div data-role="page">

	<? tabs_simple(array('results', $this->details->title)); ?>
	<? include("notifications.php"); ?>

	<div data-role="content" >		
		<h1><?= $this->details->title ?></h1>
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d">
			<li>
				<?= $this->details->text ?>
				
				<? if (isset($this->reputation)): ?>
				<p class="ui-li-aside" data-role="controlgroup" style="width:auto;" data-type="horizontal" data-mini="true">
					<a data-role="button" data-icon="faplus" onclick="rate($(this), '<?= $this->id ?>', '<?= $this->details->user ?>', 1);">
						<span style="color: blue;font-size: 14px;"><?= $this->reputation['up'] ?></span> <span style="font-weight: normal;">"J'aime"</span>
					</a>
				</p>
				<? endif; ?>
			</li>
		</ul>

		<? if (isset($this->details->user)) :?>
			<b style="font-size:14px;"><?= _('Keywords') ?>:</b>
			<?= str_replace('"', '', $this->details->keywords) ?>
			<br /><br />
			<b style="font-size:14px;"><?= _('Partners') ?>:</b>
			<? if (isset($this->details->userProfile)) :?>
				<?= $this->details->userProfile->renderProfile() ?>
			<? endif ?>
			<? foreach($this->partnersProfiles as $item) : ?>
				<?= $item->renderProfile() ?>
			<? endforeach ?>
			<br />
			<?php 
			debug_r(strlen($this->details->user));
			debug_r(strlen($_SESSION['user']->id));
			?>
			<? if ( in_array(trim($_SESSION['user']->id), $this->details->userProfile->users)) :?>
				<a href="#deletePopup" data-role="button" data-rel="popup" data-inline="true" data-icon="remove" data-mini="true" style="float:right;">  <?= _("Delete my offer") ?> </a>
			<? else :?>
				<a href="?action=Details&partnerRequest=&id=<?= urlencode($this->id) ?>" type="button" data-inline="true" data-mini="true" data-icon="check"> <?= _("Partnership request") ?> </a>
			<? endif ?>
		<? endif ?>
		
		 <div data-role="popup" id="deletePopup" class="ui-content" data-overlay-theme="e" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
			<?= _('Sure?') ?><br />
			<a href="?action=Details&rm=&id=<?= urlencode($this->id) ?>" data-role="button" data-theme="d" data-icon="remove" data-inline="true">Yes</a>
		</div>
		
	</div>
</div>

<? include("footer.php"); ?>