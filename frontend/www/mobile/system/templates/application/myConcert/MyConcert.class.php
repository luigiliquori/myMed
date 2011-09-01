<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class MyConcert extends AbstractTemplate {
	
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
		parent::__construct("myConcert", "myConcert");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header">
			<a href="?application=0" rel="external" data-role="button" data-theme="r">Fermer</a>
			<h1><?= $this->title ?></h1>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" >
			<center>
				<script src="http://www.gmodules.com/ig/ifr?url=http://www.zikinf.com/concerts/iGoogle.xml&amp;up_dpt=6&amp;up_s_salles=1&amp;up_s_heures=1&amp;up_s_styles=1&amp;up_s_villes=1&amp;synd=open&amp;w=320&amp;h=300&amp;title=&amp;border=%23ffffff%7C0px%2C1px+solid+%23595959%7C0px%2C1px+solid+%23797979%7C0px%2C2px+solid+%23898989&amp;output=js"></script>			
			</center>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="a">
			<?php $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
}
?>