<? include("header.php"); ?>
<div data-role="page">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
		<p>
			Hello <?= $this->user->name ?> Welcome to the main page !
		</p>
	
		<a href="?action=logout" rel="external" data-role="button" data-theme="r">Déconnexion</a>
		
	</div>

</div>

<? include("footer.php"); ?>