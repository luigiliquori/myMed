<?php //header("Content-Type:application/xhtml+xml") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
  <head>
  	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
	<!-- TITLE -->
	<title>myMed&gt;<?php $this->getTitle();?></title>
	<script src="jquery/dist/jquery.js"></script>
	<?php $this->headTags();?>
	
	<!-- STYLESHEET -->
		<link rel="stylesheet" href="style/desktop/style.css" />
		<!-- define styles of type of elements (ex:h1, p, p.myclass...)-->
		<link rel="stylesheet" href="style/desktop/design.css" />
		<!-- define design of website -->
		<script type="text/javascript" src="javascript/jquery.textPlaceholder.js"></script>
  </head>
  <body>
  	<!-- FORMAT -->
    <div align="center">
	  	
	  	<!-- HEADER -->
		<div id="header">
			<!-- MENU -->
			<table>
				<tr>
					<td><img alt="" src="style/img/title.png" height="30" /></td>
					<td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static1/projet">Documentation</a></td>
					<td><a href="http://mymed2.sophia.inria.fr:4242/">Forum</a></td>
					<td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static4/join">Contact</a></td>
					<td><a href="http://www.mymed.fr">Blog</a></td>
					<td style="width: 400px;"></td>
					<td>
						<form id="logout" method="post" action=""><input style="cursor:pointer;border:0 none;background:transparent none;font:inherit;" type="submit" name="logout" value="Déconnexion" /></form>
					</td> 
				</tr>
			</table>
			<div style="position: relative; width: 900px; text-align: left; margin-top: 10px; font-size:30px;">
				<?php $this->getTitle();?>
			</div>
		</div>
	  	
	  	<!-- CONTENT -->
	  	<div id="content">
	  		
		  		<!-- USERINFO -->
			<!-- USER PICTURE PROFILE -->
			<div style="width: 200px; height: 300px; background-color: #edf2f4;">
					<img width="200px" alt="profile picture" src="<?= $_SESSION['user']['profile_picture']?$_SESSION['user']['profile_picture']:'http://graph.facebook.com//picture?type=large' ?>" /><br />
			</div>
			
			<!-- USER INFOMATION -->
			<table>
				<tr>
					<th>Name:</th>
					  <td> <?= $_SESSION['user']['name']?$_SESSION['user']['name']:'Unknown' ?></td>
					</tr>
					<tr>
					  <th>Gender:</th>
					  <td> <?= $_SESSION['user']['gender']?$_SESSION['user']['gender']:'Unknown' ?></td>
					</tr>
					<tr>
					  <th>Langage:</th>
					  <td> <?= $_SESSION['user']['locale']?$_SESSION['user']['locale']:'Unknown' ?></td>
					 </tr>
					<tr>
					   <th>Profile from:</th>
					   <td> <?= $_SESSION['user']['social_network']?$_SESSION['user']['social_network']:'Unknown' ?></td>
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
				<?php if(isset($_GET['service']) && $_GET['service']!='Desktop'):?>
				<a href="?" style="left: 230px;position: absolute;top: 12px;">&lt;&lt; Retourner au bureau</a>
				<?php endif;?>
				<div id="desktop">
					<div<?=isset($_GET['service'])?' id="'.$_GET['service'].'"':''?>>
						<?php $this->content();?>
					</div>
				</div>
			
				<!-- SERVICES -->
				
				<!-- GOOGLE MAP -->
	    	
    	</div>
    	<!-- FOOTER -->
    	<div id="footer">
			<div style="position:relative; text-align: left; top:10px;">
				<a href="#"><b>Francais</b></a> |
				<a href="#" style="color: gray;">English</a> | 
				<a href="#" style="color: gray;">Italiano</a>
			</div>
			<!--div style="position:relative; top:20px; height: 1px; background-color: #b0c0e1;"></div>
			<div style="position:relative; top:35px;">"Ensemble par-delà les frontières"</div>
			<table style="position:relative; top:40px;">
				<tr>
					<td style="padding: 5px;"><img alt="" src="style/img/logos/alcotra" style="width: 100px;" /></td>
					<td style="padding: 5px;"><img alt="" src="style/img/logos/europe" style="width: 50px;" /></td>
					<td style="padding: 5px;"><img alt="" src="style/img/logos/cg06" style="width: 100px;" /></td>
					<td style="padding: 5px;"><img alt="" src="style/img/logos/regione" style="width: 100px;" /></td>
					<td style="padding: 5px;"><img alt="" src="style/img/logos/PACA" style="width: 100px;" /></td>
					<td style="padding: 5px;"><img alt="" src="style/img/logos/pref" style="width: 70px;" /></td>
					<td style="padding: 5px;"><img alt="" src="style/img/logos/inria" style="width: 100px;" /></td>
				</tr>
			</table-->
		</div>
		<?php $this->scriptTags();?>
		<script type="text/javascript">$("[placeholder]").textPlaceholder();</script>
		<?php if(defined('DEBUG')&&DEBUG){?>
		<div id="debug">
			<?php printTraces();?>
		</div>
		<?php }?>
    	
    </div>
  </body>
</html>