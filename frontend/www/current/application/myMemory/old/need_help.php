<?php include("./header.php")?>

<div data-role="page" id="NeedHelp">
	<div data-role="content" data-theme="a">
		<a href="#" data-role="button" class="ui-btn-r">Stop au secours</a>
		<br />
		<div id="map_canvas" style="height:200px;"></div>
		<br />
		<p>MyMemory appelle en main libre les numéros suivants dans l'ordre :</p>
		<ol data-role="listview" data-inset="true" data-theme="c">
				<li>Mme Rose Dupont</li>
				<li>M. Luc Davan</li>
				<li>Association "moi et toi"</li>
				<li>SAMU</li>
			</ol>
	</div>
	<div data-role="footer" data-position="fixed">
	</div>
</div>
<?php $template->getFooter();?>