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
		<img alt="<?= APPLICATION_NAME ?>" src="img/icons/icon.png" style="height: 50px; margin-top: -15px;" />
		<h1 style="display: inline-block;vertical-align: 20%;"><?= APPLICATION_NAME ?></h1>
		<!-- <h3 style="margin-top: -5px;"><?= _(APPLICATION_LABEL) ?></h3>-->
		<br>
		<div data-role="collapsible" data-theme="d" data-collapsed="false" data-content-theme="d">
			<p style='text-align:left'>
				<?= _("Credit description") ?>
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
