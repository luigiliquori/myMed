

		<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=650" />
		<title>
		myFSA
		</title>
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js">
		</script>
		<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js">
		</script>
		<script src="app.js">
		</script>
		</head>
		
		<body>
		<div data-role="page" id="Search">
		<div class="wrapper">
		<div data-role="header" data-theme="b">
		<a href=<?= $_SESSION['user']?"option":"authenticate" ?> data-icon="arrow-r" class="ui-btn-left" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
							<h2>myFSA</h2>
							<a href="?action=Publish" data-theme="b" type="button" data-transition="slide" >Publish</a>
						</div>
						<div data-role="content">
						<br />

						<p>
Hello, it's the first time you use the application, please <a href="?action=ExtendedProfile" data-role="button" data-theme="r">complete your profile</a>
</p>
						
						</div>
					</div>
					<div data-role="footer" data-theme="c" class="footer">
					<div style="text-align: center;">

					</div>
				</div>
				</div>
			</body>
		</html>