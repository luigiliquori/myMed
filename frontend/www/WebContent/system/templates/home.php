<?php //header("Content-Type:application/xhtml+xml") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>myMed<?php if($this->isContent())echo '&gt;';$this->getTitle();?></title>
		<script src="<?=ROOTPATH?>jquery/dist/jquery.js"></script>
<?php $this->headTags();?>
		<!-- define styles of type of elements (ex:h1, p, p.myclass...)-->
		<link rel="stylesheet" href="style/desktop/style.css" />
		<!-- define design of website -->
		<link rel="stylesheet" href="style/desktop/design.css" />
		<script type="text/javascript" src="javascript/jquery.textPlaceholder.js"></script>
		<script type="text/javascript" src="http://cdn.jquerytools.org/1.2.5/form/jquery.tools.min.js"></script>
		<script type="text/javascript">
		//<![CDATA[
		$.tools.dateinput.localize("fr", {
			months:        'janvier,février,mars,avril,mai,juin,juillet,août,septembre,octobre,novembre,décembre',
			shortMonths:   'jan,fév,mar,avr,mai,jun,jul,aoû,sep,oct,nov,déc',
			days:          'dimanche,lundi,mardi,mercredi,jeudi,vendredi,samedi',
			shortDays:     'dim,lun,mar,mer,jeu,ven,sam'
		});
		//]]>
		</script>
	</head>
	<body>
		<?php printError();?>
		<div id="header">
			<div class="innerContent">
				<ul>
					<li><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static1/projet">Documentation</a></li>
					<li><a href="http://mymed2.sophia.inria.fr:4242/">Forum</a></li>
					<li><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static4/join">Contact</a></li>
					<li><a href="http://www.mymed.fr">Blog</a></li>
				</ul>
<?php if(USER_CONNECTED):?>
				<form id="logout" method="post" action=""><input style="cursor:pointer;border:0 none;background:transparent none;font:inherit;" type="submit" name="logout" value="Déconnexion" /></form>
<?php endif;?>
			</div>
		</div>
		<div id="titles">
			<div class="innerContent">
<?php if($this->isContent()):?>
				<h1><?php $this->getTitle();?></h1>
<?php else:?>
				<!-- Connection -->
		  		<div id="connexion" class="title">
		  			<div>Connectez-vous avec votre compte&nbsp;: <div class="buttonList"><div class="main"><?php $this->mainButtons();?></div><div class="minor"><?php $this->minorButtons();?></div></div></div>
		  			<div>Ou simplement en tant que&nbsp;: <?php $this->guestButton();?></div>
		  		</div>
<?php endif;?>
			</div>
		</div>
		<div id="content">
<?php if($this->isContent()&&(!defined('CONTENTOBJECT')||CONTENTOBJECT!=='OpenIdProvider/OpenIdProvider')):?>
			<!-- USERINFO -->
			<!-- USER PICTURE PROFILE -->
			<div style="width: 200px; height: 300px; background-color: #edf2f4;">
				<img width="200px" alt="profile picture" src="<?= $_SESSION['user']->profilePicture?$_SESSION['user']->profilePicture:'http://graph.facebook.com//picture?type=large' ?>" /><br />
			</div>
			<!-- USER INFOMATION -->
			<table>
				<tr>
					<th align="left">Nom&#160;:</th>
					<td><?= $_SESSION['user']->name?$_SESSION['user']->name:'Unknown' ?></td>
				</tr>
				<tr>
					<th align="left">Sexe&#160;:</th>
					<td><?= $_SESSION['user']->gender?$_SESSION['user']->gender=='F'?'Femme':'Homme':'Unknown'?></td>
				</tr>
				<!--tr>
					<th align="left">Date de naissance&#160;:</th>
					<td><?= $_SESSION['user']->birthday?$_SESSION['user']->birthday:'Unknown' ?></td>
				</tr-->
				<tr>
					<th align="left">Ville&#160;:</th>
					<td><?= $_SESSION['user']->hometown?$_SESSION['user']->hometown:'Unknown' ?></td>
				</tr>
				<tr>
					<th align="left">Profile&#160;:</th>
					<td>
					   <?php
					   	if($_SESSION['user']->link)
					   		echo '
					   		<a href="'.$_SESSION['user']->link.'">'.$_SESSION['user']->socialNetworkName.'</a>';
					   	else
					   		echo '
					   		'.$_SESSION['user']->socialNetworkName;
					   ?>
					</td>
				</tr>
			</table>
			
			<!-- FIRENDS -->
			<!-- FRIENDS STREAM -->
			<div style="background-color: #415b68; color: white; width: 200px; font-size: 15px; font-weight: bold;">my Friends</div>
			<div style="position:relative; height: 150px; width: 200px; overflow: auto; background-color: #edf2f4; top:0px;">
				<?php 
				if(isset($_SESSION['friends'])&&count($_SESSION['friends'])>0)
				{
					usort($_SESSION['friends'], function($f1, $f2)
					{
						return strcasecmp($f1['displayName'], $f2['displayName']);
					});
					echo '
						<ul>';
					foreach ($_SESSION['friends'] as $value)
						if(isset($value['profileUrl']))
							echo '
							<li><a href="'.htmlspecialchars($value['profileUrl']).'">'.htmlspecialchars($value['displayName']).'</a></li>';
						else
							echo '
							<li>'.htmlspecialchars($value['displayName']).'</li>';
					echo '
						</ul>';
				}?>
			</div>
				
			<!-- DESKTOP -->
