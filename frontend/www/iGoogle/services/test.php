<?php
header("Content-Type:application/xml");
if(isset($_GET['headload']))
{
	echo '<head class="service" xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"></head>';
	exit;
}
elseif(isset($_GET['title']))
{
	echo "Test";
	exit;
}
else
{
?>
<div class="service" xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<p>Hello World !</p>
	<!--iframe src="http://mymed.fr" seamless="seamless" sandbox="allow-scripts allow-forms allow-same-origin"></iframe-->
</div>
<?php 
}
?>