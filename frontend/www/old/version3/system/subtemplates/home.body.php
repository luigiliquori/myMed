<span class="vertiAligner"></span><!--
--><div id="description_myMed">
	<div class="innerContent">
		<img alt="Un méta réseau social pour partager des applications" src="<?=ROOTPATH?>style/img/entonnoire.png" />
		<h2 class="title">myMed se joint à votre réseau social préféré pour ajouter de nouvelles fonctionnalités&#160;!</h2>
	</div>
</div><!--
--><div id="login_subscribe">
	<div class="login" onclick="$('#login div.list')[0].open();">
		<h2>Connectez-vous avec votre réseau social préféré&#160;:</h2>
		<div class="buttonList">
			<div class="main">
				<?php GlobalConnexion::getInstance()->mainButtons();?>
			</div>
			<div class="minor">
				<?php GlobalConnexion::getInstance()->minorButtons();?>
			</div>
		</div>
	</div>
	<div class="subscribe">
		<h2 class="title">
			Vous n'avez pas encore de profile sur un réseau social&#160;?<br />
			myMed en fourni un pour vous&#160;!
		</h2>
<?php		require __DIR__.'/openid-views/subscribe.view.php';?>
	</div>
</div> 
