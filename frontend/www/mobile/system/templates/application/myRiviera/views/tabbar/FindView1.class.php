<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'system/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class FindView1 extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("Find1");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	private /*MPositionBean*/ function getPosition(/*String*/ $userID){
		$request = new Request("PositionRequestHandler", READ);
		$request->addArgument("userID", $userID);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			return json_decode($responseObject->data->position);
		} else {
			return null;
		}
	}
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm1" id="<?= APPLICATION_NAME ?>FindForm1">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="find" />
				<input type="hidden" name="numberOfOntology" value="4" />
				
				<div class="ui-grid-b">
					
					<!-- FROM -->
					<div class="ui-block-a">
					<img id="departpicture" alt="thumbnail" src="http://graph.facebook.com/007/picture?type=large" width="80" height="80" style="" /><br />
					<select id="selectdepart" name="enum" data-theme="a" onchange="changeDestination('depart')">
                         
                        <!-- DEFAULT -->                      
						<option value="nullpart&&007">Choisir</option>

						<!-- USER -->
						<?php if (isset($_SESSION['position'])) {?>
							<option value="<?= $_SESSION['user']->profilePicture ?>&&<?= $_SESSION['position']->formattedAddress ?>"><?= $_SESSION['user']->name ?></option>
						<?php } ?>

						<!-- FRIENDS -->
						<?php
						if(isset($_SESSION['friends'])) {
							foreach ($_SESSION['friends'] as $friend ) { ?>
								<?php if ($friend["position"]->formattedAddress != "") {?>
										<option
											value="<?= $friend["profilePicture"] ?>&&<?= $friend["position"]->formattedAddress ?>">
											<?= $friend["name"] ?>
										</option>
								<?php }
							}
						} ?>
					</select>
					</div>
					<input id="depart" type="hidden" name="Départ" value=""  />

					<!-- SEPARATOR -->
					<div class="ui-block-b">
						<img alt="thumbnail" src="http://www.poledream.com/wp-content/uploads/2009/10/icon_map2.png" width="80" height="80" style="" />				
					</div>

					<!-- TO -->
					<div class="ui-block-c">
					<img id="arriveepicture" alt="thumbnail" src="http://graph.facebook.com/007/picture?type=large" width="80" height="80" style="" /><br /> 
					<select id="selectarrivee" name="enum" data-theme="a" onchange="changeDestination('arrivee')">

						                         
                        <!-- DEFAULT -->                      
						<option value="nullpart&&007">Choisir</option>

						<!-- USER -->
						<?php if (isset($_SESSION['position'])) {?>
							<option value="<?= $_SESSION['user']->profilePicture ?>&&<?= $_SESSION['position']->formattedAddress ?>"><?= $_SESSION['user']->name ?></option>
						<?php } ?>

						<!-- FRIENDS -->
						<?php
						if(isset($_SESSION['friends'])) {
							foreach ($_SESSION['friends'] as $friend ) { ?>
								<?php if ($friend["position"]->formattedAddress != "") {?>
										<option
											value="<?= $friend["profilePicture"] ?>&&<?= $friend["position"]->formattedAddress ?>">
											<?= $friend["name"] ?>
										</option>
								<?php }
							}
						} ?>
                    </select>
					</div>
					<input id="arrivee" type="hidden" name="Arrivée" value=""  />
					
				</div>
				<br />
				<!-- DATE -->
				Partir le :<br />
				<input type="date" name="date" data-role="datebox" data-options='{"mode": "calbox"}' data-theme="a"/>
				
				<br /><br />
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm1.submit()">Calculer l'itinéraire</a>	
			</form>
		</div>
	<?php }
	
}
?>
