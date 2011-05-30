<?php
class myStore extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle(){return 'myStore';}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		?>
		<link rel="stylesheet" type="text/css" href="services/myStore/style.css" />
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
		require_once dirname(__FILE__).'/data.php';
		$cat	= isset($_GET['cat'])&&isset($Apps[$_GET['cat']])?$_GET['cat']:'all';
		if($cat != 'all')
			echo '
				<h1>Catégorie "'.$translate[$cat].'"</h1>';
		else
			echo '
				<h1>Tous</h1>';
		if(isset($_GET['q']))
			$_GET['q'] = trim($_GET['q']);
		if(isset($_GET['q'])&&$_GET['q'])
		{
			$list = Array();
			foreach($Apps[$cat] as $app)
			{
				if(stripos($app->getDescription(), $_GET['q'])!==false || stripos($app->getName(), $_GET['q'])!==false)
					$list[]	= $app;
			}
			echo '
				<h2>Rechercher "'.$_GET['q'].'"</h2>';
		}
		else
			$list = $Apps[$cat];
		usort($list, function($app1, $app2)
		{
			if($app1->getGrade()>$app2->getGrade())
				return -1;
			elseif($app1->getGrade()<$app2->getGrade())
				return 1;
			else
				return 0;
		});
		if(count($list)):
		?>
				<div id="search"> 
			  		<a href="/mymed/iGoogle3/" class="homeLink"><span>Retour accueil</span></a> 
			  		<ul> 
			  			<li class="all"><a href="?service=myStore">Tous</a></li> 
			  			<li><a href="?service=myStore&amp;cat=news">Actualités</a></li> 
			  			<li><a href="?service=myStore&amp;cat=entertainment">Divertissement</a></li> 
			  			<li><a href="?service=myStore&amp;cat=sport">Sport</a></li> 
			  			<li><a href="?service=myStore&amp;cat=culture">Culture</a></li> 
			  			<li><a href="?service=myStore&amp;cat=other">Divers</a></li> 
			  		</ul> 
			  		<form method="get" action=""> 
			  			<div> 
			  				<input type="hidden" name="service" value="myStore" /> 
			  				<input class="query" name="q" value="" /> 
			  				<input type="submit" value="Rechercher une application" /> 
			  			</div> 
			  		</form> 
				</div> 
				<ul>
		<?php foreach($list as $app):?>
					<li><?=$app?></li>
		<?php endforeach;?>
				</ul>
				<p style="text-align:center;">
					<a href="#myStore">Retourner en Haut</a>
				</p>
<?php
		endif;
	}
	/**
	 * Called page called with POST method, Can't print anything
	 * After : redirect to GET
	 * default : do nothing
	 */
	public /*void*/ function contentPost(){}
}
?>
