<?

// ---------------------------------------------------------------------
// General purpose utils (controllers)
// ---------------------------------------------------------------------

/** Test whether a var is in the GET or POST arguments */
function in_request($varname) {
	return isset($_REQUEST[$varname]);
}

/** Return true if the value is true or "1" or 1 or "true" */
function is_true($value) {
	return (($value === true) || ($value === 1) || ($value === "1") || ($value === "true"));
} 

// ---------------------------------------------------------------------
// View utils (views)
// ---------------------------------------------------------------------

/** 
 *  Build an URL with "<action>[:<method>]".
 *  For example :
 *  * url("profile:create") => "?action=profile&method=create"
 *  * url("profile", array("foo" => "bar")) => "?action=profile&foo=bar"
 *  If action begins with "/", it is considered as absolute path 
 *  instead of action.
 *  If action == "<self>", all current GET parameters are copied and merged with supplied "$args".
 */
function url($action, $args=array()) {
	global $ACTION;
	
	// Real path

	if ($action[0] == '/' || $action[0] == '?' || $action[0] == '.' || strpos($action, "index.php") === 0 ) return $action;
	
	// Same action
	if ($action == "<self>") { 
		$args = array_merge($_GET, $args);
	} else {
		// Action:method
		$parts = explode(":", $action);
		$args["action"] = $parts[0];
		if (sizeof($parts) > 1) $args["method"] = $parts[1];
	}
	
	// Build url
	return "?" . http_build_query($args);
}

// Global prefix for "id"
$PREFIX_ID="";

// READ_ONLY or not
$READ_ONLY=false;

/** Output <tag>="<tag>" if the condition is true */
function bool_tag($tag, $condition) {
	if ($condition) print "$tag=\"$tag\"";
}

/** prettyprint id of a user **/
function getUser($id){
	$p = preg_split("/(MYMED_|@)/", $id, NULL, PREG_SPLIT_NO_EMPTY);
	return str_replace('.', ' ', $p[0]);
}

/** returns true when $haystack contains substring $needle **/
function strcontain($haystack,$needle){
	$pos = strpos($haystack,$needle);
	return $pos !== false;
}

/** Generate nice  element for title : div[data-role="header"]/h3 */
function title_bar($title) {
?>
	<div data-role="header" data-theme="e">
		<h3><?= $title ?></h3>
	</div>
<?
}

/** Generates a checkbox to check/uncheck all items of same input name */
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
			<label for="<?= $name ?>-all" ><?= _("Tout / aucun") ?></label>
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


/**
 * Generate input element.
 * If $READ_ONLY is active, a read-only equivalent will be generated. 
 * @param $type String: "textarea" or any valid 'type' values for input tag (text, tel, date, ...)
 *        Special behavior for :
 *        $type="textarea" : Generate textarea
 *        $type="html" : Textearea with WYSISYG CLEEditor
 *        $type="url"/"mailto"/"tel" : Generate links in READ_ONLY mode
 *        $type="date" : Show a datebox for selection of date
 * @param $name String: Name (id) of the form field
 * @param $label String: Human readable name of the field
 * @param $value String: Initial value of the field
 * @param $placeholder String: Placeholder if value is empty
 * @param $mandatory boolean (optional, false by default) : Is field required ? 
 */
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
	if ($mandatory) {
		$placeholder .= sprintf(" (%s)", _("obligatoire"));
		$label .= "*";
	} else {
		$placeholder .= sprintf(" (%s)",  _("optionnel"));
	}
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
			<? if ($type == "textarea" || $type == "html") : ?>
				<textarea 
					name="<?= $name ?>"
					id="<?= $id ?>"
					<?= ($type == "html")  ? "class='cle-editor'" : "" ?>
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
				<?= sprintf(_("Le champ %s est obligatoire."), $label) ?>
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

/** 
 * Generate filters :
 * Switch radio buttons shown in top of lists
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
	global $ERROR, $SUCCESS, $LANG;

	// add <APPLICATION_LABEL> as home in the breadcrumb
	$breadcrumb = array_merge(
			array(APPLICATION_LABEL => url('main')),
			$breadcrumb);
	?>
	
	<div data-role="header" data-theme="b" >
		
			<h3 class="mm-left">
			<? if (sizeof($breadcrumb) != 0) : ?>
				<? $lastLabel = end(array_keys($breadcrumb)) ?>
				<? foreach($breadcrumb as $label => $url): ?>
					<? if ($url != null) :?>
						<a style="color:white !important" href="<?= $url ?>" ><?= $label ?></a> 
					<? else: ?> 
						<?= $label ?>
					<? endif ?>
					<? if ($label != $lastLabel) : ?>
						&raquo;
				<? endif ?>
				<? endforeach ?>			
			<? endif ?>
			</h3>
		
			<div data-role="controlgroup" data-type="horizontal" class="ui-btn-right" >
			<? if (isset($_SESSION['user'])) : ?>
				<a  rel="external" data-role="button" data-theme="g" 
					data-icon="person" 
					data-if-small="data-iconpos:notext" 
					title="<?= $_SESSION['user']->name ?>"
					href="<?= url("ExtendedProfile:show") ?>" >
					<?= $_SESSION['user']->name ?>
				</a>
				<a  data-ajax="false" data-role="button" data-theme="r" data-icon="power" 
					data-if-small="data-iconpos:notext" 
					href="<?= url("logout") ?>"
					title="<?= _("Exit") ?>">
					<?= _("Exit") ?>
					</a>
			<? else: ?>
				<a href="#lang-chooser" data-rel="dialog" data-role="button" >
					<img src="../../../system/img/flags/<?= $LANG ?>.png">
					<?= $LANG ?>
				</a>
				<a rel="external" data-role="button" data-theme="g" data-icon="power" 
					href="<?= url("login") ?>" >
					<?= _("Se connecter") ?>
				</a>
			<? endif ?>

		</div>
		
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
<?
}


/** Dumps a wizard footbar for forms, with a previous/next/commit buttons. */
function wizard_footbar($commit_label) { ?>
<div data-role="footer" id="wizard" data-position="fixed">
	<div data-role="navbar">
	<ul>
		<li>
			<a href="#" id="wizard-start" data-rel="back" data-icon="delete" data-theme="r">
				<?= _("Annuler") ?>
			</a>
			<a href="#" id="wizard-previous" data-icon="arrow-l" data-theme="e">
				<?= _("Précédent") ?>
			</a>
		</li>
		<li>
			<a href="#" id="wizard-next" data-icon="arrow-r" data-theme="e">
				<?= _("Suivant") ?>
			</a>
			<a href="#" data-role="submit" id="wizard-end" data-icon="check" data-theme="g">
				<?= $commit_label ?>
			</a> 
		</li>
	</ul>
	</div>
</div>
<?	
}





?>