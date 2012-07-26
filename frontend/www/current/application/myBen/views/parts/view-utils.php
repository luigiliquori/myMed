<?php 

// Global prefix for "id"
$PREFIX_ID="";
$READ_ONLY=false;


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
function checkbox_all($name) {
	global $READ_ONLY;
	if ($READ_ONLY) return;
	?> 
	<div data-role="fieldcontain">
		<fieldset data-role="controlgroup">
			<input 
				type="checkbox" 
				id="<?= $name ?>-all"  
				data-mini="true" 
				data-check-all="<?= $name ?>[]" />
			<label for="<?= $name ?>-all" >Tout / aucun</label>
		</fieldset>
	</div>
	<?
}

/** Generate checkboxes */
function checkboxes(
		$name,     /** Form input name */
		$options,  /** Map of key=>label */
		$selection = array()) /** Array of selected keys, or "<all>" */
{
	global $PREFIX_ID, $READ_ONLY;
	
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
			<? if ($READ_ONLY) :  ?>
				<? if (!in_array($key, $selection)) continue ?>
				<div id="<?= $PREFIX_ID.$name."-".$key?>" ><b><?= $label ?></b></div>
			<? else : ?>
				<input 
					type="checkbox" 
					id="<?= $PREFIX_ID.$name."-".$key?>" 
					<? if (in_array($key, $selection)) echo 'checked="checked"' ?> 
					name="<?= $name ?>[]" 
					value="<?= $key ?>"/>
				<label for="<?= $PREFIX_ID.$name."-".$key?>"><?= $label ?></label>
			<? endif ?>
			
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
	global $PREFIX_ID, $READ_ONLY; ?>
		
	
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
	global $PREFIX_ID, $READ_ONLY;
	?>
	<div data-role="fieldcontain">
		<label for="<?= $PREFIX_ID . $name ?>"><?= $label ?></label>
		<? if ($type == "textarea" ) : ?>
			<textarea 
				name="<?= $name ?>"
				<? if ($READ_ONLY) print "disabled='disabled'"?>
				id="<?= $PREFIX_ID . $name ?>"
				placeholder="<?= $placeholder ?>"
			><?= $value ?></textarea>
		<? else: ?>
			<input 
				<? if ($READ_ONLY) print "disabled='disabled'" ?>
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
function select(
		$name,     /** Form input name */
		$label,
		$options,  /** Map of key=>label */
		$selection,/** Array, the selected items */
		$multiple=false)
{
	global $PREFIX_ID, $READ_ONLY;
	
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
		<label for="<?= $PREFIX_ID . $name ?>"><?= $label ?></label>
		<? if ($READ_ONLY) : ?>
			
			<? foreach($selection as $key): ?>
				<input 
					id="<?= $PREFIX_ID . $name ?>"
					type="text" 
					disabled="disabled" 
					value="<?= $options[$key] ?>" />
			<? endforeach ?>
			
		<? else: ?>
			<select 
				id="<?= $PREFIX_ID . $name ?>" 
				name="<?= $name ?>" 
				<?= ($multiple) ? "multiple='multiple'" :"" ?>
				?>>
				<? foreach ($options as $key => $label) : ?>
					<option <? if (in_array($key, $selection)) echo 'checked="checked"' ?>  value="<?= $key ?>"><?= $label ?></option>		
				<? endforeach ?>
			</select>
		<? endif ?>
	</div>
	<?
}
 
?>