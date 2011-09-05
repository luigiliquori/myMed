<?php
//define('MIMETYPE_XHTML', true);
$ip = ip2long($_SERVER['REMOTE_ADDR']);
if(ip2long('138.96.242.0')<$ip && $ip<ip2long('138.96.242.255'))
	define('DEBUG', true);
unset($ip);//*/
require('system/main.php');
?>
