<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class MyTranslator extends AbstractTemplate {
	
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
		parent::__construct("myTranslator", "myTranslator");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<div data-role="header" data-theme="a">
			<?php if(TARGET == "desktop") { ?>
				<a href="#" onClick="hideFrame('<?= APPLICATION_NAME ?>_iframe')" data-role="button" data-theme="r" target="_top">Fermer</a>
			<?php } else { ?>
				<a href="?application=0" rel="external" data-role="button" data-theme="r">Close</a>
			<?php } ?>
			<h1><?= $this->title ?></h1>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div id="myTranslatorContent" class="content" >
			<h1>Texte à traduire:</h1>
			<center>
				<script src="http://www.gmodules.com/ig/ifr?url=http://hosting.gmodules.com/ig/gadgets/file/107418635340074551756/prekladac.xml&amp;up_v07firstTime=1&amp;up_langpair=24&amp;up_sl=cs&amp;up_tl=en&amp;synd=open&amp;w=300&amp;h=200&amp;title=&amp;lang=fr&amp;country=ALL&amp;border=http%3A%2F%2Fwww.gmodules.com%2Fig%2Fimages%2F&amp;output=js"></script>			
			</center>
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() {  }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="b">
			<?php $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
}
?>