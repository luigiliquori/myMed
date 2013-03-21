<?php
/*
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
 */
?>
<? include("header.php");?>
<div data-role="page" >

	<? header_bar(category_crumb($this->article->getParent())) ?>

	<div data-role="content">

		<div data-role="controlgroup" data-type="horizontal" >
			<a data-role="button" data-icon="edit" data-theme="e"
			   	href="<?= url("institutionArticle:edit", array("id" => $this->article->id)) ?>">
				<?= ("éditer l'article") ?>
			</a>
			<a data-role="button" data-icon="delete" data-theme="r"
				href="<?= url("institutionArticle:delete", array("id" => $this->article->id)) ?>">
					<?= ("supprimer") ?>
			</a>
		</div>	
	
		<? title_bar($this->article->title) ?>		
		<p>
			<?= nl2br($this->article->desc) ?>
		</p>
		<p>
			<?= nl2br($this->article->content) ?>
		</p>
		
	</div><!-- End of "content" -->

</div><!-- End of "page" -->
<? include("footer.php") ?>