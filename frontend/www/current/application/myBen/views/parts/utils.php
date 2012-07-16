<?php 

// Global prefix for "id"
$PREFIX_ID="";

/**
 *  Generates a navbar of tabs with appropriate transitions (left / right).
 *  $tabs : An array of "<tabId>" => "<Label>"
 *          Where tabId is the id of the page = The id of the div with data-role="page"
 *  $ActiveTab : Current active tab id 
 *  These tabs should be repeated in the header of each tabbed page
 */
function tabs($tabs, $activeTab) {
	
	$reverse = true;
	?> 	
	<div data-role="navbar"> 
	<ul>
	<? foreach ($tabs as $id => $label) { ?>
		<li>
			<a 
				href="#<?= $id ?>"    
				data-transition="slide" 
				data-theme="b" 
				<?= ($reverse) ? 'data-direction="reverse"' : '' ?>
				<?= ($activeTab == $id) ? 'class="ui-btn-active ui-state-persist"' : '' ?> >
				<?= $label ?>
			</a>
		</li><? 
		
		if ($id == $activeTab) {
			$reverse = false;
		}
	}

	?> 
	</ul>
	</div> <?
} 

/** Generates a checkbox to check/uncheck all items of same name */
function checkox_all($name) {
	?> 
	<div data-role="fieldcontain">
		<fieldset data-role="controlgroup">
			<input type="checkbox" id="<?= $name ?>-all"  data-mini="true" data-check-all="<?= $name ?>[]" />
			<label for="<?= $name ?>-all" >Tout / aucun</label>
		</fieldset>
	</div>
	<?
}


/** Build URL */
function url($action, $args=array()) {
	$args["action"] = $action;
	return "?" . http_build_query($args);
}

/** Generate checkboxes */
function checkboxes(
		$name,     /** Form input name */
		$options,  /** Map of key=>label */
		$selection = array()) /** Array of selected keys, or "<all>" */
{
	global $PREFIX_ID;
	
	// Selection "<all>" => Select all
	if ($selection == "<all>") {
		$selection = array_keys($options);
	}
	
	// Null ? => empty array
	if ($selection == null) $selection = array();
	
	// Single selection ? => <String> to [<String>]
	if (is_string($selection)) $selection = array($selection);
	
	?>

		
	<div data-role="fieldcontain">
		<fieldset data-role="controlgroup">
		<? foreach ($options as $key => $label) : ?>
			<input type="checkbox" id="<?= $PREFIX_ID.$name."-".$key?>" <? if (in_array($key, $selection)) echo 'checked="checked"' ?> name="<?= $name ?>[]" value="<?= $key ?>"/>
			<label for="<?= $PREFIX_ID.$name."-".$key?>"><?= $label ?></label>
		<? endforeach ?>
		</fieldset>
	</div>
	<?
}

/** Generate checkboxes */
function radiobuttons(
		$name,     /** Form input name */
		$options,  /** Map of key=>label */
		$selection, /** String, the selected item */
		$legend="",  /** String label to show */
		$horizontal = false) 
{
	global $PREFIX_ID; ?>
		
	
	<div data-role="fieldcontain">
		<fieldset data-role="controlgroup" <? if ($horizontal) print 'data-type="horizontal" '?>>
			<legend><?= $legend ?></legend>

			<? foreach ($options as $key => $label) : ?>
				<input type="radio" id="<?= $PREFIX_ID.$name."-".$key?>" <? if ($key == $selection) echo 'checked="checked"' ?> name="<?= $name ?>" value="<?= $key ?>"/>
				<label for="<?= $PREFIX_ID.$name."-".$key?>"><?= $label ?></label>
			<? endforeach ?>
		</fieldset>
	</div>
	<?
}

function input(
		$type,
		$name,
		$label,		
		$value = "",
		$placeholder = "")
{
	global $PREFIX_ID;
	?>
	<div data-role="fieldcontain">
		<label for="<?= $PREFIX_ID . $name ?>"><?= $label ?></label>
		<? if ($type == "textarea" ) : ?>
			<textarea 
				name="<?= $name ?>"
				id="<?= $PREFIX_ID . $name ?>"
				placeholder="<?= $placeholder ?>"
			><?= $value ?></textarea>
		<? else: ?>
			<input 
				type="<?= $type ?>" 
				name="<?= $name ?>" 
				id="<?= $PREFIX_ID . $name ?>" 
				value="<?= $value ?>" 
				placeholder="<?= $placeholder ?>" />
		<? endif?>
	</div>
	<?
}

/** Generate checkboxes */
function multi_select(
		$name,     /** Form input name */
		$options,  /** Map of key=>label */
		$selection,/** Array, the selected items */
		$legend="" /** String label to show **/)
{
	?>
	<select name="<?= $name ?>" data-native-menu="false" multiple="multiple" size="4">
			<option><?= $legend ?></option>
			<? foreach ($options as $key => $label) : ?>
				<option <? if ($key == $selection) echo 'checked="checked"' ?>  value="<?= $key ?>"><?= $label ?></option>		
			<? endforeach ?>
	</select>
	
	<?
}
 
?>