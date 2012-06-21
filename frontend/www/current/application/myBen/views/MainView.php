<? include("header.php"); ?>

<div data-role="page" id="main-page">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
		<a href="#benevole" rel="external" data-role="button" data-theme="b" class="mm-big-button">Bénévole</a>
		<a href="#association-register" rel="external" data-role="button" data-theme="e" class="mm-big-button">Association</a>	
	</div>

</div>

<div data-role="page" id="benevole">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
	
		<form action="#" method="post" >
		
			<h4>Rentrez vos critères pour trouver une offre de bénévolat</h4>
		
			<div data-role="header" data-theme="b">
				<h3>Compétences</h3>
			</div>
			
			<div data-role="fieldcontain">
    			<fieldset data-role="controlgroup">
    				<input type=checkbox id=cb1 /><label for="cb1">Accompagnement (social professionnel)</label>
					<input type=checkbox id=cb2 /><label for="cb2">Accueil / Orientation</label>
					<input type=checkbox id=cb3 /><label for="cb3">Administration - Direction - Management</label>
					<input type=checkbox id=cb4 /><label for="cb4">Alphabétisation - Formation - Enseignement - Soutien scolaire</label>
					<input type=checkbox id=cb5 /><label for="cb5">Animation</label>
					<input type=checkbox id=cb6 /><label for="cb6">Communication</label>
					<input type=checkbox id=cb7 /><label for="cb7">Comptabilité gestion</label>
					<input type=checkbox id=cb8 /><label for="cb8">Distribution Collecte</label>
					<input type=checkbox id=cb9 /><label for="cb9">Ecoute</label>
					<input type=checkbox id=cb10 /><label for="cb10">Informatique, Internet</label>
					<input type=checkbox id=cb11 /><label for="cb11">Juridique</label>
					<input type=checkbox id=cb12 /><label for="cb12">Logistique - Conseil - Secrétariat - Administration courante</label>
					<input type=checkbox id=cb13 /><label for="cb13">Travaux (manuels et techniques)</label>
					<input type=checkbox id=cb14 /><label for="cb14">Visite domicile</label>
					<input type=checkbox id=cb15 /><label for="cb15">Visite hôpital</label>
					<input type=checkbox id=cb16 /><label for="cb16">Visite prison</label>
				</fieldset>
			</div>
			
			<div data-role="header" data-theme="b">
				<h3>Type de mission</h3>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
    				<input type=checkbox id=b1 checked="checked" /><label for="b1">Ponctuel</label>
					<input type=checkbox id=b2 checked="checked" /><label for="b2">Regulier</label>
					<input type=checkbox id=b3 checked="checked" /><label for="b3">Urgence</label>
				</fieldset>
			</div>
			
			<div data-role="header" data-theme="b">
				<h3>Mobilité</h3>
			</div>
			
			<label for="ville" >Lieu de résidence</label><input type="text" id="ville" value="Nice" />
			<label for="rayon" >Rayon d'action (km)</label><input type="number" id="rayon" value="10" />
			<br/>
			
			<div data-role="header" data-theme="b">
				<h3>Disponibilités</h3>
			</div>
			
			<div data-role="fieldcontain">
    			<fieldset data-role="controlgroup">
	   				<input type="checkbox" id="checkbox1" checked="checked" />
	   				<label for="checkbox1" checked>Tout le temps</label>
	   				
	   				<input type="checkbox" id="checkbox2"  />
	   				<label for="checkbox2">Semaine journée</label>
	   				
	   				<input type="checkbox" id="checkbox3"  />
	   				<label for="checkbox3">Semaine soir</label>
	   				
	   				<input type="checkbox" id="checkbox4"  />
	   				<label for="checkbox4">WE journée</label>
	   				
	   				<input type="checkbox" id="checkbox5"  />
	   				<label for="checkbox5">WE soir</label>
	   				
    			</fieldset>
			</div>
			
			<br/>
			<input data-theme="e" type="checkbox" name="alert" id="alert" checked="checked" />
			<label for="alert" data-theme="e">Prévenez moi par email en cas de futures offres correspondantes.</label>
		
			<a href="#offres" data-role="button" data-theme="g" data-transition="slide">Chercher</a>
		
		</form>
		
	</div>

</div>

