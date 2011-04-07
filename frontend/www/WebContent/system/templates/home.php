<?php //header("Content-Type:application/xhtml+xml") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
  <head>
  	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
	<!-- TITLE -->
	<title>myMed&gt;<?php $this->getTitle();?></title>
	<?php $this->headTags();?>
	
	<!-- STYLESHEET -->
		<link rel="stylesheet" href="style/desktop/style.css" />
		<!-- define styles of type of elements (ex:h1, p, p.myclass...)-->
		<link rel="stylesheet" href="style/desktop/design.css" />
		<!-- define design of website -->
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
				<img width="200px" alt="profile picture" src="<?= $_SESSION['user']['profile_picture'] ?>" />
			</div>
			
			<!-- USER INFOMATION -->
			<table>
				<tr>
					<th>Name:</th>
					<td> <?= $_SESSION['user']['name'] ?></td>
				</tr>
				<tr>
					<th>Gender:</th>
					<td> <?= $_SESSION['user']['gender'] ?></td>
				</tr>
				<tr>
					<th>Langage:</th>
					<td> <?= $_SESSION['user']['locale'] ?></td>
				</tr>
				<tr>
					<th>Profile from:</th>
					<td> <?= $_SESSION['user']['social_network'] ?></td>
				</tr>
			</table>

				
				<!-- FIRENDS -->
				<!-- FRIENDS STREAM -->
				<div style="background-color: #415b68; color: white; width: 200px; font-size: 15px; font-weight: bold;">my Friends</div>
				<div style="position:relative; height: 150px; width: 200px; overflow: auto; background-color: #edf2f4; top:0px;">
					<?php 
					if(isset($_SESSION['friends'])&&$_SESSION['friends'])
						foreach($_SESSION['friends'] as $value)
							echo'
					<a style="display:block;" href=http://www.facebook.com/#!/profile.php?id='.$value->id.'">'.$value->name.'</a>';?>

				</div>
				
				<!-- DESKTOP -->
				<div id="desktop">
					<?php $this->content();?>
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
			<div style="position:relative; top:20px; height: 1px; background-color: #b0c0e1;"></div>
			<div style="position:relative; top:35px;">"Ensemble par-delà les frontières"</div>
			<!-- LOGOs  -->
			<table style="position:relative; top:40px;">
				<tr>
					<td style="padding: 5px;"><img alt="" src="img/logos/alcotra" style="width: 100px;" /></td>
					<td style="padding: 5px;"><img alt="" src="img/logos/europe" style="width: 50px;" /></td>
					<td style="padding: 5px;"><img alt="" src="img/logos/cg06" style="width: 100px;" /></td>
					<td style="padding: 5px;"><img alt="" src="img/logos/regione" style="width: 100px;" /></td>
					<td style="padding: 5px;"><img alt="" src="img/logos/PACA" style="width: 100px;" /></td>
					<td style="padding: 5px;"><img alt="" src="img/logos/pref" style="width: 70px;" /></td>
					<td style="padding: 5px;"><img alt="" src="img/logos/inria" style="width: 100px;" /></td>
				</tr>
			</table>
		</div>
		<?php $this->scriptTags();?>
		<?php if(defined('DEBUG')&&DEBUG){?>
		<div id="debug">
			<?php printTraces();?>
		</div>
		<?php }?>
    	
    </div>
  </body>
</html>