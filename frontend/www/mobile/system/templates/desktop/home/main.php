<?php 
require_once 'system/templates/mobile/home/Favorite.class.php';
require_once 'system/templates/mobile/home/Category.class.php';
require_once 'system/templates/mobile/home/Top10.class.php';
require_once dirname(__FILE__).'/Profile.class.php';
require_once dirname(__FILE__).'/News.class.php';
require_once dirname(__FILE__).'/Notification.class.php';
require_once 'system/templates/mobile/home/handler/MenuHandler.class.php';

$menuHandler = new MenuHandler();
$menuHandler->handleRequest();
?>

<!-- Disconnect the user -->
<form action="#" method="post" name="disconnectForm" id="disconnectForm">
	<input type="hidden" name="disconnect" value="1" />
</form>

<div id="header"></div>

<!-- HEADER -->
<table id="menu" style="position: absolute; left: 20%; top: 0px;">
	<tr>
		<td><img alt="title" src="system/templates/desktop/home/img/title.png" height="30" /></td>
		<td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static1/projet">Documentation</a></td>
		<td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static4/join">Contact</a></td>
		<td><a href="http://www.mymed.fr">Blog</a></td>
	</tr>
</table>

<?php 
$profile = new Profile();
$profile->printTemplate(); 

$theme = "d";
$css = "position: absolute; top:120px; left:30%; width:40%; border: thin #d0d0d0 solid; padding:5px;";

$favorite = new Favorite();
$favorite->printTemplate($theme, $css);
$top10 = new Top10();
$top10->printTemplate($theme, $css);
$category = new Category();
$category->printTemplate($theme, $css);

$notification = new Notification();
$notification->printTemplate();

include('updateProfile.php');
?>
