<?php

class Template {
	
	
	public function head(){ ?>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=650" />
        <title>
        	myEurope
        </title>
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
        <link rel="stylesheet" href="my.css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js">
        </script>
        <script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js">
        </script>
        <script src="app.js">
        </script>
<!--     	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"> -->
<!--     	</script> -->
	<?php }
	
	public function credits(){ ?>
	
		<div data-role="footer" data-theme="c" class="footer">
			<div style="text-align: center;">
				<h4 style="margin: 10px;">myMed - INTERREG IV - Alcotra</h4>
				<img alt="Alcotra" src="img/alcotra" style="max-height:40px;max-width:100px;vertical-align: middle;" />
				<img alt="Europe" src="img/europe" style="max-height:40px;max-width:100px;vertical-align: middle;" />
				<img alt="Conseil Général 06" src="img/cg06" style="max-height:40px;max-width:100px;vertical-align: middle;" />
				<img alt="Regine Piemonte" src="img/regione" style="max-height:40px;max-width:100px;vertical-align: middle;" />
				<img alt="Région PACA" src="img/PACA" style="max-height:40px;max-width:100px;vertical-align: middle;" />
				<img alt="Prefecture 06" src="img/pref" style="max-height:40px;max-width:100px;vertical-align: middle;" />
				<img alt="Inria" src="img/inria.jpg" style="max-height:40px;max-width:100px;vertical-align: middle;" />
				<p style="margin: 8px;">"Ensemble par-delà les frontières"</p>
			</div>
		</div>
	
	<?php }
	

}
?>