<?php 	if(isset($_GET['service']) && $_GET['service']!='Desktop'):?>
			<a href="?" style="left: 230px;position: absolute;top: 12px;">&lt;&lt; Retourner au bureau</a>
<?php 	endif;?>
			<div id="desktop">
				<div<?=isset($_GET['service'])?' id="'.$_GET['service'].'"':''?>>
					<?php $this->content();?>
				</div>
			</div>
<?php elseif(defined('CONTENTOBJECT')&&CONTENTOBJECT==='OpenIdProvider/OpenIdProvider'):?>
			<div id="OpenIdProvider">
				<?php $this->content();?>
			</div>
<?php else:?>
			<div id="description_myMed">
				<!--img alt="méta réseau social" src="style/img/entonnoire.png" />
				<img alt="nuage de services" src="style/img/services_cloud.png" /-->
				<h2 class="title">myMed se joint à votre réseau social préféré 
pour ajouter de nouvelles fonctionnalités&nbsp;!</h2>
				<img alt="Réseau P2P" src="style/img/NetworkView.png" />
			</div>
			<div id="inscription">
				<h2 class="title">
					Vous n'êtes pas encore abonnés à un réseau social&nbsp;?<br />
					myMed en fourni un pour vous&nbsp;!
				</h2>
				<form action="#" method="post">
					<div>
						<div><label for="nom">Nom</label><input type="text" name="nom" id="nom" /></div>
						<div><label for="prenom">Prénom</label><input type="text" name="prenom" id="prenom" /></div>
						<div><label for="email">eMail</label><input type="text" name="email" id="email" /></div>
						<div><label for="password">Mot de pass</label><input type="password" name="password" id="password" /></div>
						<div><label for="confirm">Confirmation</label><input type="password" name="confirm" id="confirm" /></div>
						<div>
							<label for="gender">Je suis</label>
							<select name="gender" id="gender" style="background-color: white; border: none;">
							    <option value="Homme" default="default">Homme</option>
							    <option value="Femme">Femme</option>
							    <option value="Autre">Autre</option>
						    </select>
						</div>
					</div>
					<button type="submit"><span>inscription</span></button>
				</form>
				<div id="bloglink">
					<img alt="" src="style/img/logo-mymed.jpg" width="200px;" />
					<a href="http://www.mymed.fr">Réseau Social Transfrontalier</a>
				</div>
			</div>
<?php endif;?>
    	</div>
    	<div id="languages">
			<div class="innerContent">
	    		<ul>
					<li xml:lang="fr" lang="fr">Francais</li>
					<li xml:lang="en" lang="en"><a href="javascript:alert('Not yet avalaible')">English</a></li>
					<li xml:lang="it" lang="it"><a href="javascript:alert('Non ancora disponibile')">Italiano</a></li>
	    		</ul>
			</div>
    	</div>
    	<div id="footer">
			<div class="innerContent">
				<p>"Ensemble par-delà les frontières"</p>
				<img alt="Alcotra"				src="style/img/logos/alcotra"	style="width: 100px;" />
				<img alt="Europe"				src="style/img/logos/europe"	style="width: 50px;" />
				<img alt="Conseil Général 06"	src="style/img/logos/cg06"		style="width: 100px;" />
				<img alt="Regine Piemonte"		src="style/img/logos/regione"	style="width: 100px;" />
				<img alt="Région PACA"			src="style/img/logos/PACA"		style="width: 100px;" />
				<img alt="Prefecture 06"		src="style/img/logos/pref"		style="width: 70px;" />
				<img alt="Inria"				src="style/img/logos/inria"		style="width: 100px;" />
			</div>
		</div>
<?php $this->scriptTags();?>
		<script type="text/javascript">
		$("[placeholder]").textPlaceholder();
		$(":date").dateinput({lang:'fr', format:'yyyy-mm-dd', firstDay:1/*, selectors:true*/});
		</script>
<?php if(defined('DEBUG')&&DEBUG):?>
		<div id="debug">
<?php printTraces();?>
		</div>
<?php endif;?>
	</body>
</html>
