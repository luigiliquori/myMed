<?php
ob_start("ob_gzhandler");		// compression des pages
ini_set('display_errors', 0);
if(!isset($_GET['f']))
{
	header('Status: 404 Not Found', false, 404);
	exit;
}
$filesName	= explode(',', $_GET['f']);
header('X-Powered-By: ');
header('Content-type:text/javascript;charset=utf-8');
header('Cache-Control:public');
$filemtime	= 0;
$buffer	= '';
$error	= false;
foreach($filesName as $rfile)
{
	$file = __DIR__.'/'.$rfile;
	$fileExists	= file_exists($file.'.js');
	$minFileExists	= file_exists($file.'.min.js');
	if(preg_match('#(^|/)\\.\\./#', $file) || !($fileExists || $minFileExists))
	{
		header('Status: 404 Not Found', false, 404);
		$error = true;
		echo 'alert("'.$file.' not found !");'."\n";
	}
	else 
		$filemtime	= max(
			$filemtime, 
			max(
				$minFileExists?
					filemtime($file.'.min.js')
					:0
				, $fileExists?
					filemtime($file.'.js')
					:0));
}
if($error)exit;
if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $filemtime < strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']))
{
	header('Status: 304 Not Modified', false, 304);
	exit;
}
header('Last-Modified:'.date('D, d M Y H:i:s', $filemtime).' GMT');
foreach($filesName as $rfile)
{
	$file = __DIR__.'/'.$rfile;
	$fileExists	= file_exists($file.'.js');
	$minFileExists	= file_exists($file.'.min.js');
	if($fileExists&&$minFileExists)
	{
		if(filemtime($file.'.min.js')< filemtime($file.'.js'))
			$file	= $file.'.js';
		else
			$file	= $file.'.min.js';
	}
	else if($minFileExists)
		$file	= $file.'.min.js';
	else
		$file	= $file.'.js';
	$buffer	.= "\r\n//FILE $rfile.js\r\n".file_get_contents($file);
}
#echo preg_replace('# +#', ' ', preg_replace_callback('#(//[^\\r\\n]*([\\r\\n]))|(/\\*([^*]|(\\*[^/]))*\\*/)|("([^"]|(\\\\"))*")|(\'([^\']|(\\\\\'))*\')|(/[^*/]([^/\\\\]|(\\\\\\\\)|(\\\\/))*/)#s', function($arg){
#	var_dump($arg);
#	$firstChar	= $arg[0][0];
#	if($firstChar === '"' || $firstChar === "'" || preg_match('#^/[^*/]#', $arg[0]))
#		return $arg[0];
#	else
#		return '';
#}, $buffer));/*
echo $buffer;//*/
?>
