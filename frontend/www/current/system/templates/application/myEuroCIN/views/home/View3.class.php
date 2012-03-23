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
		<div data-role="header" data-theme="b">
			<a href="#View1" data-role="button" data-icon="arrow-l">Retour</a>
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
					<div style="width: 50%; text-align: left; color: black; border: 10px #e5e5e5 solid; border-radius: 15px; padding: 10px;">
					<?php 
					if(VIEW_3_MAP)
						$this->getMapFeature();
					if(VIEW_3_PUBLISH)
						$this->getPublishFeature(VIEW_3_PUBLISH_keyword, VIEW_3_PUBLISH_address, VIEW_3_PUBLISH_date);
					if(VIEW_3_SUBSCRIBE)
						$this->getSubscribeFeature(VIEW_3_SUBSCRIBE_keyword, VIEW_3_SUBSCRIBE_address, VIEW_3_SUBSCRIBE_date);
					if(VIEW_3_FIND)
						$this->getFindFeature(VIEW_3_FIND_keyword, VIEW_3_FIND_address, VIEW_3_FIND_date);
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