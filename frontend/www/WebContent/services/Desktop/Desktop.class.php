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
		<link rel="stylesheet" href="services/Desktop/design.css" />
		<link rel="stylesheet" href="services/Desktop/style.css" />
		<script src="ecmapatch/getElementsByClassName.js"></script>
		<script src="ecmapatch/EventListener.js"></script>
		<script src="services/Desktop/Drag.js"></script>
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
			'myTransport'	=> Array('x'=>15,	'y'=>20)/**/,
			'myJam'			=> Array('x'=>15,	'y'=>100),
			'myTranslator'	=> Array('x'=>15,	'y'=>180),
			'myJob'			=> Array('x'=>15,	'y'=>260),
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
				if(is_file(dirname(__FILE__).'/../'.$name.'/icon.png'))
					echo
							'background-image: url(\'services/'.$name.'/icon.png\');">';
				else
					echo
							'background-image: url(\'services/Dynamic/icons/'.$name.'.png\');">';
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
					<img src="services/Desktop/img/myHome.png" alt="MyProfile" />
					<span>myProfile</span>
				</a>
				<a href="?service=myStore" class="icon myStore">
					<img src="services/myStore/icon.png" alt="MyStore" />
					<span>myStore</span>
				</a>
				<a href="?service=myPreference" class="icon MyPreference">
					<img src="services/myPreference/icon.png" alt="myPreference" />
					<span>myPreference</span>
				</a>
				<a href="?service=myInfo" class="icon myInfo">
					<img src="services/myInfo/icon.png" alt="MyInfo" />
					<span>myInfo</span>
				</a>
			</div>
<?php
	}
}
?>