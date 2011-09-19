#!/usr/bin/php
<?php
define('BACKEND_URL', 'http://mymed2.sophia.inria.fr:8080/mymed_backend/');
define('KEYS', <<<EOT
define('ConnexionTwitter_KEY', 'HgsnlIpCJ7RqHhCFELkTvw');
define('ConnexionTwitter_SECRET', 'P7Gkj9AfeNEIHXrj0PMTiNHM3lJbHEqkuXwuWtGzU');
define('ConnexionMySpace_KEY', 'ca09cf41d3c047ecbed4eeac8b6f14c7');
define('ConnexionMySpace_SECRET', 'b9e2b88eb7aa4b878f913db25c1bb3f60bcbf3cdb99c47fb99e5aed9d5919eb5');
define('ConnexionLiveId_KEY', '000000004405611F');
define('ConnexionLiveId_SECRET', 'Akxh3kwLGjkyKpjpfBShzk9wrDAjme94');
//define('ConnexionLinkedIn_KEY', '');
//define('ConnexionLinkedIn_SECRET', '');
define('ConnexionGoogle_KEY', \$_SERVER['HTTP_HOST']);//unused
define('ConnexionGoogle_SECRET', 'LCQZrwojk1KdSf1ARurdjIr8');
define('ConnexionFacebook_KEY', '154730914571286');
define('ConnexionFacebook_SECRET', 'a29bd2d27e8beb0b34da460fcf7b5522');
?>
EOT
);
function /*void*/ error(/*string*/ $message, /*int*/ $exitCode = -1)
{
	fwrite(STDERR,$message);
	exit($exitCode);
}
function /*void*/ configureFile(/*string*/ $fileName, /*string*/ $ROOTPATH)
{
	$ROOTPATH_regex		= '#^define\\(\'ROOTPATH\', \'([^\']|\\\\\')*\'\\);\\s*$#';
	$BACKEND_URL_regex	= '#^define\\(\'BACKEND_URL\', \'([^\']|\\\\\')*\'\\);\\s*$#';
	$fileSrc	= fopen($fileName, 'r');
	$destFileName	= dirname($fileName).'/~'.basename($fileName).'.tmp';
	$fileDest	= fopen($destFileName, 'w');
	if($fileSrc && $fileDest)
	{
		do
		{
			$line	= fgets($fileSrc);
			if($line === false)
				error('Syntax error : Line "//Social Networks Keys" not found !'."\n");
			if(preg_match($ROOTPATH_regex, $line))
				$line	= "define('ROOTPATH', '$ROOTPATH');\n";
			elseif(preg_match($BACKEND_URL_regex, $line))
				$line	= "define('BACKEND_URL', '".str_replace("'", "\\'", BACKEND_URL)."');\n";
			fputs($fileDest, $line);
		}while($line !== '//Social Networks Keys'."\n");
		fputs($fileDest, KEYS);
		fclose($fileSrc);
		fclose($fileDest);
		rename($destFileName, $fileName);
	}
	else
		error('Can\'t open file(s)'."\n");
}
configureFile(__DIR__.'/WebContent/system/config.php', '/mymed/WebContent/');
configureFile(__DIR__.'/iGoogle3/system/config.php', '/mymed/iGoogle3/');
configureFile(__DIR__.'/version3/system/config.php', '/mymed/version3/');
?>
