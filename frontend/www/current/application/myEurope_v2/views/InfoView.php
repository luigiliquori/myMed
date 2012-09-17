
<div id="info" data-role="page">

<? include("header-bar.php"); ?>

	<div data-role="content">

		
		<div data-role="collapsible-set" data-theme="b" data-content-theme="d">
			<div  data-role="collapsible" data-collapsed="false">
				<h3>Rechercher</h3>
			
				<ul data-role="listview" data-filter="ture" >
				
					<li data-role="list-divider"><b>Programmes de coopération</b> dont peuvent bénéficier les territoires de l'Eurorégion Alpes-Méditerranée :</li>
				
					<li><a class="ULitem" href="#alcotra"><h3>Programme transfrontalier Alcotra</h3><p>Programmes de coopération</p></a></li>
					<li><a class="ULitem" href="#Maritime"><h3>Programme transfrontalier Italie-France Maritime</h3><p>Programmes de coopération</p></a></li>
				    <li><a class="ULitem" href="#IEVP"><h3>Programme transfrontalier IEVP CT MED</h3><p>Programmes de coopération</p></a></li>
					<li><a class="ULitem" href="#MED"><h3>Programme transnational MED</h3><p>Programmes de coopération</p></a></li>
					<li><a class="ULitem" href="#Espace Alpin"><h3>Programme transnational Espace Alpin</h3><p>Programmes de coopération</p></a></li>
					<li><a class="ULitem" href="#Interreg"><h3>Programme interrégional Interreg IV C</h3><p>Programmes de coopération</p></a></li>
					
					<li data-role="list-divider"><b>Fonds structurels européens</b> que vous pouvez saisir pour bénéficier d'un financement de vos projets : </li>
				
					<li><a class="ULitem" href="#FEDER"><h3>Programme opérationnel FEDER </h3><p>Fonds structurels européens</p></a></li>
					<li><a class="ULitem" href="#FSE"><h3>Programme opérationnel FSE</h3><p>Fonds structurels européens</p></a></li>
					<li><a class="ULitem" href="#FEADER"><h3>Programme de développement Rural Hexagonal FEADER</h3><p>Fonds structurels européens</p></a></li>
					
					<li data-role="list-divider">Informations relatives aux futurs programmes 2014-2020 :</li>

					<li><a class="ULitem" href="#Recherche"><h3>Horizon 2020-Le futur Programme Cadre pour la Recherche et l'Innovation </h3><p>futurs programmes 2014-2020</p></a></li>
				</ul>
			</div>
			
			<div  data-role="collapsible" data-collapsed="true">
				<h3>Ajouter</h3>
				
				<div >
					<input id="textinputp3" name="title" placeholder="<?= _("Titre") ?>" value='' type="text" />
				</div>
				
				<select name="call" id="call">
					<option value="coop">Programmes de coopération dont peuvent bénéficier les territoires de l'Eurorégion Alpes-Méditerranée</option>
					<option value="fond">Fonds structurels européens que vous pouvez saisir pour bénéficier d'un financement de vos projets</option>
					<option value="info">Informations relatives aux futurs programmes 2014-2020</option>
				</select>
				
				<textarea id="CLEeditor2" name="text"></textarea>

				<div style="text-align: center;" >
					<a href="#post1" data-role="button" data-inline="true" data-icon="check" data-theme="g"><?=_('Insert') ?></a>
				</div>
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