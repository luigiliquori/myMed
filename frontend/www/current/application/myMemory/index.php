<?php 
/**
 * Application MyMemory
 * 
 * Index file which delegate all requests to the Controller
 * 
 * 
 * David Da Silva ( contact@daviddasilva.net )
 */


include_once("controller/Controller.php");

$controller = new Controller();

$controller->invoke();
