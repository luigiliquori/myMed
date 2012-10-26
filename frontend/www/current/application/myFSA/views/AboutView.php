
<? require_once("header.php"); ?>

<div data-role="page" id="about" >	
	
	<div data-role="header" data-theme="b" data-position="fixed">
	
	<h1 style="color: white;">A propos</h1>	
	<span style="position: absolute;right: 3px;top: -3px;opacity: 0.6;">
		<a class="social" style="background-position: -33px 0px;" href="https://plus.google.com/u/0/101253244628163302593/posts" title="myFSA on Google+"></a>
		<a class="social" style="background-position: -66px 0px;" href="http://www.facebook.com/pages/MyFSA/122386814581009" title="myFSA on Facebook"></a>
		<a class="social" style="background-position: 0px 0px;" href="https://twitter.com/my_europe" title="myFSA on Twitter"></a>
	</span>
	<? include("notifications.php")?>
	</div>

	<div data-role="content" class="content">
	
		<img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" />
		<br />
		<h3> Réseau Social Transfrontalier </h3>
		<br />
		<p>
		Le projet myMed est né d’une double constatation: l’existence d’un énorme potentiel de développement des activités économiques de la zone transfrontalière, objet de l’action Alcotra, et le manque criant d’infrastructures techniquement avancées en permettant un développement harmonieux. La proposition myMed est née d’une collaboration existante depuis plus de 15 ans entre l’Institut National de Recherche en Informatique et en Automatique (INRIA) de Sophia Antipolis et l’Ecole Polytechnique de Turin, auxquels viennent s’ajouter deux autres partenaires, l’Université de Turin et l’Université du Piémont Oriental.
		</p>
		<p>
		<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank">Plus d'informations</a>
		</p>
		<br />
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="#login" data-transition="none" data-back="true" data-icon="home">Connexion</a></li>
				<li><a href="#register" data-transition="none" data-back="true" data-icon="grid" >Inscription</a></li>
				<li><a href="#about" data-transition="none" data-icon="info" class="ui-btn-active ui-state-persist">A propos</a></li>
			</ul>
		</div>
	</div>
</div>

<? require_once("LoginView.php"); ?>

<? require_once("footer.php"); ?>
