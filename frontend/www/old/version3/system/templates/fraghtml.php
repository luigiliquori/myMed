<?php
header("Content-Type:application/xml");
if(!isset($_GET['headload']))
{
	echo '<div id="';$this->getTitle();echo '" class="service" xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">';
	printError();
	$this->content();
	$this->content->scriptTags();
	echo '</div>';
}
else
{
	echo '<head xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">';
	$this->content->headTags();
	echo '</head>';
}
?>
