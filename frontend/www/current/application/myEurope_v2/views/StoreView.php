<div id="store" data-role="page">

	<? include("header-bar.php"); ?>
	
	<div data-role="content" class="content">
	
			
		<div data-role="collapsible-set" data-theme="b" data-content-theme="d">
		
			<div data-role="collapsible" data-collapsed="false">
				<h3>Journal des Bonnes Pratiques</h3>
				<ul data-role="listview"  data-mini="true" data-filter="true">
					<li>
						<a class="ULitem" href="#exemple"><h3 style="color: black;">Trouver un partenaire européen</h3></a>
					</li>
					<li>
						<a class="ULitem" href="#exemple"><h3 style="color: black;">Par quoi commencer ?</h3></a>
					</li>
					<li>
						<a class="ULitem" href="#exemple"><h3 style="color: black;">Des pistes pour trouver un partenaire</h3></a>
					</li>
					<li>
						<a class="ULitem" href="#exemple"><h3 style="color: black;">Identifier un partenaire dans un séminaire de contact</h3></a>
					</li>
					<li>
						<a class="ULitem" href="#exemple"><h3 style="color: black;">Remplir une fiche de recherche de partenaire avec le PEJA</h3></a>
					</li>
					<li>
						<a class="ULitem" href="#exemple"><h3 style="color: black;">En live avec son partenaire !</h3></a>
					</li>
				</ul>
			</div>
			
			<div data-role="collapsible">
				<h3>Journal des "Beta" testeurs de myEurope</h3>
				<ul data-role="listview"  data-mini="true" data-filter="true">
					<li>
						<a class="ULitem" href="#exemple"><h3 style="color: black;">Icones pour partager l'application</h3></a>
					</li>
					<li>
						<a class="ULitem" href="#exemple"><h3 style="color: black;">Juste un test</h3></a>
					</li>
				</ul>
			</div>
			
		</div>
	

	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-icon="search">Partenariats</a></li>
				<li><a href="#info" data-transition="none" data-back="true" data-icon="info">Informations</a></li>
				<li><a href="#store" data-transition="none" data-icon="grid" class="ui-btn-active ui-state-persist">Journal</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user">Profil</a></li>
			</ul>
		</div>
	</div>

</div>

<!-- EXEMPLE -->
<div id="exemple" data-role="page">

	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="#" data-rel="back" data-icon="arrow-l">Retour</a>
		<h1><?= APPLICATION_NAME ?></h1>
		<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-icon="delete" data-theme="r" data-icon="power" class="ui-btn-right" data-iconpos="notext">Déconnexion</a>
	</div>
	
	
	<div data-role="content" class="content">
		<div data-role="collapsible" data-collapsed="false" data-theme="c" data-content-theme="d">
			<h3>Trouver un partenaire européen</h3>
			<p Style="color: black">Pour ceux qui s’engagent pour la 1ère fois dans la réalisation d’un projet européen, cela semble être la difficulté principale ! En fait le problème réside moins dans la recherche d’un partenaire que dans le maintien de la coopération avec celui-ci sur des bases positives…</p>
			<p Style="color: black">Qu’il soit local ou international le partenariat doit faire l’objet d’une analyse et induire une démarche stratégique [1]] Les motifs d’engagement dans un partenariat suppose d’identifier les enjeux qu’implique cette démarche (que veut l’organisation porteuse du projet, que recherche le partenaire sollicité ?…)</p>
			<p Style="color: black">
			[1] [Racine - Construire et conduire des partenairats transnationaux
			Recherche :
			Pour y voir plus clair dans les programmes européens...
			Les projets des autres...
			Outils pratiques
			Points de vue & Analyses
			Se former pour conduire un projet européen
			Trouver un partenaire européen
			</p>
		</div>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-icon="search">Partenariats</a></li>
				<li><a href="#info" data-transition="none" data-back="true" data-icon="info">Informations</a></li>
				<li><a href="#store" data-transition="none" data-icon="grid" class="ui-btn-active ui-state-persist">Journal</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user">Profil</a></li>
			</ul>
		</div>
	</div>

</div>

