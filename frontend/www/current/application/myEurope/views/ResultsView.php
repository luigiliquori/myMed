<? include("header.php"); ?>

<div data-role="page" id="results">
	<? tabs_simple(array("search", $this->title)); ?>
	<? include("notifications.php"); ?>
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
		
		<? if ($this->part->isIndexNotEmpty()) :?>
			<a id="subscribeButton" style="float: right;" title="<?= _("Subscribe to:").' '.$this->part->renderSearchIndex(); ?>" type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="warning-sign"
			onclick='subscribe($(this), "<?= APPLICATION_NAME ?>:part", "<?= APPLICATION_NAME.":part" ?>", <?= json_encode($this->part->index) ?>);'><?= _("Subscribe") ?></a>
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
			<a href="?action=details&id=<?= urlencode($item->id) ?>" rel="external"><span
					class="ui-link"><?= $item->title ?> </span> &ndash; <span style="font-weight: lighter;"><?= prettyprintUser($item->user) ?></span><p class="ui-li-aside"><?= date('j/n/Y', $item->time) ?></p>
				</a>
			</li>
			<? endforeach ?>

			<? if (!empty($this->suggestions)) :?>
			<li data-role="list-divider">Suggestions:</li>
			<? foreach($this->suggestions as $item) : ?>
			<li><a href="./?action=details&id=<?= urlencode($item->id) ?>"> <b>...</b> : <?= print_r($item) ?><br />
			</a>
			</li>
			<? endforeach ?>
			<? endif ?>

		</ul>


	</div>


</div>

<? include("footer.php"); ?>