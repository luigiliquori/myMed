<?php

require_once 'system/templates/AbstractTemplate.class.php';;

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class News extends AbstractTemplate {
	/* --------------------------------------------------------- */
	/* Attribute */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("news", "news");
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div style="background-color: #1d1d1d; color: white; width: 200px; font-size: 15px; font-weight: bold;">
			Actualités
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { }
		
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() {?>
		<!-- CONTENT -->
		<div style="position:relative;  width: 200px; background-color: #f0f0f0; ">
			<br />
			Plus d'infos sur: <a href="http://www.mymed.fr" target="blank">www.mymed.fr</a>
			<br />
			<ul style="position: relative; left: -15px;">
				<li>
					L'équipe technique de myMed de l'INRIA s'est rendu à Turin pour installer avec ses collègues de l'École Politechnique 
					de Turin la dorsale de PC Italiens.<br /><br />
				</li>
				<li>
					Deuxième RV avec M. Jérôme Vandamme à une réunion de travail du Club des Entrepreneurs Italiens de l’UPE 06.<br /><br />
				</li>
				<li>
					myMed entame deux réunions les 20 et 27 juillet avec M. Benchimol, Doyen de la faculté de Medicine et le Professeur
					Pascal Staccini et Edmond Cissé pour étudier quelques réseaux sociaux axés étudiants, docteurs ou "analyse de symptômes,
					depistage maladies". <br /><br />
				</li>
			</ul>
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div style="position: absolute; left: 71%; top:445px;">
			<?php 
			$this->getHeader();
			$this->getContent();
			$this->getFooter();
			?>
		</div>
	<?php }
}
?>


