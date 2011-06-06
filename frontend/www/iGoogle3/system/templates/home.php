<?php 
if(defined('MIMETYPE_XHTML')&&MIMETYPE_XHTML)
	header("Content-Type:application/xhtml+xml; charset=utf-8");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>myMed</title>
<?php $this->headTags();?>
		<!-- STYLESHEET -->
		<!-- define styles of type of elements (ex:h1, p, p.myclass...)-->
		<link rel="stylesheet" href="style/desktop/style.css" />
		<!-- define design of website -->
		<link rel="stylesheet" href="style/desktop/design.css" />
		<!-- define styles of zoom tool-->
		<link rel="stylesheet" href="style/zoom.css" />
		
		<script type="text/javascript" src="ecmapatch/EventListener.js"></script>
		<script type="text/javascript" src="ecmapatch/XMLHttpRequest.js"></script>
		<script type="text/javascript" src="ecmapatch/getElementsByClassName.js"></script>
		<script type="text/javascript" src="ecmapatch/importNode.js"></script>
		<script type="text/javascript" src="ecmapatch/getComputedStyle.js"></script>
		
		<script type="text/javascript" src="javascript/zoom.js"></script>
		
		<script type="text/javascript" src="javascript/jquery/dist/jquery.js"></script>
		<script type="text/javascript" src="javascript/jquery.textPlaceholder.js"></script>
		<script type="text/javascript" src="javascript/display.js"></script>
		
		<!--[if IE]>
		<script type="text/javascript">ie_version=parseFloat(navigator.appVersion.split("MSIE")[1]);</script>
		<![endif]-->
	</head>
	<body>
		<?php printError();?>
		<div id="header">
			<h1>myMed v1.0 alpha</h1>
			<ul class="menu">
				<li><a href="#">WebPage</a></li>
				<li><a href="#">Blog</a></li>
				<li><a href="#">Forum</a></li>
				<li><a href="#">Contact</a></li>
			</ul>
			<div class="connexion<?=isset($_GET['connexion'])?' form':''?>">
<?php if(USER_CONNECTED):
	echo $_SESSION['user']->name?> |
					<form method="post" action="" class="logout">
						<div><input type="submit" name="logout" value="Déconnexion" /></div>
					</form>
<?php else :?>
				<span>Connexion</span>
				<div class="connexionList">
<?php				$this->button();?>
				</div>
<?php endif;?>
			</div>
		</div>
	  	<div id="search">
	  		<a href="<?=ROOTPATH?>" class="homeLink"><span>Retour accueil</span></a>
<?php 
	  		$addQuery	= isset($_GET['q'])&&isset($_GET['service'])&&$_GET['service']=='myStore'?'&amp;q='.$_GET['q']:'';
	  		?>
	  		<ul>
	  			<li class="all"><a href="?service=myStore<?=$addQuery?>">Tous</a></li>
	  			<li><a href="?service=myStore&amp;cat=news<?=$addQuery?>">Actualités</a></li>
	  			<li><a href="?service=myStore&amp;cat=entertainment<?=$addQuery?>">Divertissement</a></li>
	  			<li><a href="?service=myStore&amp;cat=sport<?=$addQuery?>">Sport</a></li>
	  			<li><a href="?service=myStore&amp;cat=culture<?=$addQuery?>">Culture</a></li>
	  			<li><a href="?service=myStore&amp;cat=other<?=$addQuery?>">Divers</a></li>
	  		</ul>
	  		<form method="get" action="">
	  			<div>
	  				<input type="hidden" name="service" value="myStore" />
<?php if(isset($_GET['cat'])&&isset($_GET['service'])&&$_GET['service']=='myStore'):?>
	  				<input type="hidden" name="cat" value="<?=$_GET['cat']?>" />
<?php endif;?>
	  				<input class="query" name="q" value="<?=isset($_GET['q'])?$_GET['q']:''?>" />
	  				<input type="submit" value="Rechercher une application" />
	  			</div>
	  		</form>
	    </div>
	    <div id="content">
	    	<div id="<?php $this->getServiceName()?>">
<?php			$this->content();?>
	    	</div>
		</div>
		<div id="footer">
		 	<ul class="languages">
		 		<li class="active"><a href="#">Francais</a></li>
		 		<li><a href="#">English</a></li>
		 		<li><a href="#">Italiano</a></li>
		 	</ul>
		 	<div class="slogan">"Ensemble par-delà les frontières"</div>
		</div>
<?php	$this->scriptTags();?>
		<script type="text/javascript">new zoom.Zoom(69);</script>
		<script type="text/javascript">$("[placeholder]").textPlaceholder();</script>
<?php if(defined('DEBUG')&&DEBUG):?>
		<div id="debug">
			<?php printTraces();?>
		</div>
<?php endif;?>
  </body>
</html>
