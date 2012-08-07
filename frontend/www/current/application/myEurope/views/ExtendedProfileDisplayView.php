<? include("header.php"); ?>




<!-- Header -->
<div data-role="header" data-theme="c" data-position="fixed">
	<? tabs_3(
			_('Profile'),
			_('Add to contacts'),
			"?action=ExtendedProfile&id=". $this->profile->user ,
			"check") ?>
	<? include("notifications.php")?>
</div>

<div data-role="content" >
	
	<? if (isset($this->profile)) :?>
		<span class="label"><?= _('Role') ?>:</span> <?= $this->profile->role ?><br />
		<span class="label"><?= _('Activity') ?>:</span> <?= $this->profile->activity ?><br />
		<span class="label"><?= _('Email') ?>:</span> <?= $this->profile->email ?><br />
		<span class="label"><?= _('Validate') ?>:</span> <?= $this->profile->address ?><br />
		<span class="label"><?= _('Description') ?>:</span> <?= $this->profile->desc ?><br />
		<br /><br />
		<?= _('Reputation') ?>: 
		<? for($i=20; $i<=100; $i+=20) : ?>
			<a data-theme="<?= ($this->reputation['rep'] >= $i)?'e':'c' ?>" data-role="button" data-iconpos="notext" data-icon="star" data-inline="true" style="margin-right:1px; margin-left:1px;"></a>
		<? endfor ?>&nbsp;&nbsp;
		<?= $this->reputation['up'] ?> <img src="./img/up.png" style="height: 22px;vertical-align: middle;"/>
		<?= $this->reputation['down'] ?> <img src="./img/down.png" style="height: 22px;vertical-align: middle;"/>
		
	<? endif ?>
	
	
</div>
<? include("footer.php"); ?>