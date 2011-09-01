<?php 
if(defined('MIMETYPE_XHTML')&&MIMETYPE_XHTML)
	header("Content-Type:application/xhtml+xml; charset=utf-8");
else
	header("Content-Type:text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" style="background:none transparent">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>myMed<?php for($i=0 ; $this->getTitle($i, ' > ') ; $i++)?></title>
		<script type="text/vbscript">
		Const ROOTPATH = "<?=ROOTPATH?>"
		</script>
		<script type="text/javascript">
		if (typeof ROOTPATH == 'undefined')
			const ROOTPATH = "<?=ROOTPATH?>";
		</script>
		<script type="text/javascript">
		//<![CDATA[
			window.isMobileDesign	= function(){ return window.innerWidth<<?=MOBILESWITCH_WIDTH?>;};
			window.isMaxWidthMobileDesign	= function(){ return window.screen.width<<?=MOBILESWITCH_WIDTH?>;};
		//]]>
		</script>
		<!--bloquer le style pour les vieux IE-->
		<!--[if gt IE 7]><!-->
		<!-- load fonts-->
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/font.min.css" />
		<!-- define styles of type of elements (ex:h1, p, p.myclass...)-->
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/style.min.css" />
		<!-- ><![endif]!-->
		
		<!--JQuery CORE -->																  <!-- JQuery HTML5 compatibility-->
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/loader.js.php?f=jquery/dist/jquery,jquery.textPlaceholder,jquery.form,jquery.form.fr,jquery.form.config"></script>
		
		<!-- JS IE's version'-->
		<!--[if IE]>
		<script type="text/javascript">ieVersion=parseFloat(navigator.appVersion.split("MSIE")[1]);</script>
		<![endif]-->
<?php 	$this->headTags();?>
	</head>
	<body style="background:none transparent;overflow:hidden;" class="noscript">
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
			if(!jQuery.browser.opera&&jQuery.browser['version']>9.5)
				$(":date").dateinput();
			</script>
		
			<!-- DEBUG -->
<?php 	if(defined('DEBUG')&&DEBUG):?>
			<div id="debug">
				<?php printTraces();?>
			</div>
<?php 	endif;?>
		</div>
	</body>
</html>
