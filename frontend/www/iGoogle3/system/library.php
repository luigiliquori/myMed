<?php
/**
 * send an error message tu the final user usiing session then redirect to the 
 * current page in GET method
 * @param $msg	message to send
 * @param $removeGetVar	if true remove all var after '?'
 */
/*void*/ function sendError(/*string*/ $msg, /*bool*/ $removeGetVar=false)
{
	$_SESSION['error']	= $msg;
	if($removeGetVar)
		header('Location:'.preg_replace('#\\?.*$#', '', $_SERVER['REQUEST_URI']));
	else
		header('Location:'.$_SERVER['REQUEST_URI']);
	exit;
}
/**
 * print error send with sendError() inside a <div class="error"> tag
 */
/*void*/ function printError()
{
	if(isset($_SESSION['error']))
	{
		echo '<div class="error"><a href="'.$_SERVER['REQUEST_URI'].'" onclick="this.parentNode.parentNode.removeChild(this.parentNode);return false;">'.$_SESSION['error'].'</a></div>';
		unset($_SESSION['error']);
	}
}
?>
