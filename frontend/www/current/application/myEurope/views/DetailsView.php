<? include("header.php"); ?>

<div data-role="page">

	<? tabs_simple(array($this->details->title)); ?>
	<? include("notifications.php"); ?>

	<div data-role="content" >	
		<br>
		<div data-role="header" data-theme="e">
		<h1><?= $this->details->title ?></h1>
		</div>	
		
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d">
			<li style="font-weight: normal;">
				<div data-role="none">
					<?= $this->details->text ?>
				</div>
				<p class="ui-li-aside" data-role="controlgroup" style="width:auto;" data-type="horizontal" data-mini="true">
					<a data-role="button" data-icon="plus-sign" onclick="rate($(this), '<?= $this->id ?>', '<?= $this->details->user ?>', 1);">
						<span style="color: blue;font-size: 14px;"><?= $this->reputation['up'] ?></span> <span style="font-weight: normal;">"J'aime"</span>
					</a>
				</p>
				
			</li>
			<li style="font-weight: normal; font-size: 14px;">
				<b><?= _("Keywords") ?>:</b> <?= str_replace(array('{','}','"r":','"p":','"",','"c":','"t":','"k":'), '', $this->details->keywords)  ?>
			</li>
			<li style="font-weight: normal; font-size: 14px;">
				<b><?= _("Publication Date") ?>:</b> <?= date('j/n/Y G:i', $this->details->time) ?>
			</li>
			<? if (isset($this->details->expirationDate)) :?>
				<li style="font-weight: normal; font-size: 14px;">
					<b><?= _("Expiration Date") ?>:</b> <?= date('j/n/Y G:i', strtotime($this->details->expirationDate)) ?>
				</li>	
			<? endif ?>	
		</ul>
		
		<? if (isset($this->details->user)) :?>			
			<div data-role="collapsible" data-mini="true">
				<h3><?= _('Partners') ?>:</h3>
				
				<?= $this->details->userProfile->renderProfile() ?>
					
				<? foreach($this->partnersProfiles as $item) : ?>
					<?= $item->renderProfile() ?>
				<? endforeach ?>
			</div>

			<br />

			<? if ( in_array(trim($_SESSION['user']->id), $this->details->userProfile->users)) :?>
				<a href="#deletePopup" data-role="button" data-rel="popup" data-inline="true" data-icon="remove" data-mini="true" style="float:right;">  <?= _("Delete my offer") ?> </a>
			<? else :?>
				<a href="?action=Details&method=update&partnerRequest=&id=<?= urlencode($this->id) ?>" type="button" data-inline="true" data-mini="true" data-icon="check"> <?= _("Partnership request") ?> </a>
			<? endif ?>
		<? endif ?>
		
		 <div data-role="popup" id="deletePopup" class="ui-content" data-overlay-theme="e" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
			<?= _('Sure?') ?><br />
			<a href="?action=Details&method=delete&id=<?= urlencode($this->id) ?>" data-role="button" data-theme="d" data-icon="remove" data-inline="true">Yes</a>
		</div>
		
	</div>
</div>

<? include("footer.php"); ?>