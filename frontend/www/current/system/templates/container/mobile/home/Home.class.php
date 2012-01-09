<?php

require_once 'system/templates/container/Container.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Home extends Container {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	protected /*String*/ $activeHeader;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($id = "home") {
		parent::__construct($id);
		$this->activeHeader = $id;
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() {
		if (TARGET == "mobile") { ?>
			<!-- HEADER -->
			<div data-role="header">
				<div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
					<a href="#home" data-transition="slide"  data-role="button" data-back="true" <?= $this->activeHeader == "home" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Applications</a>
					<a href="#profile" data-transition="slide"  data-role="button" <?= $this->activeHeader == "profile" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Profile</a>
				</div>
			</div>
		<?php }
	}
}
?>


