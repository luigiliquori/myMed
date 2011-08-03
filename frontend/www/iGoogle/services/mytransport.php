<?php
header("Content-Type:application/xml");
require_once __DIR__.'/ContentObject.class.php';
require_once __DIR__.'/mytransport/MyTransport.class.php';
$content = new MyTransport;
if($_SERVER['REQUEST_METHOD'] == 'POST')
	$content->contentPost();
elseif($_SERVER['REQUEST_METHOD'] == 'GET')
{
	if(isset($_GET['headload']))
	{
		echo '<head class="service" xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">';
		$content->headTags();
		echo '</head>';
	}
	elseif(isset($_GET['title']))
		$content->getTitle();
	else
	{
		echo '<div class="service" xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">';
		$content->contentGet();
		$content->scriptTags();
		echo '</div>';
	}
}
?>