<? include("header.php") ?>
<div data-role="page" >

	<? header_bar(category_crumb($this->article->getParent())) ?>

	<div data-role="content">

		<? title_bar(_("Nouvel article")) ?>
	
		<form method="post" action="<?= url("institutionArticle:doCreate") ?>" >
		
			<? include("InstitutionArticleForm.php") ?>
		
			<input type="submit" value="<?= _("Publier l'article") ?>" data-theme="g">
			
		</form>

	</div><!-- End of "content" -->

</div><!-- End of "page" -->
<? include("footer.php") ?>
