<?php
class Desktop extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle()
	{
		return 'myMed\'s home page: '.$_SESSION['user']['name'];
	}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
?>
		<link rel="stylesheet" href="services/desktop/design.css" />
		<link rel="stylesheet" href="services/desktop/style.css" />
		<script src="ecmapatch/getElementsByClassName.js"></script>
		<script src="ecmapatch/EventListener.js"></script>
		<script src="services/desktop/Drag.js"></script>
<?php
	}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags()
	{
?>
		<script>
		//<![CDATA[
		(function()
		{
			patchGEBCN.initDocument();
			var elems = document.getElementsByClassName('drag');
			for(var i=0 ; i<elems.length ; i++)
			{
				Drag(elems[i]);
			}
		})();
		//]]>
		</script>
<?php
	}
	public /*void*/ function contentGet()
	{
		echo '
			<div class="innerDesktop">';
		$services = Array(
			'MyTransport'	=> Array('x'=>15,	'y'=>20)/**/,
			'myAngel'		=> Array('x'=>15,	'y'=>100),
			'myProduct'		=> Array('x'=>15,	'y'=>180),
			'myCasoun'		=> Array('x'=>15,	'y'=>260),
			'myKayak'		=> Array('x'=>107,	'y'=>20),
			'myMontagne'	=> Array('x'=>107,	'y'=>100)/**/);
		
		//DISPLAY MENU
		//include('menu.php');
		//DISPLAY ICONS ON THE DESKTOP
		foreach($services as $name=>$position)
		{
			echo '
				<a '
						.'href="?service='.$name.'" '
						.'class="drag icon" '
						.'style="'
							.'top:'.$position['y'].'px;'
							.'left:'.$position['x'].'px;';
				if(is_file(dirname(__FILE__).'/../'.strtolower($name).'/icon.png'))
					echo
							'background-image: url(\'services/'.strtolower($name).'/icon.png\');">';
				else
					echo
							'background-image: url(\'services/dynamic/icons/'.strtolower($name).'.png\');">';
				echo '
					<span>'.$name.'</span>
				</a>';
		}
		echo '
			</div>';
		//DOCK
?>
			<div id="dock">
				<a href="<?= $_SESSION['user']['profile'] ?>" class="icon myProfile">
					<img src="services/desktop/img/myHome.png" alt="MyProfile" />
					<span>MyProfile</span>
				</a>
				<a href="?service=MyStore" class="icon myStore">
					<img src="services/mystore/icon.png" alt="MyStore" />
					<span>MyStore</span>
				</a>
				<a href="?service=myPreference" class="icon MyPreference">
					<img src="services/mypreference/icon.png" alt="myPreference" />
					<span>MyPreference</span>
				</a>
				<a href="?service=myInfo" class="icon myInfo">
					<img src="services/myinfo/icon.png" alt="MyInfo" />
					<span>MyInfo</span>
				</a>
			</div>
<?php
	}
}
?>