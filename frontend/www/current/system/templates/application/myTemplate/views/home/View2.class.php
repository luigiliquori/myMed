<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class View2 extends View1 {
	
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
		parent::__construct("View2");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header" data-theme="b">
			<a href="#View1" data-role="button">Vue 1</a>
			<h1><?= APPLICATION_NAME ?></h1>
			<a href="#View3" data-role="button" class="ui-btn-right">Vue 3</a>
		</div>
	<?php }
		
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*void*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
				<center>
					<div style="width: 50%; text-align: left; color: black; border: 10px #e5e5e5 solid; border-radius: 15px; padding: 10px;">
					<?php 
					if(VIEW_2_MAP)
						$this->getMapFeature();
					if(VIEW_2_PUBLISH)
						$this->getPublishFeature(VIEW_2_PUBLISH_keyword, VIEW_2_PUBLISH_address, VIEW_2_PUBLISH_date);
					if(VIEW_2_SUBSCRIBE)
						$this->getSubscribeFeature(VIEW_2_SUBSCRIBE_keyword, VIEW_2_SUBSCRIBE_address, VIEW_2_SUBSCRIBE_date);
					if(VIEW_2_FIND)
						$this->getFindFeature(VIEW_2_FIND_keyword, VIEW_2_FIND_address, VIEW_2_FIND_date);
					if(VIEW_2_PROFILE)
						$this->getProfileFeature();
					if(VIEW_2_SOCIAL_NETWORK)
						$this->getSocialNetworkFeature();
					?> 
					</div>
				</center>
			
		</div>
	<?php }
	
}
?>