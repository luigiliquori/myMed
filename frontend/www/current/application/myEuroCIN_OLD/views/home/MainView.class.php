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
	public /*String*/ function getMenu() { ?>
		<a href="?language=IT" rel="external"><img alt="it" src="img/IT_Flag.png" width="20" Style="position: absolute; left: 300px; top:10px;"></a>
		<a href="?language=FR" rel="external"><img alt="fr" src="img/FR_Flag.png" width="20" Style="position: absolute; left: 300px; top:50px;"></a>
		<div data-role="navbar" data-iconpos="left">
			<ul>
				<?php $mainView = $this->id == "MainView" ?>
				<?php $findView = $this->id == "FindView" || $this->id == "ResultView" || $this->id == "DetailView" ?>
				<?php $publishView = $this->id == "PublishView" ?>
				<li><a href="#MainView" <?= $mainView ? "data-theme='b'" : "" ?> data-icon="home" data-transition="fade"><?= $_SESSION['dictionary'][LG]["view1"] ?></a></li>
				<li><a href="#FindView" <?= $findView  ? "data-theme='b'" : "" ?> data-icon="search" data-transition="fade"><?= $_SESSION['dictionary'][LG]["view2"] ?></a></li>
				<li><a href="#PublishView" <?= $publishView ? "data-theme='b'" : "" ?> data-icon="grid" data-transition="fade"><?= $_SESSION['dictionary'][LG]["view3"] ?></a></li>
			</ul>
		</div><!-- /navbar -->
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<div data-role="content" id="content" style="padding: 10px;" data-theme="c">
			<?= $_SESSION['dictionary'][LG]["welcome"] ?>
		</div>
	<?php }
	
}
?>
