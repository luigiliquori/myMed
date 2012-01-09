<?php
require_once 'system/templates/handler/UpdateProfileHandler.class.php';
require_once 'system/templates/handler/MenuHandler.class.php';
require_once dirname(__FILE__).'/Home.class.php';
require_once dirname(__FILE__).'/Profile.class.php';

$menuHandler = new MenuHandler();
$menuHandler->handleRequest();

$updateProfileHandler = new UpdateProfileHandler();
$updateProfileHandler->handleRequest();
?>

<!-- Disconnect the user -->
<form action="#" method="post" name="disconnectForm" id="disconnectForm">
	<input type="hidden" name="disconnect" value="1" />
</form>

<?php
$home = new Home();
$home->printTemplate();
$profile = new Profile();
$profile->printTemplate();
include('views/updateProfile.php');
?>