<?php

require_once '../../lib/dasp/beans/MDataBean.class.php';
require_once '../../lib/dasp/request/Request.class.php';
require_once '../../lib/dasp/request/Reputation.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class SubscribeView extends MainView {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($handler) {
		$this->handler = $handler;
		parent::__construct("SubscribeView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	
	/**
	* 
	*/
	private /*String*/ function getSubscribeContent() { 
		?>

		<?php $_SESSION['Sub']= "ok"; ?>		
		
		<form action="" method="post" name="getSubscribeForm1">
			<input name="application" value="<?= APPLICATION_NAME ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="2" type="hidden"/>
			
				<li data-role="list-divider" data-theme='b'><?= "Area" ?></li>
				<br />
				<select name="Area">
					<option value=></option>
					<option value="Aerospaziale"><?= $_SESSION['dictionary'][LG]["Area"][0] ?></option>
					<option value="Ambientale"><?= $_SESSION['dictionary'][LG]["Area"][1] ?></option>
					<option value="Autoveicolo"><?= $_SESSION['dictionary'][LG]["Area"][2] ?></option>
					<option value="Biomeccania"><?= $_SESSION['dictionary'][LG]["Area"][3] ?></option>
					<option value="Cinema"><?= $_SESSION['dictionary'][LG]["Area"][4] ?></option>
					<option value="Civile"><?= $_SESSION['dictionary'][LG]["Area"][5] ?></option>
					<option value="Elettrica"><?= $_SESSION['dictionary'][LG]["Area"][6] ?></option>
					<option value="Elettronica"><?= $_SESSION['dictionary'][LG]["Area"][7] ?></option>
					<option value="Energetica"><?= $_SESSION['dictionary'][LG]["Area"][8] ?></option>
					<option value="Fisica"><?= $_SESSION['dictionary'][LG]["Area"][9] ?></option>
					<option value="Gestionale"><?= $_SESSION['dictionary'][LG]["Area"][10] ?></option>
					<option value="Informatica"><?= $_SESSION['dictionary'][LG]["Area"][11] ?></option>
					<option value="Matematica"><?= $_SESSION['dictionary'][LG]["Area"][12] ?></option>
					<option value="Materiali"><?= $_SESSION['dictionary'][LG]["Area"][13] ?></option>
					<option value="Meccanica"><?= $_SESSION['dictionary'][LG]["Area"][14] ?></option>
					<option value="Telecomunicazioni"><?= $_SESSION['dictionary'][LG]["Area"][15] ?></option>
				</select>
				<?php $dataBean = new MDataBean("Area", null, KEYWORD); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
				<br />
				<li data-role="list-divider" data-theme='b'><?= "Categoria" ?></li>
				<br />
				<!-- Categoria -->
				<select name="Categoria">
					<option value=></option>
					<option value="Stage"><?= $_SESSION['dictionary'][LG]["Categoria"][0] ?></option>
					<option value="Job"><?= $_SESSION['dictionary'][LG]["Categoria"][1] ?></option>
					<option value="Tesi"><?= $_SESSION['dictionary'][LG]["Categoria"][2] ?></option>
					<option value="Appunti"><?= $_SESSION['dictionary'][LG]["Categoria"][3] ?></option>
				</select>
				<?php $dataBean = new MDataBean("Categoria", null, KEYWORD); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">

		</form>
		<a  type="button" data-inline="true" data-theme="e" href="#" onclick="document.getSubscribeForm1.submit();">Sottoscrivere</a>
		</br>
		</br>


		<?php
		$request = new Request("SubscribeRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("userID", $_SESSION['user']->id);
		?>
		<ul data-role="listview"  data-theme="c" data-divider-theme="b" >
		<li data-role="list-divider" data-theme='b'><?= $_SESSION['dictionary'][LG]["SubMade"] ?></li>
		<?php
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$_SESSION['test']="/";
			$cont=0;
			$res = (array) $responseObject->dataObject->subscriptions;
			?>
			<?php
			foreach( $res as $i => $value ){ 
				$_SESSION['test']=$_SESSION['test'].$i."/";
				$cont++;
				if(!strstr($i, "Area")){
					$a=1;
					$categoria=substr($i, 9);
				}else{
					if(!strstr($i, "Categoria")){
						$a=2;
						$area=substr($i, 4);
					}else{	
						$a=3;
						$n=strpos($i, "Categoria");
						$area=substr($i, 4, $n-4);
						$categoria=strstr($i, "Categoria");
					$categoria=substr($categoria, 9);
					}
				} ?>
				<?php
				if($a==1){?>
					<form action="#ResultView" method="post" name="<?= APPLICATION_NAME ?>FindForm<?=$cont?>" id="<?= APPLICATION_NAME ?>FindForm<?=$cont?>" enctype="multipart/form-data">
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="find" />
					<input type="hidden" name="numberOfOntology" value="4" />
					<input type="hidden" name="Categoria" value="<?php echo $categoria; ?>"/>
					<?php $dataBean = new MDataBean("Categoria", null, KEYWORD); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm<?=$cont?>.submit()" data-inline="true" data-icon="search"><?= $categoria ?></a>
					</form>
					<form action="" method="post" name="delSubscribeForm<?=$cont?>">
					<input name="application" value="<?= APPLICATION_NAME ?>" type="hidden" />
					<input name="method" value="delete" type="hidden" />
					<input name="numberOfOntology" value="4" type="hidden"/>
					<input type="hidden" name="Categoria" value="<?php echo $categoria; ?>"/>
					<?php $dataBean = new MDataBean("Categoria", null, KEYWORD); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
				<a href="#" data-role="button" onclick="document.delSubscribeForm<?=$cont?>.submit()" data-theme="r" data-inline="true" data-icon="delete"><?= $categoria ?></a>
					</form>

			<?php 
				}else{
					if($a==2){?>
						<form action="#ResultView" method="post" name="<?= APPLICATION_NAME ?>FindForm<?=$cont?>" id="<?= APPLICATION_NAME ?>FindForm<?=$cont?>" enctype="multipart/form-data">
						<!-- Define the method to call -->
						<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
						<input type="hidden" name="method" value="find" />
						<input type="hidden" name="numberOfOntology" value="4" />
						<input type="hidden" name="Area" value="<?php echo $area; ?>"/>
						<?php $dataBean = new MDataBean("Area", null, KEYWORD); ?>
						<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
						<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm<?=$cont?>.submit()" data-inline="true" data-icon="search"><?= $area ?></a>
						</form>
						<form action="" method="post" name="delSubscribeForm<?=$cont?>">
						<input name="application" value="<?= APPLICATION_NAME?>" type="hidden" />
						<input name="method" value="delete" type="hidden" />
						<input name="numberOfOntology" value="4" type="hidden"/>
						<input type="hidden" name="Area" value="<?php echo $area; ?>"/>
						<?php $dataBean = new MDataBean("Area", null, KEYWORD); ?>
						<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
						<a href="#" data-role="button" onclick="document.delSubscribeForm<?=$cont?>.submit()" data-theme="r" data-inline="true" data-icon="delete"><?= $area ?></a>
						</form>
						<?php
					}else{?>
						<form action="#ResultView" method="post" name="<?= APPLICATION_NAME ?>FindForm<?=$cont?>" id="<?= APPLICATION_NAME ?>FindForm<?=$cont?>" enctype="multipart/form-data">
						<!-- Define the method to call -->
						<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
						<input type="hidden" name="method" value="find" />
						<input type="hidden" name="numberOfOntology" value="4" />
						<input type="hidden" name="Area" value="<?php echo $area; ?>"/>
						<?php $dataBean = new MDataBean("Area", null, KEYWORD); ?>
						<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
						<input type="hidden" name="Categoria" value="<?php echo $categoria; ?>"/>
						<?php $dataBean = new MDataBean("Categoria", null, KEYWORD); ?>
						<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($dataBean)); ?>">
						<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm<?=$cont?>.submit()" data-inline="true" data-icon="search"><?= $area." ".$categoria ?></a>
						</form>
						<form action="" method="post" name="delSubscribeForm<?=$cont?>">
						<input name="application" value="<?= APPLICATION_NAME ?>" type="hidden" />
						<input name="method" value="delete" type="hidden" />
						<input name="numberOfOntology" value="4" type="hidden"/>
						<input type="hidden" name="Area" value="<?php echo $area; ?>"/>
						<?php $dataBean = new MDataBean("Area", null, KEYWORD); ?>
						<input type="hidden" name="Categoria" value="<?php echo $categoria; ?>"/>
						<?php $dataBean = new MDataBean("Categoria", null, KEYWORD); ?>
						<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
						<a href="#" data-role="button" data-icon="delete" onclick="document.delSubscribeForm<?=$cont?>.submit()" data-theme="r" data-inline="true" ><?= $area." ".$categoria ?></a>
						</form>

					<?php
					}
				}
			}?>
		</ul>
		<?php
		}
		return ;
	}


	private /*String*/ function getSubscribeResult() {
	$newarea=$_POST['Area'];
	$newcategoria=$_POST['Categoria'];
	$x="a";
	?>
	<br />		
	<a href="#" data-role="button" data-direction="reverse" data-inline="true" onclick="window.location.reload();"><?= $_SESSION['dictionary'][LG]["back"] ?></a><br /><br />
	<?php
	if($x!="a"){
		echo $_SESSION['dictionary'][IT]["SubYet"]." : ".$newarea." ".$newcategoria;
	}else{
		echo $_SESSION['dictionary'][LG]["SubOK"]." : ".$newarea." ".$newcategoria;
	}
	}
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" style="padding: 10px;" data-theme="c">';
			if(isset($_POST['method']) && $_POST['method'] == "subscribe" && $_SESSION['Sub']== "ok" && ($this->handler->getError() || $this->handler->getSuccess())) {
			$_SESSION['Sub']= "ko";
			$this->getSubscribeResult();
		} else {
			
			$this->getSubscribeContent();
		}
			
		echo '</div>';
	}
}
?>
