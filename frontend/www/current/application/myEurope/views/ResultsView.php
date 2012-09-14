<? include("header.php"); ?>

<div data-role="page" id="results">
	<div data-role="header" class="ui-btn-active" data-position="fixed">
		<? tabs_simple("Results") ?>
		<? include("notifications.php"); ?>
	</div>

	<div data-role="content">

		<div style="margin-bottom: 16px;">
		<label for="radio-group1"><?= _("Sort by") ?>:</label>
		<fieldset id="radio-group1" data-role="controlgroup" data-mini="true" data-type="horizontal" style="display:inline-block;">
			<input onclick="sortBy($(this).val());" type="radio" name="name" id="radio-view-t" value="title" checked='checked'/>
			<label for="radio-view-t"><?= _("title") ?></label>
			<input onclick="sortBy($(this).val());" type="radio" name="name" id="radio-view-a"value="partner"/>
			<label for="radio-view-a"><?= _("partner") ?></label>
			<input onclick="sortBy($(this).val());" type="radio" name="name" id="radio-view-b" value="date" />
			<label for="radio-view-b"><?= _("date") ?></label>
			<input onclick="sortBy($(this).val());" type="radio" name="name" id="radio-view-e" value="reputation" />
			<label for="radio-view-e"><?= _("reputation") ?></label>
		</fieldset>
		
		<? if (!empty($this->themes)||!empty($this->places)||!empty($this->roles)||!empty($this->p)) :?>
		<div style="float: right;">
		<label for="subscribeButton" >
			<?= (empty($this->themes)?'':_('Themes').': <em style="font-size: 14px;">'.join(", ",$this->themes).'</em>, ').
		(empty($this->places)?'':_('Places').': <em style="font-size: 14px;">'.join(", ",$this->places).'</em>, ').
		(empty($this->roles)?'':_('Roles').': <em style="font-size: 14px;">'.join(", ",$this->roles).'</em>, ').
		(empty($this->p)?'':_('Keywords').': <em style="font-size: 14px;">'.join(", ",$this->p).'</em>') ?>:
		
		</label>
		<a id="subscribeButton" type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="warning-sign"
		onclick='subscribe($(this), "<?= APPLICATION_NAME ?>:part", "<?= APPLICATION_NAME.":".$_GET['namespace'] ?>", <?= json_encode($this->index) ?>);'><?= _("Subscribe") ?></a>
		</div>
		<? endif ?>
		</div>
		
		<ul id="matchinglist" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?= _("filter") ?>" style="clear:both;">

			<?php if (count($this->result) == 0) :?>
			<li>
				<h4><?= _("Your search didn't match any result.") ?></h4>
			</li>
			<? endif ?>

			<? foreach($this->result as $item) : ?>
			
			<li data-id="<?= prettyprintUser($item->user) ?>" data-partner="<?= $item->user ?>" data-time="<?= $item->time ?>" data-title="<?= $item->title ?>">
			<a href="?action=details&namespace=<?= $this->namespace ?>&id=<?= urlencode($item->id) ?>"><span
					class="ui-link"><?= $item->title ?> </span> &ndash; <span style="font-weight: lighter;"><?= prettyprintUser($item->user) ?>  (<?= date('j/n/y G:i', $item->time) ?>)</span>
				</a>
			</li>
			<? endforeach ?>

			<? if (!empty($this->suggestions)) :?>
			<li data-role="list-divider">Suggestions:</li>
			<? foreach($this->suggestions as $item) : ?>
			<li><a href="./?action=details&namespace=<?= $_GET['namespace'] ?>&id=<?= urlencode($item->id) ?>"> <b>...</b> : <?= print_r($item) ?><br />
			</a>
			</li>
			<? endforeach ?>
			<? endif ?>

		</ul>


	</div>


</div>

<? include("footer.php"); ?>