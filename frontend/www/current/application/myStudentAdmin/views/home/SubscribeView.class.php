<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
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
	
		$subAero = false;
		$subAmbi = false;
		$subAuto = false;
		$subBio = false;
		$subCine = false;
		$subCivi = false;
		$subEle = false;
		$subElettronica = false;
		$subEne = false;
		$subFisi = false;
		$subGesti = false;
		$subInfo = false;
		$subMate = false;
		$subMateri = false;
		$subMecca = false;
		$subTele = false;
		$subCom = false;
		
		$request = new Request("SubscribeRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME."_ADMIN");
		$request->addArgument("userID", $_SESSION['user']->id);
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$res = $responseObject->dataObject->subscriptions;
			foreach( $res as $value ){
				if (strrpos($value, "AreaAerospaziale") !== false){
					$subAero = true;
				}
				if (strrpos($value, "AreaAmbientale") !== false){
					$subAmbi = true;
				}
				if (strrpos($value, "AreaAutoveicolo") !== false){
					$subAuto = true;
				}
				if (strrpos($value, "AreaBiomeccania") !== false){
					$subBio = true;
				}
				if (strrpos($value, "AreaCinema") !== false){
					$subCine = true;
				}
				if (strrpos($value, "AreaCivile") !== false){
					$subCivi = true;
				}
				if (strrpos($value, "AreaElettrica") !== false){
					$subEle = true;
				}
				if (strrpos($value, "AreaElettronica") !== false){
					$subElettronica = true;
				}
				if (strrpos($value, "AreaEnergetica") !== false){
					$subEne = true;
				}
				if (strrpos($value, "AreaFisica") !== false){
					$subFisi = true;
				}
				if (strrpos($value, "AreaGestionale") !== false){
					$subGesti = true;
				}
				if (strrpos($value, "AreaInformatica") !== false){
					$subInfo = true;
				}
				if (strrpos($value, "AreaMatematica") !== false){
					$subMate = true;
				}
				if (strrpos($value, "AreaMateriali") !== false){
					$subMateri = true;
				}
				if (strrpos($value, "AreaMeccanica") !== false){
					$subMecca = true;
				}
				if (strrpos($value, "AreaTelecomunicazioni") !== false){
					$subTele = true;
				}
				if (strrpos($value, "commentGroup") !== false){
					$subCom = true;
				}
			}
		}
		
		?>

		<form action="" method="post" name="getSubscribeForm1">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Aerospaziale", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm2">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Ambientale", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm3">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Autoveicolo", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm4">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Biomeccania", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm5">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Cinema", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm6">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Civile", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm7">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Elettrica", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm8">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Elettronica", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm9">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Energetica", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm10">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Fisica", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm11">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Gestionale", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm12">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Informatica", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm13">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Matematica", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm14">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Materiali", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm15">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Meccanica", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm16">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Area", "Telecomunicazioni", KEYWORD))); ?>" type="hidden" >
		</form>		
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm1.submit();">Sottoscrivere ai testi di Aereospaziale</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm2.submit();">Sottoscrivere ai testi di Ambientale</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm3.submit();">Sottoscrivere ai testi di Autoveicolo</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm4.submit();">Sottoscrivere ai testi di Biomeccania</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm5.submit();">Sottoscrivere ai testi di Cinema</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm6.submit();">Sottoscrivere ai testi di Civile</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm7.submit();">Sottoscrivere ai testi di Elettrica</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm8.submit();">Sottoscrivere ai testi di Elettronica</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm9.submit();">Sottoscrivere ai testi di Energetica</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm10.submit();">Sottoscrivere ai testi di Fisica</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm11.submit();">Sottoscrivere ai testi di Gestionale</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm12.submit();">Sottoscrivere ai testi di Informatica</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm13.submit();">Sottoscrivere ai testi di Matematica</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm14.submit();">Sottoscrivere ai testi di Materiali</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm15.submit();">Sottoscrivere ai testi di Meccanica</a>
		<a type="button" data-theme="e" href="#" onclick="document.getSubscribeForm16.submit();">Sottoscrivere ai testi di Telecomunicazioni</a>
		

		<form action="" method="post" name="getSubscribeForm17">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("commentGroup", APPLICATION_NAME, KEYWORD))); ?>" type="hidden" >
		</form>
		<br />
		<br />
		<br />
		<a type="button" data-theme="b" href="#" onclick="document.getSubscribeForm17.submit();" >Sottoscrivere ai commenti</a>

	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" style="padding: 10px;" data-theme="c">';
			$this->getSubscribeContent();
			
		echo '</div>';
	}
}
?>
