<?php
//define('MIMETYPE_XHTML', true);
if($_SERVER['REMOTE_ADDR']=='138.96.242.21')
	define('DEBUG', true);
define('CONTENTOBJECT', 'OpenIdProvider/OpenIdProvider');
define('SESSIONNAME', 'openid');
require('system/main.php');
?>
