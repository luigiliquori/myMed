<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class View3 extends View1 {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("View3");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header" data-theme="a">
			<a href="#View2" data-role="button">Vue 2</a>
			<h1><?= APPLICATION_NAME ?></h1>
		</div>
	<?php }
		
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*void*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
				<center>
					<div data-role="collapsible-set" data-theme="a" data-content-theme="d" style="width: 70%; text-align: left;"> 
					<?php 
					if(VIEW_3_MAP)
						$this->getMapFeature();
					if(VIEW_3_PUBLISH)
						$this->getPublishFeature();
					if(VIEW_3_SUBSCRIBE)
						$this->getFindFeature();
					if(VIEW_3_FIND)
						$this->getSubscribeFeature();
					if(VIEW_3_PROFILE)
						$this->getProfileFeature();
					if(VIEW_3_SOCIAL_NETWORK)
						$this->getSocialNetworkFeature();
					?> 
					</div>
				</center>
			
		</div>
	<?php }
	
}
?>