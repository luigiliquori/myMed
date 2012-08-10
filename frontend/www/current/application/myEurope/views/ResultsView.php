<? include("header.php"); ?>

<div data-role="page" id="results">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3empty(_("Results")) ?>
	</div>

	<div data-role="content">

		<div data-role="fieldcontain" style="display:inline-block;">
			<label for="flip-a2"><?= _("Sort by") ?>:</label>
			<select data-theme="e" data-mini="true" name="slider2" id="flip-a2" data-role="slider" onchange="sortBy($(this).val());">
				<option value="3"><?= _("partner") ?></option>
				<option value="0"><?= _("date") ?></option>
			</select>
		</div>
		
		<div data-role="fieldcontain" style="float: right;">
			<select data-theme="e" data-mini="true" name="slider" id="flip-a" data-role="slider"
				onchange="toggleSub($(this).val(), '<?= APPLICATION_NAME ?>', '<?= $_GET['namespace'] ?>', '<?= isset($_GET['id'])?$_GET['id']:'' ?>','<?= urlencode(json_encode($this->index)) ?>');">
				<option value="3"><?= _("Subscribe") ?></option>
				<option value="0"><?= _("Subscribe") ?></option>
			</select>
		</div>
		
		
		<ul id="matchinglist" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?= _("filter") ?>" style="clear:both;">

			<? if (count($this->result) == 0) :?>
			<li>
				<h4><?= _("Your search didn't match any result.") ?></h4>
			</li>
			<? endif ?>

			<? foreach($this->result as $item) : ?>
			
			<li data-id="<?= getUser($item->user) ?>" data-time="<?= $item->time ?>">
			<a href="?action=details&namespace=<?= $_GET['namespace'] ?>&id=<?= urlencode($item->id) ?>" data-ajax="false"><span
					style="font-weight: lighter;">Offre publiée par </span> <?= getUser($item->user) ?> <span style="font-weight: lighter;"> (<?= date('d/m/Y', $item->time) ?> <?=  date('H:i:s', $item->time) ?>)</span>
				</a>
			</li>
			<? endforeach ?>

			<? if (!empty($this->suggestions)) :?>
			<li data-role="list-divider">Suggestions:</li>
			<? foreach($this->suggestions as $item) : ?>
			<li><a href="./?action=details&namespace=<?= $_GET['namespace'] ?>&id=<?= urlencode($item->id) ?>" data-ajax="false"> <b>...</b> : <?= print_r($item) ?><br />
			</a>
			</li>
			<? endforeach ?>
			<? endif ?>

		</ul>


	</div>


</div>

<? include("footer.php"); ?>