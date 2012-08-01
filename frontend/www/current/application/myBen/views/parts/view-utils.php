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

/** Output <tag>="<tag>" if the cobdition is true */
function bool_tag($tag, $condition) {
	if ($condition) print "$tag=\"$tag\"";
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
				<div class="readonly-input" id="<?= $PREFIX_ID.$name."-".$key?>" ><?= $label ?></div>
			<? else : ?>
				<input 
					type="checkbox" 
					id="<?= $PREFIX_ID.$name."-".$key?>" 
					<? bool_tag('checked', in_array($key, $selection)) ?> 
					name="<?= $name ?>[]" 
					value="<?= $key ?>"/>
				<label for="<?= $PREFIX_ID.$name."-".$key?>"><?= $label ?></label>
			<? endif ?>
			
		<? endforeach ?>
		
		</fieldset>
	</div>
	<?
}

/** Generate radio button */
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
				<? if ($READ_ONLY) :  ?>
					<? if ($key != $selection) continue ?>
					<div class="readonly-input" id="<?= $PREFIX_ID.$name."-".$key?>" ><?= $label ?></div>
				<? else: ?>
					<input 
						type="radio" 
						id="<?= $PREFIX_ID.$name."-".$key?>" 
						<? bool_tag('checked', $key == $selection) ?> 
						name="<?= $name ?>" 
						value="<?= $key ?>"/>
					<label for="<?= $PREFIX_ID.$name."-".$key?>"><?= $label ?></label>
				<? endif ?>
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
		$placeholder = "", 
		$mandatory = false)
{
	global $PREFIX_ID, $READ_ONLY;
	$id = $PREFIX_ID . $name;
	?>
	<div data-role="fieldcontain">
		<label for="<?= $id ?>"><?= $label ?></label>
		<? if ($READ_ONLY) :?>
			<span class="readonly-input" id="<?= $id ?>" >
				<? if ($type == "email"): ?>
					<a href="mailto:<?= $value ?>"><?= $value ?></a>
				<? elseif ($type == "tel"): ?>
					<a href="tel:<?= $value ?>"><?= $value ?></a>
				<? elseif ($type == "url"): ?>
					<a href="<?= $value ?>"><?= $value ?></a>
				<? else: ?>
					<?= $value ?>
				<? endif ?>
			</span>
		<? else: ?>
			<? if ($type == "textarea" ) : ?>
				<textarea 
					name="<?= $name ?>"
					id="<?= $id ?>"
					placeholder="<?= $placeholder ?>"
				><?= $value ?></textarea>
			<? else: ?>
				<input 
					type="<?= $type ?>" 
					<? if ($type == "date") print 'data-role="datebox" data-options=\'{"mode":"datebox", "useFocus": true}\'' ?>
					name="<?= $name ?>" 
					id="<?= $id ?>" 
					value="<?= $value ?>" 
					placeholder="<?= $placeholder ?>" />
			<? endif ?>
			<? if ($mandatory) :?>
			<div data-validate="<?= $name ?>" data-validate-non-empty >
				Le champ <?= $label ?> est obligatoire.
			</div>
			<? endif ?>
		<? endif ?>
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
	
	$id = $PREFIX_ID . $name;
	
	?>
	<div data-role="fieldcontain">
		<label for="<?= $id ?>"><?= $label ?></label>
		<? if ($READ_ONLY) : ?>
			<? foreach($selection as $key): ?>
				<span class="readonly-input" id="<?= $id ?>">
					<?= $options[$key] ?>
				</span>
			<? endforeach ?>
		<? else: ?>	
			<select
				id="<?= $id ?>" 
				name="<?= $name ?>" 
				<? bool_tag("multiple", $multiple) ?> >
				<? foreach ($options as $key => $label) : ?>
					<option
						<? bool_tag('selected', in_array($key, $selection)) ?>  
						value="<?= $key ?>">
						<?= $label ?>
					</option>	
				<? endforeach ?>
			</select>		
		<? endif ?>
	</div>
	<?
}


/** Display filters in top of lists
 * @param $action Action to redirect to
 * @param $filters array("filterID" => filterLabel)
 * @param $currFilter Current active filter ID */
function filters(
		$action, 
		$currFilter,
		$filters) 
{
	foreach($filters as $filterID => $label) : ?>
		<a data-role="button" data-mini="true" data-theme="d" data-inline="true"
			<? if ($currFilter == $filterID) echo 'class="ui-btn-active"' ?>
			href="<?= url($action, array('filter' => $filterID)) ?>" >
			<?= $label ?>
		</a>
	<? endforeach;
}

/** Show one header bar with an optionnal breadcrumb 
 * @param $breadcrumb Array of "Label" => "URL". Use null as URL to prevent displaying link */
function header_bar($breadcrumb = array()) {
	global $ERROR, $SUCCESS;
	
	?>
	<div data-role="header">
	
	<a href="javascript: history.go(-1)" data-role="button" data-icon="back">Retour</a>
	
	<h1><a href="?"><?= "MyBénévolat" ?></a></h1>
		
		<? if (isset($_SESSION['user'])) : ?>
			<a href="<?= url("ExtendedProfile:show") ?>" rel="external" data-role="button" data-theme="g" data-icon="person"><?= $_SESSION['user']->name ?></a>
		<? endif ?>
		
		<? if (!empty($ERROR)): ?>
		<div class="ui-bar ui-bar-e" id="notification-error">
			<h3><?= $ERROR ?></h3>
			<div style="float:right; margin-top:4px;">
				<a href="#" data-role="button" data-icon="delete" data-iconpos="notext" onclick="$('#notification-error').slideUp('fast');">Button</a>
			</div>
		</div>
		<? endif ?>
		
		<? if (!empty($SUCCESS)): ?>
		<div class="ui-bar ui-bar-e" id="notification-success">
			<h3><?= $SUCCESS ?></h3>
			<div style="float:right; margin-top:4px;">
				<a href="#" data-role="button" data-icon="delete" data-iconpos="notext" onclick="$('#notification-success').slideUp('fast');">Button</a>
			</div>
		</div>
		<? endif ?>
				
	</div>
	
	<? if (sizeof($breadcrumb) != 0) : ?>
		<div data-role="header" data-theme="e" class="left" >
			<h3>
			<? $lastLabel = end(array_keys($breadcrumb)) ?>
			<? foreach($breadcrumb as $label => $url): ?>
				<? if ($url != null) :?>
					<a href="<?= $url ?>"><?= $label ?></a> 
				<? else: ?>
					<?= $label ?>
				<? endif ?>
				<? if ($label != $lastLabel) : ?>
					&raquo;
				<? endif ?>
			<? endforeach ?>			
			</h3>
		</div>
	<? endif ?>
<?
}
 