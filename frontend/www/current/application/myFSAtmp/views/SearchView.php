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
							<ul data-role="listview" data-filter="true" data-inset="true" data-filter-placeholder="...">
			
							<?php	
// 							echo "<pre>";\
// 							print_r($this->publisher_list);
// 							echo "</pre>";
							
									//foreach( $this->publisher_list as $value ){	
							?>
							
									<li><a href="">
										dzialam<?= $_SESSION['Publish']->key1 ?>
										</a>
									</li>
							</ul>
							
							<div data-role="collapsible" data-collapsed="true">
								<h3>Recherche avancée</h3>
								<form action="#" id="subscribeForm">
									<div>
									<div data-role="fieldcontain" style="margin-left: auto;margin-right: auto;">
										<fieldset data-role="controlgroup" >
											<label for="textinputs1"> Nom de l'organisme bénéficiaire: </label> <input id="textinputs1"  name="nom" placeholder="" value="" type="text" />
										</fieldset>
									</div>
									<div data-role="fieldcontain" style="margin-left: auto;margin-right: auto;">
										<fieldset data-role="controlgroup" >
											<label for="textinputs2"> Libellé du projet: </label> <input id="textinputs2"  name="lib" placeholder="" value="" type="text" />
										</fieldset>
									</div>
									<a href="" type="button" data-icon="gear" onclick="$('#subscribeForm').submit();" style="width:280px;margin-left: auto;margin-right: auto;">rechercher</a></div>
								</form>
							</div>
							<div class="push"></div>
						</div>
					</div>
					<div data-role="footer" data-theme="c" class="footer">
					<div style="text-align: center;">
						<img alt="Alcotra" src="img/alcotra" style="max-height:40px;max-width:100px;vertical-align: middle;" />
						<img alt="Europe" src="img/europe" style="max-height:40px;max-width:100px;vertical-align: middle;" />
						<img alt="Conseil Général 06" src="img/cg06" style="max-height:40px;max-width:100px;vertical-align: middle;" />
						<img alt="Regine Piemonte" src="img/regione" style="max-height:40px;max-width:100px;vertical-align: middle;" />
						<img alt="Région PACA" src="img/PACA" style="max-height:40px;max-width:100px;vertical-align: middle;" />
						<img alt="Prefecture 06" src="img/pref" style="max-height:40px;max-width:100px;vertical-align: middle;" />
						<img alt="Inria" src="img/inria.jpg" style="max-height:40px;max-width:100px;vertical-align: middle;" />
					</div>
				</div>
				</div>
			</body>
		</html>