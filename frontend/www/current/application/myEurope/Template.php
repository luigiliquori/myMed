<?php

class Template {
	
	
	public static function init(  $redirect = true ) {
		
		require_once '../../lib/dasp/request/Request.class.php';
		require_once '../../system/config.php';
		require_once '../../lib/dasp/beans/OntologyBean.php';
		
		if(session_id() == '') {
			session_start();
		}
		if ($redirect && !isset($_SESSION['user'])) {
			$_SESSION['redirect'] = $_SERVER['QUERY_STRING'];
			header("Location: ./authenticate");
		}
		
	}


	public static function head(){ ?>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>myEurope</title>
<link rel="stylesheet"
	href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
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

	public static function credits(){ ?>

<div data-role="footer" data-theme="c" class="footer">
	<div style="text-align: center;">
		<h4 style="margin: 10px;">myMed - INTERREG IV - Alcotra</h4>
		<img alt="Alcotra" src="../../system/img/logos/alcotra" style="max-height: 40px; max-width: 100px; vertical-align: middle;" /> <img alt="Europe"
			src="../../system/img/logos/europe" style="max-height: 40px; max-width: 100px; vertical-align: middle;" /> <img alt="Conseil Général 06"
			src="../../system/img/logos/cg06" style="max-height: 40px; max-width: 100px; vertical-align: middle;" /> <img alt="Regine Piemonte" src="../../system/img/logos/regione"
			style="max-height: 40px; max-width: 100px; vertical-align: middle;" /> <img alt="Région PACA" src="../../system/img/logos/PACA"
			style="max-height: 40px; max-width: 100px; vertical-align: middle;" /> <img alt="Prefecture 06" src="../../system/img/logos/pref"
			style="max-height: 40px; max-width: 100px; vertical-align: middle;" /> <img alt="Inria" src="../../system/img/logos/inria.png"
			style="max-height: 40px; max-width: 100px; vertical-align: middle;" />
		<p style="margin: 8px;">"Ensemble par-delà les frontières"</p>
	</div>
</div>

<?php }


	static function isPredicate($var) {
		return($var->ontologyID < 4);
	}

	static function isTag($var) {
		return($var == "on");
	}

}
?>