<?php
require_once dirname(__FILE__).'/iGoogleManager.class.php';
class Desktop extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle(){return 'myBigApp';}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		?>
		<script type="text/javascript" src="javascript/FormSerializer.js"></script>
		<script type="text/javascript" src="javascript/include.js"></script>
		<script type="text/javascript" src="javascript/MyDesktop.js"></script>
		<script type="text/javascript" src="javascript/MyService.js"></script>
		<script type="text/javascript" src="javascript/MyWindow.js"></script>
<?php
	}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags(){}
	/**
	 * Print page's main content when page called with GET method
	 */
	public /*void*/ function contentGet()
	{

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
					<script type="text/javascript">
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
<?php
	}
	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost(){}
}
?>
