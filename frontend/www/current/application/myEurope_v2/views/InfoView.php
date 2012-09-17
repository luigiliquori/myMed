
<div id="info" data-role="page">

<? include("header-bar.php"); ?>

	<div data-role="content">

		
		<div data-role="collapsible-set" data-theme="b" data-content-theme="d">
		
			<div data-role="collapsible" data-collapsed="false">
				<h3><b>Programmes de coopération</b> dont peuvent bénéficier les territoires de l'Eurorégion Alpes-Méditerranée :</h3>
				<ul data-role="listview">
					<li><a href="#alcotra">Programme transfrontalier Alcotra </a></li>
					<li><a href="#Maritime">Programme transfrontalier Italie-France Maritime</a></li>
				    <li><a href="#IEVP">Programme transfrontalier IEVP CT MED</a></li>
					<li><a href="#MED">Programme transnational MED</a></li>
					<li><a href="#Espace Alpin">Programme transnational Espace Alpin</a></li>
					<li><a href="#Interreg">Programme interrégional Interreg IV C</a></li>
				</ul>
			</div>
			
			<div data-role="collapsible">
				<h3><b>Fonds structurels européens</b> que vous pouvez saisir pour bénéficier d'un financement de vos projets :</h3>
				<ul data-role="listview">
					<li><a href="#FEDER">Programme opérationnel FEDER </a></li>
					<li><a href="#FSE">Programme opérationnel FSE</a></li>
					<li><a href="#FEADER">Programme de développement Rural Hexagonal FEADER</a></li>
				</ul>
			</div>
			
			<div data-role="collapsible">
				<h3>Informations relatives aux futurs programmes 2014-2020 :</h3>
				<ul data-role="listview">
					<li><a href="#Recherche">Horizon 2020-Le futur Programme Cadre pour la Recherche et l'Innovation </a></li>
				</ul>
			</div>
			
		</div>
	
	</div>

	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-icon="search">Partenariats</a></li>
				<li><a href="#info" data-transition="none" data-back="true" data-icon="info" class="ui-btn-active ui-state-persist">Informations</a></li>
				<li><a href="#store" data-transition="none" data-icon="grid">Journal</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user">Profil</a></li>
			</ul>
		</div>
	</div>

</div>



<? if(!empty($_SESSION['user']->lang)):?>
	<? include("LangView.php"); ?>
<? endif ?>