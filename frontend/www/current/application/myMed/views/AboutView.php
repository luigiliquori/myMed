<? require_once("header.php");

function tab_bar_login($activeTab) {
	if(!function_exists('tabs')) {
		function tabs($activeTab, $tabs, $opts = false){
			return tabs_default($activeTab, $tabs, $opts);
		}
	}
	tabs($activeTab, array(
		array("?action=login", "Sign in", "signin"),
		array("?action=register&method=showRegisterView", "Create an account", "th-list"),
		array("#aboutView", "About", "info-sign")
	));
}
?>

<div data-role="page" id="aboutView" >	
	<!-- Page Header -->
	<?php tab_bar_login("#aboutView"); ?>
	   
	<div data-role="content" class="content">
	
		<a href="/application/myMed/" rel="external"><img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" style="margin-top: -15px;"/></a>
		<h3 style="margin-top: -5px;"><?= _(APPLICATION_LABEL) ?></h3>
		<br>
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d">
			<li>
			<p>
			<?= _(APPLICATION_NAME."about") ?>
			</p>
			</li>
		</ul>
		<p>
		<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank"><?= _("More informations") ?></a>
		</p>
		<br>
		<p>
			<?= _("The myMed consortium is composed by INRIA, Nice Sophia-Antipolis University, Politecnico di Torino, Turin University, and Piémont Oriental University, and it is founded by:")?>
		</p>
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
	</div>

</div>
<? include_once("footer.php"); ?>