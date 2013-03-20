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
require_once '../../lib/dasp/request/Reputation.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class ResultView extends MainView {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($handler) {
		$this->handler = $handler;
		parent::__construct("ResultView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<div data-role="content" id="content" style="padding: 10px;" data-theme="c">
		
			<a href="#MainView" data-role="button" data-direction="reverse" data-inline="true"><?= $_SESSION['dictionary'][LG]["back"] ?></a><br /><br />
			
			<?php if(isset($_POST['method']) && $_POST['method'] == "find" &&  $this->handler->getError()) { ?>
				<center><h3><?= $_SESSION['dictionary'][LG]["noResult"] ?></h3></center>
			<?php } else { ?>
				<ul data-role="listview" data-filter="true" data-theme="c" data-dividertheme="a" >
					<?php
					if(isset($_POST['method']) && $_POST['method'] == "find" &&  $this->handler->getSuccess()) {
						$results = json_decode($this->handler->getSuccess());
						$_SESSION[APPLICATION_NAME]['results'] = $results;
						$find="true";
					} else {
						if(isset($_POST['Area']) && isset($_POST['Categoria'])){
							?>
							<center><h3><?=$_SESSION['dictionary'][LG]["noArg"]?></h3></center>
							<?php
							$find="false";
						}else{
						$results = $_SESSION[APPLICATION_NAME]['results'];
						$find="true";
						}
					}
					if($find=="true"){
						$i=0;
						foreach($results as $result) { ?>
							<li>
								<!-- RESULT DETAILS -->
								<form action="#DetailView" method="post" name="getDetailForm<?= $i ?>">
									<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
									<input type="hidden" name="method" value="getDetail" />
									<input type="hidden" name="user" value="<?= $result->publisherID ?>" />
									<input type="hidden" name="predicate" value="<?= $result->predicate ?>" />
								</form>
								<a href="#" onclick="document.getDetailForm<?= $i ?>.submit()">
									<?php 
									$area=strstr($result->predicate, "Area");
									$n=strpos($area, "Categoria");
									$area=substr($area, 4, $n-4);
									$categoria=strstr($result->predicate, "Categoria");
									$n=strpos($categoria, "begin");
									$categoria=substr($categoria, 9 , $n-9);


									?><span><?= $categoria." ".$area?></span>

									<span><font color=”blue”><center><?= $result->data ?></center></font></span>

									<?php
									$data=strstr($result->predicate, "begin");
									$data=substr($data, 5,10);
									?>
									<span><?= $data?></span>
								</a>
							</li>
							<?php $i++ ?>
						<?php }
						}?>
				</ul>
			<?php } ?>
			<br /><br />
		</div>
	<?php }
}
?>