<div data-role="page" id="association-register">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
	
		<form action="#" method="post" >
		
			<h4>Enregistrement d'une association</h4>
			
			<div data-role="header" data-theme="b">
				<h3>Renseignements administratifs</h3>
			</div>
			
				<label for="ass_nom" checked>Nom</label>
				<input type="text" id="ass_nom"  />
				
				<label for="ass_nom" checked>Raison sociale</label>
				<input type="text" id="ass_nom" />
				
				<label for="ass_nom" checked>SIRET</label>
				<input type="text" id="ass_nom" />
	  
	  			<label for="ass_add" checked>Adresse</label>
				<textarea  id="ass_add" ></textarea>
				
				<label for="ass_tel" checked>Tel</label>
				<input type="tel" id="ass_tel" />
				<br/>
			
			<div data-role="header" data-theme="b">
				<h3>Domaines d'actions</h3>
			</div>
			
			<div data-role="fieldcontain">
    			<fieldset data-role="controlgroup">
    				<input type=checkbox id=cb1 /><label for="cb1">Accompagnement (social professionnel)</label>
					<input type=checkbox id=cb2 /><label for="cb2">Accueil / Orientation</label>
					<input type=checkbox id=cb3 /><label for="cb3">Administration - Direction - Management</label>
					<input type=checkbox id=cb4 /><label for="cb4">Alphabétisation - Formation - Enseignement - Soutien scolaire</label>
					<input type=checkbox id=cb5 /><label for="cb5">Animation</label>
					<input type=checkbox id=cb6 /><label for="cb6">Communication</label>
					<input type=checkbox id=cb7 /><label for="cb7">Comptabilité gestion</label>
					<input type=checkbox id=cb8 /><label for="cb8">Distribution Collecte</label>
					<input type=checkbox id=cb9 /><label for="cb9">Ecoute</label>
					<input type=checkbox id=cb10 /><label for="cb10">Informatique, Internet</label>
					<input type=checkbox id=cb11 /><label for="cb11">Juridique</label>
					<input type=checkbox id=cb12 /><label for="cb12">Logistique - Conseil - Secrétariat - Administration courante</label>
					<input type=checkbox id=cb13 /><label for="cb13">Travaux (manuels et techniques)</label>
					<input type=checkbox id=cb14 /><label for="cb14">Visite domicile</label>
					<input type=checkbox id=cb15 /><label for="cb15">Visite hôpital</label>
					<input type=checkbox id=cb16 /><label for="cb16">Visite prison</label>
				</fieldset>
			</div>
			
			<div data-role="header" data-theme="b">
				<h3>Types de missions proposées</h3>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
    				<input type=checkbox id=b1 checked="checked" /><label for="b1">Ponctuel</label>
					<input type=checkbox id=b2 checked="checked" /><label for="b2">Regulier</label>
					<input type=checkbox id=b3 checked="checked" /><label for="b3">Urgence</label>
				</fieldset>
			</div>
	
			<a href="#association" data-role="button" data-theme="g" data-transition="slide">Enregister le profil</a>
		
		</form>
	</div>
</div>

<div data-role="page" id="association">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
		<a href="#post-offre" rel="external" data-role="button" data-theme="b" class="mm-big-button">Poster une annonce</a>
	</div>
</div>


<div data-role="page" id="post-offre">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
	
		<form action="#" method="post" >
		
			<h4>Remplissez cette fiche pour une nouvelle annonce</h4>
		
			<div data-role="header" data-theme="b">
				<h3>Description</h3>
			</div>
			
			<label for="offre_date" checked>Titre de l'annonce</label>
	   		<input type="text" id="offre_date" /> 
	   		
	   		<label for="offre_date" checked>Description</label>
	   		<input type="text" id="offre_date" />   
		
			<div data-role="header" data-theme="b">
				<h3>Compétences requises</h3>
			</div>
			
			<div data-role="fieldcontain">
    			<fieldset data-role="controlgroup">
    				<input type=checkbox id=cb1 checked="checked" /><label for="cb1">Accompagnement (social professionnel)</label>
					<input type=checkbox id=cb2 /><label for="cb2">Accueil / Orientation</label>
					<input type=checkbox id=cb3 /><label for="cb3">Administration - Direction - Management</label>
					<input type=checkbox id=cb4 /><label for="cb4">Alphabétisation - Formation - Enseignement - Soutien scolaire</label>
					<input type=checkbox id=cb5 checked="checked" /><label for="cb5">Animation</label>
					<input type=checkbox id=cb6 /><label for="cb6">Communication</label>
					<input type=checkbox id=cb7 /><label for="cb7">Comptabilité gestion</label>
					<input type=checkbox id=cb8 checked="checked" /><label for="cb8">Distribution Collecte</label>
					<input type=checkbox id=cb9 /><label for="cb9">Ecoute</label>
					<input type=checkbox id=cb10 /><label for="cb10">Informatique, Internet</label>
					<input type=checkbox id=cb11 checked="checked" /><label for="cb11">Juridique</label>
					<input type=checkbox id=cb12 /><label for="cb12">Logistique - Conseil - Secrétariat - Administration courante</label>
					<input type=checkbox id=cb14 /><label for="cb14">Visite domicile</label>
					<input type=checkbox id=cb15 /><label for="cb15">Visite hôpital</label>
					<input type=checkbox id=cb16 /><label for="cb16">Visite prison</label>
				</fieldset>
			</div>
			
			<div data-role="header" data-theme="b">
				<h3>Type de mission</h3>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
    				<input type=checkbox id=b1 /><label for="b1">Ponctuel</label>
					<input type=checkbox id=b2 checked="checked" /><label for="b2">Regulier</label>
					<input type=checkbox id=b3 /><label for="b3">Urgence</label>
				</fieldset>
			</div>
			
			<div data-role="header" data-theme="b">
				<h3>Lieu</h3>
			</div>
			
			<label for="ville" >Ville</label><input type="text" id="ville" value="Nice" />
			<br/>
			
			<div data-role="header" data-theme="b">
				<h3>Date</h3>
			</div>
			
			<div data-role="fieldcontain">		
	   				<label for="offre_date" checked>Ponctuel</label>
	   				<input type="date" id="offre_date" value="2012-06-23 18h" />  
	   								
	   			   	<label for="hebdo" checked>Ou hedomadaire</label><br/>
	   				<select name="hebdo" id="hebdo" data-inline=true >
      					<option value="lundi">Lundi</option>
      					<option value="lundi">Mardi</option>
      					<option value="lundi">Mercredi</option>
      					<option value="lundi">Jeudi</option>
      					<option value="lundi">Vendredi</option>
      					<option value="lundi">Samedi</option>
      					<option value="lundi">Dimanche</option>
   					</select>
   					<select name="hebdo" id="hebdo" data-inline=true >
      					<option value="lundi">Matin</option>
      					<option value="lundi">Après midi</option>
      					<option value="lundi">Soir</option>
   					</select>
			</div>
				
			<a href="#offre-postee" data-role="button" data-theme="g" data-transition="slide">Poster</a>
		
		</form>
	</div>
