<!-- ------------------ -->
<!-- DEFAULT HELP POPUP -->
<!-- ------------------ -->
<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
	<h2><?= _("Bienvenu dans myEurope") ?></h2>
	<p><?= _("myEurope est une application du projet Alcotra myMed, qui vise à mettre en relations des maires et des communes transfrontalières.") ?><br />
	<?= _("L'idée est de frounir un outils pour simplifier et aider la création de projet Européen comme myMed") ?></p>
	<br />
	<h3><?= _("Comment ça marche") ?> ?</h3>
	<ul data-role="listview" data-theme="e">	
		<li>
			<img alt="search" src="img/icons/search.png" Style="width: 64px">
			<p><strong><?= _("Search a partnership offer") ?></strong></p>
			<p><?= _("Recherchez ici directement des offres de partenariat") ?></p>
		</li>
		<li>
			<img alt="publish" src="img/icons/publish.png" Style="position:absolute; left:0px; width: 64px">
				<p><strong><?= _("Insert a partnership offer") ?></strong></p>
				<p><?= _("Insérez vos propres offres de partenariat") ?></p>
		</li>
		<li>
			<img alt="blog" src="img/icons/blog.png" Style="position:absolute; left:0px; width: 64px">
				<p><strong><?= _("Blog") ?></strong></p>
				<p><?= _("Laissez votre avis, partagez votre experience et enrichissez le réseau myEurope") ?></p>
		</li>
		<li>
			<img alt="info" src="img/icons/info.png" Style="position:absolute; left:0px; width: 64px">
				<p><strong><?= _("Informations") ?></strong></p>
				<p><?= _("Venez récupérer des informations et des liens utiles pour la création de Projet Européen") ?></p>
		</li>
		<li>
			<img alt="profile" src="img/icons/profile<?= $_SESSION['user']->is_guest ? "_guest" : "" ?>.png" Style="position:absolute; left:0px; width: 64px">
			<p><strong><?= _("Profile") ?></strong></p>
			<p><?= _("Complétez et gérez votre profil pour une meilleurs visibilité dans myEurope") ?></p>
		</li>
	</ul>
	<br />	
	<center><a href="#" data-role="button" data-icon="ok" data-inline="true" data-theme="e" data-direction="reverse">Ok</a></center>
</div>

<!-- ----------------- -->
<!-- SEARCH HELP POPUP -->
<!-- ----------------- -->
<div data-role="popup" id="searchHelpPopup" class="ui-content"
	data-overlay-theme="e" data-theme="d">
	<a href="#" data-rel="back" data-role="button" data-theme="d"
		data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
	<ul data-role="listview" data-theme="d">
		<li>Si vous laissez tous les champs <b>vides</b>, vous
		obtenez toutes les offres publiées à ce jour</li>
		<li>Lorsque vous
		laissez une categorie <b>vide</b>, elle n'est pas prise en compte dans la recherche.</li>
		<li>Lorsque vous cochez/ remplissez plusieurs champs dans une catégorie, les 
			résultats matcheront au moins un des critères.</li>
	</ul>
</div>

