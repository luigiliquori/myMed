<?php
/**
 * @param array<string>	$tabList
 * @param int	$selectedTab
 */
?>
			<div id="tabList">
				<div class="innerContent">
					<ul>
<?php foreach($tabList as $tabName => $desktop):?>
						<li<?=$selectedTab==$tabName?' class="focus"':''?>><a href="?tab=<?=urlencode($tabName)?>"><span class="vertiAligner"></span><span><?=$tabName?></span></a><form method="post" action=""><div><button type="submit" name="deleteTab" value="<?=$tabName?>">suppr</button></div></form></li>
<?php endforeach;?>
					</ul>
					<hr />
					<div class="border-bottom"></div>
				</div>
			</div>
			<div id="desktops">
				<div id="desktop<?=$selectedTab?>" class="desktop focus">
<?php foreach($tabList[$selectedTab] as $column):?>
					<div class="column" style="width:<?=$column['width']?>">
<?php 	foreach($column['appList'] as $app):?>
						<div class="window">
							<div class="titlebar">
								<div class="title"><span><?=$app?></span></div>
								<div class="titleButtons"><!--
									--><button class="options" onclick="this.focus();"><span>options</span></button><!--
									--><div class="menu">
										<ul>
											<li><button>Partager ce service</button></li>
											<li><button>Signaler ce service</button></li>
											<li><hr /></li>
											<li><button onclick="this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.toogleMinimize()">Réduire ce service</button></li>
											<li><button onclick="this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.toogleMaximize()">Agrandir ce service</button></li>
											<li><button onclick="this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.close()">Supprimer ce service</button></li>
										</ul>
									</div><!--
									--><button class="minimize" title="minimize" onclick="this.parentNode.parentNode.parentNode.toogleMinimize()"><span>-</span></button><!--
									--><button class="maximize" title="maximize" onclick="this.parentNode.parentNode.parentNode.toogleMaximize()"><span>+</span></button><!--
									--><button class="close" title="delete" onclick="this.parentNode.parentNode.parentNode.close()"><span>x</span></button><!--
								--></div>
							</div>
							<div class="content">
								<!--[if IE 8]>
								<iframe allowtransparency="true" frameBorder="0" src="application/<?=$app?>?template=onlycontent" id="<?=$app?>">
									<a href="application/<?=$app?>">Démarrer l'application</a>
								</iframe>
								<![endif]-->
								<!--[if gt IE 8]><!-->
								<iframe src="application/<?=$app?>?template=onlycontent" id="<?=$app?>">
									<a href="application/<?=$app?>">Démarrer l'application</a>
								</iframe>
								<!--><![endif]-->
							</div>
							<div class="rightResizer"></div>
							<div class="leftResizer"></div>
							<div class="bottomResizer"></div>
						</div>
<?php 	endforeach;?>
					</div>
<?php endforeach;?>
					<div class="glassPane"></div>
				</div>
				<script type="text/javascript">
				//<![CDATA[
				(function(){
					var desktops	= document.getElementById('desktops');
					for(var i=0 ; i<desktops.children.length-1 ; i++)
						initDesktop(desktops.children[i]);
					if(!desktops.getElementsByClassName)
						desktops.getElementsByClassName	= patchGEBCN.getElementsByClassName;
					var windowsList	= desktops.getElementsByClassName("window");
					for(var i=0 ; i<windowsList.length ; i++)
						initWindow(windowsList[i]);
				})();
				//]]>
				</script>
			</div>
