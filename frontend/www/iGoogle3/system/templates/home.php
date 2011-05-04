<?php 
require_once dirname(__FILE__).'/../iGoogleManager.class.php';
//header("Content-Type:application/xhtml+xml") 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>myMed</title>
		<?php $this->headTags();?>
		<!-- STYLESHEET -->
		<link rel="stylesheet" href="style/desktop/style.css" />
		<!-- define styles of type of elements (ex:h1, p, p.myclass...)-->
		<link rel="stylesheet" href="style/desktop/design.css" />
		<!-- define design of website -->
		<script src="ecmapatch/EventListener.js"></script>
		<script src="ecmapatch/XMLHttpRequest.js"></script>
		<script src="ecmapatch/getElementsByClassName.js"></script>
		<script src="ecmapatch/importNode.js"></script>
		<script src="ecmapatch/getComputedStyle.js"></script>
		
		<script src="javascript/jquery/dist/jquery.js"></script>
		<script src="javascript/display.js"></script>
		
		<script src="javascript/FormSerializer.js"></script>
		<script src="javascript/include.js"></script>
		<script src="javascript/MyDesktop.js"></script>
		<script src="javascript/MyService.js"></script>
		<script src="javascript/MyWindow.js"></script>
	</head>
	<body>
		<div id="header">
			<h1>myMed v1.0 alpha</h1>
			<ul class="menu">
				<li><a href="#">WebPage</a></li>
				<li><a href="#">Blog</a></li>
				<li><a href="#">Forum</a></li>
				<li><a href="#">Contact</a></li>
			</ul>
			<div class="connexion">
				<?php if($_SESSION['user']['social_network'] != 'unknown'):?>
					<?php echo $_SESSION['user']['name']?> |
					<form method="post" action="">
						<div><input type="submit" name="logout" value="Déconnexion" /></div>
					</form>
				<?php else :?>
				<span>Connexion</span>
				<div>
					<?php $this->button();?>
				</div>
				<?php endif;?>
			</div>
		</div>
	  	<div id="search">
	  		<ul>
	  			<li class="all"><a href="#">Tous</a></li>
	  			<li><a href="#">Actualite</a></li>
	  			<li><a href="#">Divertissement</a></li>
	  			<li><a href="#">Sport</a></li>
	  			<li><a href="#">Culture</a></li>
	  			<li><a href="#">Plus</a></li>
	  		</ul>
	  		<form method="get" action="#">
	  			<div>
	  				<input class="query" type="text" />
	  				<input type="submit" name="search" value="Rechercher une application" />
	  				<input type="submit" name="top10" value="Top 10" />
	  			</div>
	  		</form>
	    </div>
	    <div id="content">
<?php 
			$iGoogleManager = new iGoogleManager();
			$tabsList	= $iGoogleManager->getTabs();
			?>
			<div id="tabList">
				<ul>
<?php 
					$i=0; foreach($tabsList as $tabName=>$active) :?>
					<li id="tab<?=$i?>"<?php if($active) echo ' class="active"'?>>
						<button type="button"><?php echo htmlspecialchars($tabName)?></button>
						<button class="del" type="button"><span>del</span></button>
					</li>
<?php 
					$i++;endforeach;?>
					<li class="add">
						<button type="button"><span>Add Tab</span></button>
					</li>
				</ul>
			</div>
			<div id="desktops">
<?php 
				$i=0;foreach($tabsList as $tabName=>$active) :?>
				<div id="desktop<?=$i?>" class="desktop<?php if($active) echo ' active'?>">
					<script>
					//<![CDATA[
					(function(){
						var desktop = new MyDesktop(document.getElementById("desktop<?=$i?>"));
<?php 
							$apps = $iGoogleManager->getApps($tabName);
							foreach($apps as $app)
								echo '
						new MyService("'.$app[0].'", desktop, '.$app[1].');';
						?>

					})();
					//]]>
					</script>
				</div>
