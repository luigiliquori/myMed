<? include("header.php"); ?>

<div data-role="page">

	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
  			<ul>
  				<li><a href="?action=logout" rel="external" data-icon="back"><?= _("Exit") ?></a></li>
  				<li><a href="./" rel="external" data-icon="<?= APPLICATION_NAME ?>"><?= APPLICATION_NAME ?></a></li>
  				<li><a href="?action=extendedProfile" rel="external" data-icon="profile"><?= $_SESSION['user']->name ?></a></li>
  			</ul>
  		</div>
	</div>

	<div data-role="content" >
		<br />
		<?= _("Your partnership offer has been successfully published on") ?>
		 <?= APPLICATION_NAME ?>,
		 <br /><br />
		
		<a href="?action=search<?= $this->req ?>" data-ajax="false" type="button" data-inline="true"> <?= _("See similar offers") ?> </a><br />
		 
	</div>
</div>

<? include("footer.php"); ?>