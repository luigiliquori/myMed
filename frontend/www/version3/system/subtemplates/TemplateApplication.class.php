<?php
require_once __DIR__.'/../ContainerTemplate.class.php';
require_once __DIR__.'/../ServiceNotFound.class.php';
require_once __DIR__.'/../backend/CookieRequest.class.php';
/**
 * A class to define template for /home
 */
class TemplateApplication extends ContainerTemplate
{
	private	/*CookieRequest*/	$cookies;
	private	/*array<string>*/	$tabList;
	private	/*int*/				$selectedTab;
	public function __construct()
	{
		parent::__construct();
		if(!$this->content)
		{
			$this->cookies	= new CookieRequest;
			try{$this->tabList	= unserialize($this->cookies->read('Application_tabList'));}
			catch(CookieNotFoundException $ex)
			{
				$this->initDefaultTabList();
				$this->cookies->create('Application_tabList', serialize($this->tabList));
			}
			$this->selectedTab	= isset($_GET['tab'])?$_GET['tab']:key($this->tabList);
			if(!isset($this->tabList[$this->selectedTab]))
				$this->content	= new ServiceNotFound;
		}
	}
	private /*void*/ function initDefaultTabList()
	{
		$xslt = new DOMDocument();
		$xslt->load(__DIR__.'/'.$this->name.'.default.xsl');
		$xml = new DOMDocument();
		$xml->load(__DIR__.'/'.$this->name.'.default.xml');
		$processor = new XSLTProcessor();
		$processor->importStylesheet($xslt);
		$this->tabList	= json_decode($processor->transformToXml($xml), true);
	}
	public /*void*/ function contentGet()
	{
		if($this->content)
			$this->content->contentGet();
		else
		{
			$tabList	= $this->tabList;
			$selectedTab= $this->selectedTab;
			require __DIR__.'/'.$this->name.'.body.php';
		}
	}
}
?>
