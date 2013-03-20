<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
<?php

require_once '../../lib/dasp/beans/MDataBean.class.php';
require_once '../../lib/dasp/request/Request.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class MainView extends AbstractView {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($id = "MainView") {
		parent::__construct($id);
		$this->printTemplate();
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() {
		parent::getHeader();
		$this->getMenu();
	}

	public /*String*/ function getMenu() { ?>
		<?php $articleView = $this->id == "ArticleView" ?>
		<?php $commentView = $this->id == "CommentView" ?>
<<<<<<< HEAD
=======
		<?php $subscribeView = $this->id == "SubscribeView" ?>
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
		<a href="?langue=IT"><img alt="it" src="img/IT_Flag.png" width="20" Style="position: absolute; left: 300px; top:10px;"></a>
		<a href="?langue=IT"><img alt="fr" src="img/FR_Flag.png" width="20" Style="position: absolute; left: 300px; top:50px;"></a>
		<div data-role="navbar">
			<ul>
				<li><a href="#ArticleView" <?= $articleView ? "data-theme='b'" : "" ?>>Articolo</a></li>
				<li><a href="#CommentView" <?= $commentView ? "data-theme='b'" : "" ?>>Commento</a></li>
<<<<<<< HEAD
=======
				<li><a href="#SubscribeView" <?= $subscribeView ? "data-theme='b'" : "" ?>>Sottoscrivere</a></li>
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
			</ul>
		</div><!-- /navbar -->
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<div data-role="content" id="content" style="padding: 10px;" data-theme="c">
			<p>EURO C.I.N. GEIE (Gruppo Europeo di Interesse Economico) fondato nel 1994, dalle Camere di commercio transfrontaliere di Cuneo, Imperia e Nizza, si compone oggi di istituzioni e aziende che rappresentano i territori di Piemonte, Liguria e Provence Alpes Côte d’Azur. Oltre ai tre fondatori (Cuneo, Imperia e Nizza) gli altri membri sono le Camere di commercio di Asti, Alessandria, Genova, Unioncamere Piemonte, Autorità Portuale di Savona, BRE Banca Cuneo e GEAC Spa.</p>
			<p>Tra gli obiettivi del Gruppo la volontà di creare un’immagine globale e comune all’interno e all’esterno dell’Euroregione (chiamata delle “Alpi del Mare) favorendo l’integrazione economica, culturale e scientifica, lo sviluppo dei flussi transfrontalieri e la promozione dei territori che ne fanno parte con le loro peculiarità e tradizioni.</p>
			<p>Attraverso myEurocin, il Gruppo si propone di presentare e suggerire ai visitatori le mete più suggestive, contemplando natura e benessere, curiosità storiche e artistiche e i numerosi prodotti tipici che caratterizzano l’Euroregione.</p>
			<p>I contenuti di myEurocin sono liberamente consultabili dai visitatori, al quale chiediamo collaborazione per migliorare l’applicazione, fornendo informazioni e suggerimenti. Ricordiamo agli utenti che l’inserimento di nuovi contenuti è possibile previa autenticazione al sito.</p>
		</div>
	<?php }
	
}
?>
