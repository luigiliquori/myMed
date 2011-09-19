<?php
require_once __DIR__.'/../ContainerTemplate.class.php';
require_once __DIR__.'/../ServiceNotFound.class.php';
require_once __DIR__.'/../backend/CookieRequest.class.php';
/**
 * A class to define template for /application
 */
class TemplateApplication extends ContainerTemplate
{
	private	/*CookieRequest*/	$cookies;
	private	/*array<string>*/	$tabList;
	private	/*int*/				$selectedTab;
	private	/*bool*/			$initalized	= false;
	public function __construct()
	{
		parent::__construct();
	}
	private function init()
	{
		if(!$this->content && !$this->initalized)
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
		$this->initalized	= true;
	}
	/**
	 * Print content's tags to be put inside \<head\> tag
	 */
	public /*void*/ function headTags()
	{
		parent::headTags();
		$this->init();
		echo '
		<style type="text/css">';
		foreach($this->tabList as $tabName=>$tab)
		{
			foreach($tab as $colId => $column)
				echo '
			#desktop'.$tabName.' div.col'.$colId.' {width:'.$column['width'].';}';
		}
		$width	= 100/count($this->tabList);
		echo "
			#tabList li ,
			#tabList div.mobile_slider {width:$width%;}";
		echo '
		</style>';
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
		$this->init();
		if($this->content)
		{
			echo '<div id="'.$this->getTitle().'">';
			$this->content->contentGet();
			echo '</div>';
		}
		else
		{
			$tabList	= $this->tabList;
			$selectedTab= $this->selectedTab;
			if(isset($_GET['application_fraghtml']))
				require __DIR__.'/application-views/desktop.view.php';
			else
				require __DIR__.'/'.$this->name.'.body.php';
		}
	}
}
?>
