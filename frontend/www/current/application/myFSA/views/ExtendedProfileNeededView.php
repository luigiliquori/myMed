<? include("header.php"); ?>
		
		<body>
		<div data-role="page" id="Search" data-theme="a">
		<div class="wrapper">
		<div data-role="header" data-theme="a">
		<a href=<?= $_SESSION['user']?"option":"authenticate" ?> data-icon="arrow-r" class="ui-btn-left" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
							<h2>myFSA</h2>
							<a href="?action=Publish" type="button" data-transition="slide" >Publish</a>
						</div>
						<div data-role="content">
						<br />

						<p>
						To use all options in myFSA please fill extended profile
 						<a href="?action=ExtendedProfile" data-role="button">complete your profile</a>
						</p>
						
						</div>
					</div>
				<? include("footer.php"); ?>
				</div>
			</body>
		</html>
