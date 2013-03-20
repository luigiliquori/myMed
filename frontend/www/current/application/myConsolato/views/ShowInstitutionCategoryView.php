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
<? include("header.php");?>
<div data-role="page" >

	<? header_bar(category_crumb($this->category)) ?>

	<div data-role="content">

		<?  // Not for root category !
			if ($this->category->id != "root") : ?>
			
			<div data-role="controlgroup" data-type="horizontal" >
				<a data-role="button" data-icon="edit" data-theme="e"
			   		href="<?= url("institutionCategory:edit", array("id" => $this->category->id)) ?>">
					<?= ("éditer la catégorie") ?>
				</a>
				<a data-role="button" data-icon="delete"  data-theme="r"
			   		href="<?= url("institutionCategory:delete", array("id" => $this->category->id)) ?>">
					<?= ("supprimer") ?>
				</a>
			</div>	
			
			<? title_bar($this->category->title) ?>		
			<p>
				<?= nl2br($this->category->desc) ?>
			</p>
			

		<? endif ?>
		

		<? $childCats = $this->category->getChildCategories() ?> 
		<? if (sizeof($childCats) > 0): ?>
		
			<? title_bar(_("Sous catégories")) ?>
			
			<ul data-role="listview" data-inset="true">
			<? foreach($childCats as $cat) :?>
				<li>
					<a href="<?= url("institutionCategory:show", array("id" => $cat->id)) ?>">
						<h3><?= $cat->title ?></h3>
						<p><?= $cat->desc ?></p>
					</a>
				</li>
			<? endforeach ?>
			</ul>
		<? endif ?>
		
		
		<? $childArticles = $this->category->getChildArticles() ?> 
		<? if (sizeof($childArticles) > 0): ?>
		
			<? title_bar(_("Articles")) ?>
			
			<ul data-role="listview" data-inset="true">
			<? foreach($childArticles as $article) :?>
				<li>
					<a href="<?= url("institutionArticle:show", array("id" => $article->id)) ?>">
						<h3><?= $article->title ?></h3>
						<p><?= $article->desc ?></p>
					</a>
				</li>
			<? endforeach ?>
			</ul>
		<? endif ?>
		
		<div data-role="controlgroup" data-type="horizontal" >
			<a href="<?= url("institutionCategory:create", array("parentID" => $this->category->id)) ?>" 
				data-role="button" data-icon="create" data-inline="true" data-theme="g" >
				<?= _("Ajouter une sous catégorie") ?>
			</a>	
			<a href="<?= url("institutionArticle:create", array("parentID" => $this->category->id)) ?>" 
				data-role="button" data-icon="create" data-iconpos="right" data-inline="true" data-theme="g" >
				<?= _("Ajouter un article") ?>
			</a>
		</div>
	
	</div><!-- End of "content" -->

</div><!-- End of "page" -->
<? include("footer.php") ?>