<?php 
				$i++;endforeach;?>
			</div>
			<script type="text/javascript">
			//<![CDATA[
			(function(){
				var tabContainer	= document.getElementById("tabList").getElementsByTagName("ul")[0];
				var tabsList		= tabContainer.children;
				var desktopContainer	= document.getElementById("desktops");
				if(!desktopContainer.getElementsByClassName)
					desktopContainer.getElementsByClassName = patchGEBCN.getElementsByClassName;
				if(!tabContainer.getElementsByClassName)
					tabContainer.getElementsByClassName = patchGEBCN.getElementsByClassName;
				function tabClick(evt)
				{
					var id = evt.currentTarget.id.replace(/^tab/, "");
					var activeTabs	= tabContainer.getElementsByClassName("active");
					for(var i=0 ; i<activeTabs.length ; i++)
						activeTabs[i].className = "";
					var activeDesktops	= desktopContainer.getElementsByClassName("active");
					for(var i=0 ; i<activeDesktops.length ; i++)
						activeDesktops[i].className = "desktop";
					document.getElementById("desktop"+id).className	= "desktop active";
					evt.currentTarget.className	= "active";
				}
				function closeClick(evt)
				{
					var tab = evt.currentTarget.parentNode;
					var id	= tab.id.replace(/^tab/, "");
					var desktop	= document.getElementById("desktop"+id);
					desktop.parentNode.removeChild(desktop);
					tab.parentNode.removeChild(tab);
					evt.stopPropagation();
				}
				function testTabExists(/*string*/ name)
				{
					for(var i=0 ; i<tabsList.length ; i++)
						if(tabsList[i].getElementsByTagName("button")[0].textContent == name)
							return true;
					return false;
				}
				function addTab()
				{
					var name = prompt("Donner le nom de l'onglet à ajouter :");
					while((function(){
						if(name === null)
							return false;
						if(name === "")
						{
							name = prompt("Veuillez entrer un nom.\nDonner le nom de l'onglet à ajouter :");
							return true;
						}
						else if(testTabExists(name))
						{
							name = prompt(name+" existe déjà.\nDonner le nom de l'onglet à ajouter :");
							return true;
						}
						else
							return false;
					})());
					if(name != null)
					{
						var tab = document.createElement("li");
						var tabButton = document.createElement("button");
						var delButton = document.createElement("button");
						tabButton.setAttribute("type", "button");
						delButton.setAttribute("type", "button");
						delButton.setAttribute("class", "del");
						tabButton.appendChild(document.createTextNode(name));
						delButton.innerHTML = "<span>del</span>";
						tab.appendChild(tabButton);
						tab.appendChild(delButton);
						tab.setAttribute("id", "tab"+tabsList.length);
						desktopContainer.innerHTML += '\n'
								+'				<div id="desktop'+tabsList.length+'">\n'
								+'					<div class="desktop"></div>\n'
								+'				</div>';
						tabContainer.insertBefore(tab, tabsList[tabsList.length-1]);
						tabContainer.insertBefore(document.createTextNode(" "), tabsList[tabsList.length-1]);
						
						addEventListenerToElement(tab, "click", tabClick);
						addEventListenerToElement(delButton, "click", closeClick);
					}
				}
				for(var i=0 ; i<tabsList.length-1 ; i++)
				{
					addEventListenerToElement(tabsList[i], "click", tabClick);
					var buttons = tabsList[i].getElementsByTagName("button");
					addEventListenerToElement(buttons[buttons.length-1], "click", closeClick);
				}
				addEventListenerToElement(tabsList[tabsList.length-1], "click", addTab);
			})();
			//]]>
			</script>
		</div>
		<div id="footer">
		 	<ul class="languages">
		 		<li class="active"><a href="#">Francais</a></li>
		 		<li><a href="#">English</a></li>
		 		<li><a href="#">Italiano</a></li>
		 	</ul>
		 	<div class="slogan">"Ensemble par-delà les frontières"</div>
		</div>
		<?php $this->scriptTags();?>
		<?php if(defined('DEBUG')&&DEBUG){?>
		<div id="debug">
			<?php printTraces();?>
		</div>
		<?php }?>
  </body>
</html>