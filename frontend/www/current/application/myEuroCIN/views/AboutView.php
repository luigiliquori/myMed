<!-- ------------------ -->
<!-- App About View     -->
<!-- ------------------ -->


<!-- Page view -->
<div data-role="page" id="aboutView" >


	<!-- Header bar -->
 	<? $title = _("Credits"); print_header_bar(true, false, $title); ?>

 	
	<!-- Page content -->
	<div data-role="content" class="content">
	
		<!-- <a href="/application/myMed/" rel="external"><img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" style="margin-top: -15px;"/></a>-->
		<img alt="<?= APPLICATION_NAME ?>" src="img/icon.png" style="height: 50px; margin-top: -15px;" />
		<h1 style="display: inline-block;vertical-align: 20%;"><?= APPLICATION_NAME ?></h1>
		<!-- <h3 style="margin-top: -5px;"><?= _(APPLICATION_LABEL) ?></h3>-->
		<br>
		<div data-role="collapsible" data-theme="d" data-collapsed="false" data-content-theme="d">
			<p style='text-align:left'>
				<?= _("<b>myEuroCIN Application</b><br/><br/><br/>
						EURO C.I.N. GEIE (Gruppo Europeo di Interesse Economico) fondato nel 1994, dalle Camere di commercio transfrontaliere di Cuneo, Imperia e Nizza, si compone oggi di istituzioni e aziende che rappresentano i territori di Piemonte, Liguria e Provence Alpes Côte d’Azur. Oltre ai tre fondatori (Cuneo, Imperia e Nizza) gli altri membri sono le Camere di commercio di Asti, Alessandria, Genova, Unioncamere Piemonte, Autorità Portuale di Savona, Comune di Cuneo, BRE Banca Cuneo e GEAC Spa.
						Tra gli obiettivi del Gruppo la volontà di creare un’immagine globale e comune all’interno e all’esterno dell’Euroregione (chiamata delle Alpi del Mare) favorendo l’integrazione economica, culturale e scientifica, lo sviluppo dei flussi transfrontalieri e la promozione dei territori che ne fanno parte con le loro peculiarità e tradizioni.
						Attraverso myEurocin, il Gruppo si propone di presentare e suggerire ai visitatori le mete più suggestive, contemplando natura e benessere, curiosità storiche e artistiche e i numerosi prodotti tipici che caratterizzano l’Euroregione.
						I contenuti di myEurocin sono liberamente consultabili dai visitatori, ai quali chiediamo la loro collaborazione per migliorare l’applicazione, fornendo informazioni e suggerimenti. Ricordiamo agli utenti che l’inserimento di nuovi contenuti è possibile previa autenticazione al sito.
						<br/><br/> ") ?>
			</p>
			<p>
				<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank"><?= _("More informations") ?></a>
			</p>
			<p>
				<?= _("If you find an error you can report it at this address: ")?><a href="http://mymed22.sophia.inria.fr/bugreport/index.php" target="_blank"> http://mymed22.sophia.inria.fr/bugreport/index.php </a>
			</p>
			<br/>
			<br/>
			
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
	</div>

</div>
