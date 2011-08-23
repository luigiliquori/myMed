<?php if(!$this->content):?>
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/design.application.min.css" media="only screen and (min-width: <?=MOBILESWITCH_WIDTH?>px)" />
		<link rel="stylesheet" href="<?=ROOTPATH?>style/mobile/design.application.min.css" media="only screen and (max-width: <?=MOBILESWITCH_WIDTH-1?>px)" />
		<!--[if IE 8]><link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/design.application.ie8.min.css" media="screen" /><![endif]-->
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/loader.js.php?f=ecmapatch/getElementsByClassName,ecmapatch/XMLHttpRequest,ecmapatch/EventListener,ecmapatch/getComputedStyle,Desktop,Window" type="text/javascript"></script>
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/desktopPreloader.js" type="text/javascript"></script>
<?php endif;?>
