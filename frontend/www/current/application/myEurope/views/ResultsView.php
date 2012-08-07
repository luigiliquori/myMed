<? include("header.php"); ?>

<div data-role="page" id="results">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3empty(_("Results")) ?>
		<? include("notifications.php")?>
	</div>

	<div data-role="content">

		<ul data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="<?= _("filter") ?>">

			<select data-theme="c" data-mini="true" name="slider2" id="flip-a2" data-role="slider" onchange="">
				<option value="3"><?= _("Sort by date") ?></option>
				<option value="0"><?= _("Sort by partner") ?></option>
			</select>
			<select data-theme="e" data-mini="true" name="slider" id="flip-a" data-role="slider"
				onchange="toggleSub($(this).val(), '<?= APPLICATION_NAME ?>', '<?= $_GET['namespace'] ?>', '<?= isset($_GET['id'])?$_GET['id']:'' ?>','<?= urlencode(json_encode($this->index)) ?>');">
				<option value="3"><?= _("Subscribe to this search") ?></option>
				<option value="0"><?= _("Unsubscribe") ?></option>
			</select>

			<? if (count($this->result) == 0) :?>
			<li>
				<h4><?= _("Your search didn't match any result.") ?></h4>
			</li>
			<? endif ?>

			<? foreach($this->result as $item) : ?>
			<li><a href="./?action=details&namespace=<?= $_GET['namespace'] ?>&id=<?= urlencode($item->id) ?>" data-ajax="false"><span
					style="font-weight: lighter;">Offre publiée par </span> <?= $item->user ?> <span style="font-weight: lighter;"> (26/07/2012)</span>
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