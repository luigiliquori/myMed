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