<?php 
if(defined('MIMETYPE_XHTML')&&MIMETYPE_XHTML)
	header("Content-Type:application/xhtml+xml; charset=utf-8");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" style="background:none transparent">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>myMed<?php for($i=0 ; $this->getTitle($i, ' > ') ; $i++)?></title>
		<!--bloquer le style pour les vieux IE-->
		<!--[if gt IE 7]><!-->
		<!-- load fonts-->
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/font.css" />
		<!-- define styles of type of elements (ex:h1, p, p.myclass...)-->
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/style.css" />
		<!-- ><![endif]!-->
		
		<!--JQuery CORE -->
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery/dist/jquery.js"></script>
		<!-- JQuery HTML5 compatibility-->
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery.textPlaceholder.js"></script>
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery.form.min.js"></script>
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery.form.fr.js"></script>
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery.form.config.js"></script>
		
		<!-- JS IE's version'-->
		<!--[if IE]>
		<script type="text/javascript">ieVersion=parseFloat(navigator.appVersion.split("MSIE")[1]);</script>
		<![endif]-->
<?php 	$this->headTags();?>
	</head>
	<body style="background:none transparent;overflow:hidden;">
		<script type="text/javascript">document.body.className = "javascript";</script>
		<div id="content" class="body" style="overflow:auto;">
<?php 
			printError();
			$this->content();
			$this->scriptTags();
?>
			<!-- JQuery HTML5 compatibility's initialization'-->
			<script type="text/javascript">
			$("[placeholder]").textPlaceholder();
			$(":date").dateinput();
			</script>
		
			<!-- DEBUG -->
<?php 	if(defined('DEBUG')&&DEBUG):?>
			<div id="debug">
				<a href="?debug=0" class="close">close</a>
				<?php printTraces();?>
			</div>
<?php 	endif;?>
		</div>
	</body>
</html>
