<?php
/**
 * @param array<string>	$tabList
 * @param int	$selectedTab
 */
$mobileTabWidth	= 100/count($tabList);
$tabFocusId	= 0;
?>
			<div id="tabList">
				<div class="innerContent">
					<ul>
<?php $i=0;foreach($tabList as $tabName => $desktop):?>
						<li<?=$selectedTab==$tabName?' class="focus"':''?>><a href="?tab=<?=urlencode($tabName)?>"><span class="vertiAligner"></span><span><?=$tabName?></span></a><form method="post" action=""><div><button type="submit" name="deleteTab" value="<?=$tabName?>">suppr</button></div></form></li>
<?php 
	if($selectedTab==$tabName)$tabFocusId	= $i;
	$i++;
	endforeach;?>
					</ul>
					<hr />
					<div class="border-bottom"></div>
				</div>
				<div class="mobile_slider" style="left:<?=$tabFocusId*$mobileTabWidth?>%;"></div>
			</div>
			<div id="desktops">
<?php			require(__DIR__.'/application-views/desktop.view.php');?>
			</div>
			<script type="text/javascript">
			//<![CDATA[
			if(!isMaxWidthMobileDesign())
			(function(){
				var desktops	= document.getElementById('desktops');
				for(var i=0 ; i<desktops.childNodes.length-1 ; i++)
					if(desktops.childNodes[i].nodeName.toLowerCase() == "div")
						initDesktop(desktops.childNodes[i]);
				if(!desktops.getElementsByClassName)
					desktops.getElementsByClassName	= patchGEBCN.getElementsByClassName;
				var windowsList	= desktops.getElementsByClassName("window");
				for(var i=0 ; i<windowsList.length ; i++)
					initWindow(windowsList[i]);
			})();
			
			desktopPreloader($("#desktops")[0], $("#tabList")[0], function(){});
			//]]>
			</script>
