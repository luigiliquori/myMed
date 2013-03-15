
<? require_once("header.php"); ?>
</head>
<body>
	<div data-role="page" id="about" >	
		
		<div data-role="header" data-theme="b" data-position="fixed">
			<h1 style="color: white;"><?= _("About")?></h1>	
		</div>
	

		<div data-role="content" class="content">
		
			<img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" />
			<h3> Réseau Social Transfrontalier </h3>
			
			<br />
			<div data-role="collapsible" data-theme="d" data-collapsed="false" data-content-theme="d">
				<p>Le projet myMed est né d’une double constatation: l’existence d’un énorme potentiel de développement des activités économiques de la zone transfrontalière, objet de l’action Alcotra, et le manque criant d’infrastructures techniquement avancées en permettant un développement harmonieux. La proposition myMed est née d’une collaboration existante depuis plus de 15 ans entre l’Institut National de Recherche en Informatique et en Automatique (INRIA) de Sophia Antipolis et l’Ecole Polytechnique de Turin, auxquels viennent s’ajouter deux autres partenaires, l’Université de Turin et l’Université du Piémont Oriental.
				</p>
				<p>
					<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank">Plus d'informations</a>
				</p>
				<br />
				<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
			</div>
		</div>
<? include("footer.php"); ?>
	</div>
</body>
