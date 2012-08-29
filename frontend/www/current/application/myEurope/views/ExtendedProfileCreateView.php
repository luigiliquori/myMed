<? include("header.php"); ?>

<div data-role="page">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3(APPLICATION_NAME, $_SESSION['user']->name) ?>
		<? include("notifications.php"); ?>
	</div>
	<div data-role="content" style="text-align:center;">
		<br /><br />
		<?= _("Select your language") ?>:<br />
		<fieldset data-role="controlgroup" data-type="horizontal" style="display:inline-block;vertical-align: middle;">
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-a" value="fr" <?= $_SESSION["user"]->lang == "fr"?"checked='checked'":"" ?>/>
			<label for="radio-view-a"><?= _("French") ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-b" value="it" <?= $_SESSION["user"]->lang == "it"?"checked='checked'":"" ?>/>
			<label for="radio-view-b"><?= _("Italian") ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-e" value="en" <?= $_SESSION["user"]->lang == "en"?"checked='checked'":"" ?>/>
			<label for="radio-view-e"><?= _("English") ?></label>
		</fieldset>
		<br />
		<div style="text-align: center;" >
			<a href="#role" type="button" data-inline="true" data-icon="arrow-r" data-transition="slide"><?= _("Next") ?></a>
		</div>
		
	</div>
	
</div>

<?

$cats = array(
		"Association, coopérative, mutuelle",
		"Entreprise privée",
		"Chambre consulaire, groupement professionnel",
		"Université, organisme de recherche",
		"Commune, intercommunalité (communauté de communes, agglomération, métropole), autre établissement public communal ou intercommunal",
		"Département, établissement public départemental",
		"Région, établissement public régional",
		"Service de l’Etat, établissement public de l’Etat",
		"Autre établissement ou groupement public",
		"Autre"
		);

$shortCats = array_map("short", $cats);

function short($w){
	$pieces = preg_split("/[^a-zA-Z0-9_é]/", $w);
	return $pieces[0].(count($pieces)>1?strlen($pieces[1])>2?$pieces[1]:'':'');
}

?>

<div data-role="page" id="role">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3(APPLICATION_NAME, $_SESSION['user']->name) ?>
	</div>
	<div data-role="content">
		<p>
		<?= _("Hello,<br/><br/>This is your first time on myEurope.<br/>
				 Please fill in your profile <br/>
			You are ?<br/> ") ?>
		</p>
		<ul data-role="listview" data-inset="true">
		<? foreach (range(0, count($cats)-1) as $i) :?>
			<li><a href="#<?= $shortCats[$i] ?>"><?= $cats[$i] ?></a></li>
		<? endforeach ?>
		</ul> 
	</div>
	
</div>

<? foreach (range(0, count($cats)-1) as $i) :?>
	<?= profileForm($shortCats[$i], $cats[$i]) ?>
<? endforeach ?>


<? include("footer.php"); ?>

