<?php 
require_once dirname(__FILE__).'/Favorite.class.php';
require_once dirname(__FILE__).'/Category.class.php';
require_once dirname(__FILE__).'/Top10.class.php';
require_once dirname(__FILE__).'/Profile.class.php';
require_once dirname(__FILE__).'/handler/MenuHandler.class.php';

$menuHandler = new MenuHandler();
$menuHandler->handleRequest();
?>

<!-- Disconnect the user -->
<form action="#" method="post" name="disconnectForm" id="disconnectForm">
	<input type="hidden" name="disconnect" value="1" />
</form>

<div id="header"></div>

<?php 
$profile = new Profile();
$profile->printTemplate(); 
$favorite = new Favorite();
$favorite->printTemplate();
$category = new Category();
$category->printTemplate();
$top10 = new Top10();
$top10->printTemplate();
include('updateProfile.php');
?>