</div>

<div data-role="page" id="offre-postee">
	<? include("header-bar.php") ?>
	<div data-role="content">
		<img src="img/mail_send.png" style="float:left;margin:20px" />
		<p>
			Merci, votre offre a été transmise à <b>Nice-Bénévolat</b>.<br/>
			Nous vous contacterons dès que nous aurons trouvé un bénévole au profil adéquat.		
		</p>
	</div>
</div>


<div data-role="page" id="offres">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
		
		<ul data-role="listview">
			<li role="list-divider" data-theme="e">Résultats</li>
			<li>		
				<a href="#annonce" data-transition="slide">
					<u>Titre:</u> Transport de nourriture<br/>
					<u>Association:</u> Les Restos du Coeur <span class="ui-link">via Nice Bénévolat</a><br/>
				</a>
			</li>
			<li>
				<a href="#annonce" data-transition="slide">
					<u>Titre:</u> Cours du soir pour collégiens<br/>
					<u>Association:</u> Croix rouge <span class="ui-link">via Nice Bénévolat</a><br/>
				</a>
			</li>
			<li>
				<a href="#annonce" data-transition="slide">
					<u>Titre:</u> Collecte de vêtements<br/>
					<u>Association:</u> Le Secours Populaire <span class="ui-link">via Nice Bénévolat</a><br/>
				</a>
			</li>
		</ul>	
	</div>

</div>

<div data-role="page" id="annonce">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
		
		<div data-role="header" data-theme="b">
			<h3>Détails de l'annonce</h3>
		</div>
		<p>
			<b>Titre</b>: Collecte de vêtements<br/>
			<b>Association</b>: Le Secours Populaire <a href="#" rel="external">via Nice Bénévolat</a><br/>
			<b>Lieu</b>: Nice<br/>
			<b>Compétences</b>: Animation / Communication
			<b>Date</b>les merdi matin. 
		</p>
		
		<div data-role="header" data-theme="c" >
			<h4>Description</h4>
		</div>
		<p>
			Assurer la permanence de l'antenne de Nice.<br/>
			Accueillir les donneurs et Collecter les vêtements,<br/> 
			les trier et les mettre à disposition.<br/>
		</p>
		
		<a href="#postuler" data-role="button" data-rel="dialog" data-theme="g" >Postuler</a>
		
	</div>

</div>

<div data-role="page" id="postuler">

	<div data-role="header">
		<h1>Postuler</h1>
	</div>
	
	<div data-role="content">
		<p>
			<b>Postuler pour l'annonce</b> : "Collecte de vêtements"<br/>
			<b>Association</b>: "Le Secours Populaire" via Nice Bénévolat (tel:<a href="tel:04-93-12-34-00">04-93-12-34-00</a>)
			<form>
				<textarea rows="12" cols="40" placeholder="Ajoutez un message"></textarea>
				<fieldset class="ui-grid-a">
					<div class="ui-block-a"><a href="#" onclick="message('Votre profil a été envoyé à Nice Bénévolat. Vous pouvez aussi les contacter au 04-93-12-34-00'); return true;" data-role="button" data-rel="back" data-theme="g" data-icon="check">Postuler</a></div>
					<div class="ui-block-b"><a href="#" data-role="button" data-rel="back" data-theme="r" data-icon="delete">Annuler</a></div>
				</fieldset>
			</form>
		</p>
	</div>

</div>

<? include("footer.php"); ?>