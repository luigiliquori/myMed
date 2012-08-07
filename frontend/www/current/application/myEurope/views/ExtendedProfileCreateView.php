<? include("header.php"); ?>
<? include("notifications.php")?>

<div data-role="page">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_2(APPLICATION_NAME) ?>
		<? include("notifications.php")?>
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
			<a href="#role" type="button" data-inline="true" data-icon="arrow-r" data-theme="b" data-transition="slide"><?= _("Next") ?></a>
		</div>
		
	</div>
	
</div>

<div data-role="page" id="role">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_2(APPLICATION_NAME) ?>
	</div>
	<div data-role="content">
		<p>
		<?= _("Hello<br/>,<br/>This is your first time on myEurope.<br/>
				 Please fill in your profile <br/>
			You are ?<br/> ") ?>

			<ul data-role="listview" data-inset="true">
				<li><a href="#Association"><?= _("Association") ?></a></li>
				<li><a href="#Entreprise"><?= _("Company") ?></a></li>
				<li><a href="#EtabPublic"><?= _("State-owned enterprise") ?></a></li>
				<li><a href="#Mairie"><?= _("Town hall") ?> </a></li>
				<li><a href="#Région"><?= _("Region") ?></a></li>
				<li><a href="#Département"><?= _("Department") ?></a></li>
				<li><a href="#Autre">... <?= _("Other") ?></a></li>
			</ul> 
		</p>	
	</div>
	
</div>

<div data-role="page" id="Association" >
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3(
				APPLICATION_NAME,
				"<?= _('Validate') ?>",
				"$('#ExtendedProfileForm').submit();",
				"check") ?>
	</div>

	<div data-role="content">
		<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false" class="compact">
			<input type="hidden" name="form" value="create" />
			<input type="hidden" name="role" value="Association" />
			
			<label for="textinputu1"><?= _('Association Name') ?>: </label>
			<input id="textinputu1" name="name" placeholder="" value='' type="text" />
			
			<label for="textinputu2"> Domaine d'action: </label>
			<input id="textinputu2" name="activity" placeholder="" value='' type="text" />
			
			<label for="textinputu3"> N°SIRET: </label>
			<input id="textinputu3" name="siret" placeholder="" value='' type="text" />
			
			<label for="textinputu4"> Adresse: </label>
			<input id="textinputu4" name="address" placeholder="" value=''type="text" />

			<label for="textinputu5"> <?= _('Email') ?>: </label>
			<input id="textinputu5" name="email" placeholder="" value='' type="email" />
			
			<label for="textinputu6"> <?= _('Phone') ?>: </label>
			<input id="textinputu6" name="phone" placeholder="" value='' type="text" />
			<br/>
			<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
			<br/>
				
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">
				J'accepte les 
				<a href="<?= APP_ROOT ?>/conds" rel="external">conditions d'utilisation</a>
			</span><br />
			
			<div style="text-align: center;" >
				<input type="submit" data-inline="true" data-role="button" data-icon="check" value="Valider"/>
			</div>
		</form>
	</div>
</div>

<div data-role="page" id="Entreprise" >
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3(
				APPLICATION_NAME,
				"Valider",
				"$('#ExtendedProfileForm').submit();",
				"check") ?>
	</div>

	<div data-role="content">
		<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false" class="compact">
			<input type="hidden" name="form" value="create" />
			<input type="hidden" name="role" value="Entreprise" />
			
			<label for="textinputu1"> <?= _('Company name') ?>: </label>
			<input id="textinputu1" name="name" placeholder="" value='' type="text" />
			
			<label for="textinputu2"> <?= _('Commercial activity') ?>: </label>
			<input id="textinputu2" name="activity" placeholder="" value='' type="text" />
			
			<label for="textinputu3"> <?= _('Siret') ?>: </label>
			<input id="textinputu3" name="siret" placeholder="" value='' type="text" />
			
			<label for="textinputu4"> <?= _('Address') ?>: </label>
			<input id="textinputu4" name="address" placeholder="" value=''type="text" />

			<label for="textinputu5"> <?= _('Email') ?>: </label>
			<input id="textinputu5" name="email" placeholder="" value='' type="email" />
			
			<label for="textinputu6"> <?= _('Phone') ?>: </label>
			<input id="textinputu6" name="phone" placeholder="" value='' type="text" />
			<br/>
			<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
			<br/>
				
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">
				J'accepte les 
				<a href="<?= APP_ROOT ?>/conds" rel="external">conditions d'utilisation</a>
			</span><br />
			<div style="text-align: center;" >
				<input type="submit" data-inline="true" data-role="button" data-icon="check" value="Valider"/>
			</div>
		</form>
	</div>
</div>

<div data-role="page" id="EtabPublic" >
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3(
				APPLICATION_NAME,
				"Valider",
				"$('#ExtendedProfileForm').submit();",
				"check") ?>
	</div>

	<div data-role="content">
		<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false" class="compact">
			<input type="hidden" name="form" value="create" />
			<input type="hidden" name="role" value="EtabPublic" />
			
			<label for="textinputu1"> Nom de l’établissement: </label>
			<input id="textinputu1" name="name" placeholder="" value='' type="text" />
			
			<label for="textinputu2"> Type d’établissement: </label>
			<input id="textinputu2" name="activity" placeholder="" value='' type="text" />
			
			<label for="textinputu4"> Adresse: </label>
			<input id="textinputu4" name="address" placeholder="" value=''type="text" />

			<label for="textinputu5"> <?= _('Email') ?>: </label>
			<input id="textinputu5" name="email" placeholder="" value='' type="email" />
			
			<label for="textinputu6"> <?= _('Phone') ?>: </label>
			<input id="textinputu6" name="phone" placeholder="" value='' type="text" />
			<br/>
			<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
			<br/>
				
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">
				J'accepte les 
				<a href="<?= APP_ROOT ?>/conds" rel="external">conditions d'utilisation</a>
			</span><br />
			<div style="text-align: center;" >
				<input type="submit" data-inline="true" data-role="button" data-icon="check" value="Valider"/>
			</div>
		</form>
	</div>
</div>

<div data-role="page" id="Mairie" >
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3(
				APPLICATION_NAME,
				"Valider",
				"$('#ExtendedProfileForm').submit();",
				"check") ?>
	</div>

	<div data-role="content">
		<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false" class="compact">
			<input type="hidden" name="form" value="create" />
			<input type="hidden" name="role" value="Mairie" />
			
			<label for="textinputu1"> Ville/Commune: </label>
			<input id="textinputu1" name="name" placeholder="" value='' type="text" />
			
			<label for="textinputu4"> Adresse: </label>
			<input id="textinputu4" name="address" placeholder="" value=''type="text" />

			<label for="textinputu5"> <?= _('Email') ?>: </label>
			<input id="textinputu5" name="email" placeholder="" value='' type="email" />
			
			<label for="textinputu6"> <?= _('Phone') ?>: </label>
			<input id="textinputu6" name="phone" placeholder="" value='' type="text" />
			<br/>
			<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
			<br/>
				
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">
				J'accepte les 
				<a href="<?= APP_ROOT ?>/conds" rel="external">conditions d'utilisation</a>
			</span><br />
			<div style="text-align: center;" >
				<input type="submit" data-inline="true" data-role="button" data-icon="check" value="Valider"/>
			</div>
		</form>
	</div>
</div>

<div data-role="page" id="Région" >
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3(
				APPLICATION_NAME,
				"Valider",
				"$('#ExtendedProfileForm').submit();",
				"check") ?>
	</div>

	<div data-role="content">
		<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false" class="compact">
			<input type="hidden" name="form" value="create" />
			<input type="hidden" name="role" value="Région" />
			
			<div data-role="fieldcontain" class="mySlider">
				<label for="flip-a2">Vous êtes:</label>
				<select data-theme="b" name="activity" id="flip-a2" data-role="slider"
					onchange="">
					<option value="cr">Conseil Régional</option>
					<option value="pr">Préfecture de Région</option>
				</select>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup"  class="myCheck">
					<input type="radio" name="name" id="radio-view-a" value="PACA" /> <label for="radio-view-a">PACA</label>
					<input type="radio" name="name" id="radio-view-e" value="Rhône-Alpes" /><label for="radio-view-e">Rhône-Alpes</label>
				</fieldset>
			</div>
			
			<label for="textinputu4"> Adresse: </label>
			<input id="textinputu4" name="address" placeholder="" value=''type="text" />

			<label for="textinputu5"> <?= _('Email') ?>: </label>
			<input id="textinputu5" name="email" placeholder="" value='' type="email" />
			
			<label for="textinputu6"> <?= _('Phone') ?>: </label>
			<input id="textinputu6" name="phone" placeholder="" value='' type="text" />
			<br/>
			<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
			<br/>
				
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">
				J'accepte les 
				<a href="<?= APP_ROOT ?>/conds" rel="external">conditions d'utilisation</a>
			</span><br />
			<div style="text-align: center;" >
				<input type="submit" data-inline="true" data-role="button" data-icon="check" value="Valider"/>
			</div>
		</form>
	</div>
</div>

<div data-role="page" id="Département" >
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3(
				APPLICATION_NAME,
				"Valider",
				"$('#ExtendedProfileForm').submit();",
				"check") ?>
	</div>

	<div data-role="content">
		<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false" class="compact">
			<input type="hidden" name="form" value="create" />
			<input type="hidden" name="role" value="Département" />
			
			<div data-role="fieldcontain" class="mySlider">
				<label for="flip-a2">Vous êtes:</label>
				<select data-theme="b" name="activity" id="flip-a2" data-role="slider"
					onchange="">
					<option value="cg">Conseil Général</option>
					<option value="pd">Préfecture de Département</option>
				</select>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup" class="myCheck">

					<input type="radio" name="name" id="radio-view-a" value="Alpes-Maritimes" /> <label for="radio-view-a">Alpes-Maritimes</label>
					<input type="radio" name="name" id="radio-view-b" value="Alpes de Haute-Provence" /> <label for="radio-view-b">Alpes de Haute-Provence</label>
					<input type="radio" name="name" id="radio-view-c" value="Hautes-Alpes" /> <label for="radio-view-c">Hautes-Alpes</label>
					<input type="radio" name="name" id="radio-view-d" value="Savoie" /> <label for="radio-view-d">Savoie</label>
					<input type="radio" name="name" id="radio-view-e" value="Haute-Savoie" /><label for="radio-view-e">Haute-Savoie</label>
				</fieldset>
			</div>
			
			<label for="textinputu4"> Adresse: </label>
			<input id="textinputu4" name="address" placeholder="" value=''type="text" />

			<label for="textinputu5"> <?= _('EMail') ?>: </label>
			<input id="textinputu5" name="email" placeholder="" value='' type="email" />
			
			<label for="textinputu6"> <?= _('Phone') ?>: </label>
			<input id="textinputu6" name="phone" placeholder="" value='' type="text" />
			<br/>
			<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
			<br/>
				
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">
				J'accepte les 
				<a href="<?= APP_ROOT ?>/conds" rel="external">conditions d'utilisation</a>
			</span><br />
			<div style="text-align: center;" >
				<input type="submit" data-inline="true" data-role="button" data-icon="check" value="Valider"/>
			</div>
		</form>
	</div>
</div>

<div data-role="page" id="Autre" >
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3(
				APPLICATION_NAME,
				"Valider",
				"$('#ExtendedProfileForm').submit();",
				"check") ?>
	</div>

	<div data-role="content">
		<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false" class="compact">
			<input type="hidden" name="form" value="create" />
			<input type="hidden" name="role" value="Autre" />
			
			<label for="textinputu1"> Nom: </label>
			<input id="textinputu1" name="name" placeholder="" value='' type="text" />
			
			<label for="textinputu2"> Activité exercée: </label>
			<input id="textinputu2" name="activity" placeholder="" value='' type="text" />
			
			<input id="textinputu3" name="siret" placeholder="" value='' type="hidden" />
			
			<label for="textinputu4"> Adresse: </label>
			<input id="textinputu4" name="address" placeholder="" value=''type="text" />

			<label for="textinputu5"> <?= _('Email') ?>: </label>
			<input id="textinputu5" name="email" placeholder="" value='' type="email" />
			
			<label for="textinputu6"> <?= _('Phone') ?>: </label>
			<input id="textinputu6" name="phone" placeholder="" value='' type="text" />
			<br/>
			<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
			<br/>
				
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">
				J'accepte les 
				<a href="<?= APP_ROOT ?>/conds" rel="external">conditions d'utilisation</a>
			</span><br />
			
			<div style="text-align: center;" >
				<input type="submit" data-inline="true" data-role="button" data-icon="check" value="<?= _('Validate') ?>"/>
			</div>
		</form>
	</div>
</div>


<? include("footer.php"); ?>

