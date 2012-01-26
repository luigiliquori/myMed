<?php 
require_once 'system/templates/container/Container.class.php';
require_once 'system/templates/handler/MenuHandler.class.php';

require_once dirname(__FILE__).'/Profile.class.php';
require_once dirname(__FILE__).'/Wall.class.php';
require_once dirname(__FILE__).'/Friends.class.php';
require_once dirname(__FILE__).'/Notification.class.php';

$menuHandler = new MenuHandler();
$menuHandler->handleRequest();

$wall = new Wall();
$wall->printTemplate();

?>

<!-- Disconnect the user -->
<form action="#" method="post" name="disconnectForm" id="disconnectForm">
	<input type="hidden" name="disconnect" value="1" />
</form>

<div id="header"></div>

<!-- HEADER -->
<table id="menu" style="position: absolute; left: 20%; top: 7px;">
	<tr>
		<td><img alt="title" src="system/templates/container/desktop/login/img/title.png" height="30" /></td>
		<td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static1/projet">Documentation</a></td>
		<td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static4/join">Contact</a></td>
		<td><a href="http://www.mymed.fr">Blog</a></td>
	</tr>
</table>

<?php 
// include('updateProfile.php');

$profile = new Profile();
$profile->printTemplate();

$friends = new Friends();
$friends->printTemplate();

$notification = new Notification();
$notification->printTemplate();

?>
