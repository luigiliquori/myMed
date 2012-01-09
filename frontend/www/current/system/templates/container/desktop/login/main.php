<?php
require_once dirname(__FILE__).'/Login.class.php';

$login = new Login();
$login->printTemplate();

include('inscription.php');
include('socialNetwork.php');

?>