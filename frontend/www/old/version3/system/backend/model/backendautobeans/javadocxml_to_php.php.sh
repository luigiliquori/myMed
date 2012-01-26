#!/usr/bin/php
<?php
function /*string*/ parseXml(/*strong*/ $url)
{
	$xslt = new DOMDocument();
	$xslt->load(__DIR__.'/javadocxml_to_php.xsl');
	$xml = new DOMDocument();
	$xml->load($url);
	$processor = new XSLTProcessor();
	$processor->importStylesheet($xslt);
	return $processor->transformToXml($xml);
}
function /*void*/ createPhpFileFromXml(/*string*/ $className)
{
	file_put_contents(__DIR__.'/'.$className.'.class.php', parseXml('http://mymeddev:alcotra@mymed2.sophia.inria.fr/documentationXML/'.$className.'.xml'));
}
function /*void*/ main(/*void*/)
{
	$index = new DOMDocument();
	$index->loadHTMLFile('http://mymeddev:alcotra@mymed2.sophia.inria.fr/documentationXML/');
	$xpath	= new DOMXPath($index);
	$trList	= $xpath->query('/html/body/table/tr');
	for($i=2 ; $i<$trList->length ; $i++)
	{
		$file	= $xpath->query('td/a/@href', $trList->item($i))->item(0)->value;
		createPhpFileFromXml(basename($file, '.xml'));
	}
}
main();
?>
