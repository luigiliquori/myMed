<?php

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class AbstractView {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	protected /*String*/ $id;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*String*/ $id) {
		$this->id = $id;
		
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<img alt="logo" src="img/logo.gif">
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public abstract /*String*/ function getMenu();
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public abstract /*String*/ function getContent();
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { ?>
		<div Style="position: relative; width: 100%; text-align: center;">
				<h4>myMed - INTERREG IV - Alcotra</h4>
				<img alt="Alcotra" src="../../system/img/logos/alcotra"
					style="width: 100px;" /> <img alt="Europe"
					src="../../system/img/logos/europe" style="width: 50px;" /> <img
					alt="Conseil Général 06" src="../../system/img/logos/cg06"
					style="width: 100px;" /> <img alt="Regine Piemonte"
					src="../../system/img/logos/regione" style="width: 100px;" /> <img
					alt="Région PACA" src="../../system/img/logos/PACA" style="width: 100px;" />
				<img alt="Prefecture 06" src="../../system/img/logos/pref"
					style="width: 70px;" /> <img alt="Inria"
					src="../../system/img/logos/inria.jpg" style="width: 100px;" />
				<p>"Ensemble par-delà les frontières"</p>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="d">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getId(){
		return 	$this->id;
	}
}
?>
