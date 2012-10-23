<? include("header.php"); ?>
<body>
	<div data-role="page" id="Search" data-theme="b">
		   <div class="wrapper">
				<div data-role="header" data-theme="b" data-position="fixed">
					<h1><?= APPLICATION_NAME ?></h1>
					<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-theme="r" data-icon="power" data-iconpos="notext">Deconnexion</a>
					<? include("notifications.php")?>
				</div>
					
				<div data-role="content">
					<br />
					<p>Pour utiliser toutes les fonctionnalités de myFSA remplissez s'il vous plait votre profil étendu 
 						<a href="?action=ExtendedProfile" data-role="button">Compléter votre profil</a>
					</p>
				</div>
			</div>
			
			<? include("footer.php"); ?>
	</div>
</body>
</html>
