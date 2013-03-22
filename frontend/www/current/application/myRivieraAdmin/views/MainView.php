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
<? include("header.php"); ?>

<div id="home" data-role="page">

<? include("header-bar.php"); ?>

	<div data-role="content" class="content">
		
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
		
		<div data-role="collapsible" data-theme="b" data-content-theme="c" data-collapsed="false" Style="text-align: left;">
		    
		    <h3>Afficher les points d'interêts</h3>
		    
		    <!-- MAP -->
			<div id="myMap"></div><br />
			
			Longitude: <input id="marker_longitude" type="text" value="" data-inline="true" /><br />
		    Latitude: <input id="marker_latitude" type="text" value="" data-inline="true" /><br />
		    Radius: <input id="marker_radius" type="text" value="" data-inline="true" /><br />
		    Type:<select id="marker_type" >
						<option value="Transports">Transports</option>
						<option value="GARESSUD">Gares</option>
						<option value="Santé">Santé</option>
						<option value="MaisonsRetraites">Maisons de retraites</option>
						<option value="TourismeCulture">Tourisme Culture</option>
						<option value="AdressesUtiles">Adresses Utiles</option>
						<option value="Mairie">Mairie</option>
						<option value="Banques">Banques</option>
						<option value="Policemunicipale">Police</option>
						<option value="POSTES">Poste</option>
						<option value="Sports">Sports</option>
						<option value="STADES">Stades</option>
						<option value="Restaurants">Restaurants</option>
						<option value="PizzasEmporter">Pizzeria</option>
						<option value="Education">Education</option>
						<option value="Bibliotheques">Bibliotheques</option>
						<option value="IUT">IUT</option>
						<option value="colleges">Collèges</option>
						<option value="Primaires">Primaires</option>
						<option value="Maternelles">Maternelles</option>
						<option value="Company">Company</option>
				</select>
		    <center><a href="#" data-role="button" onclick="printMarkers($('#marker_type').val(), $('#marker_longitude').val(), $('#marker_latitude').val(), $('#marker_radius').val());" rel="external" data-theme="g" data-inline="true">Afficher</a></center>	
		</div>
	
		<!-- Convertion -->
		<div data-role="collapsible" data-theme="b" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
		   
		   <h3>Convertion LongLat/Adresse</h3>
		   
		   Adresse:<br />
		   <input id="converted_address" type="text" value="" /><br />
		   Longitude: <input id="converted_longitude" type="text" value="" data-inline="true" /><br />
		   Latitude: <input id="converted_latitude" type="text" value="" data-inline="true" /><br />
	   	  <center><a href="#" data-role="button" onclick="convertLatLng($('#converted_latitude').val(), $('#converted_longitude').val(), $('#converted_address').val());" data-theme="g" data-inline="true">Convertir</a></center>	
		</div>
		
		<!-- CARF POIs -->
		<div data-role="collapsible" data-theme="b" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			<h3>Ajouter points d'interêts</h3>
			
			<form action="?action=main" method="post" name="addSinglePOI" id="addSinglePOI" enctype="multipart/form-data">
				<input name="addPOI" type="hidden" value="1" />
				<input name="action" type="hidden" value="main" />
			 	Titre: <input name="title" type="text" value="" data-inline="true" /><br />
				Longitude: <input name="longitude" type="text" value="" data-inline="true" /><br />
			    Latitude: <input name="latitude" type="text" value="" data-inline="true" /><br />
			    Type:<select name="type">
						<option value="Transports">Transports</option>
						<option value="GARESSUD">Gares</option>
						<option value="Santé">Santé</option>
						<option value="MaisonsRetraites">Maisons de retraites</option>
						<option value="TourismeCulture">Tourisme Culture</option>
						<option value="AdressesUtiles">Adresses Utiles</option>
						<option value="Mairie">Mairie</option>
						<option value="Banques">Banques</option>
						<option value="Policemunicipale">Police</option>
						<option value="POSTES">Poste</option>
						<option value="Sports">Sports</option>
						<option value="STADES">Stades</option>
						<option value="Restaurants">Restaurants</option>
						<option value="PizzasEmporter">Pizzeria</option>
						<option value="Education">Education</option>
						<option value="Bibliotheques">Bibliotheques</option>
						<option value="IUT">IUT</option>
						<option value="colleges">Collèges</option>
						<option value="Primaires">Primaires</option>
						<option value="Maternelles">Maternelles</option>
				</select>
				<br />
			    IdMedia (url): <input name="IdMedia" type="text" value="" data-inline="true" /><br />
			    Icon (url): <input name="icon" type="text" value="" data-inline="true" /><br />
			    Link: <input name="Link" type="text" value="" data-inline="true" /><br />
			    Email: <input name="Email" type="text" value="" data-inline="true" /><br />
			    Adresse: <input name="Adresse" type="text" value="" data-inline="true" /><br />
			    Description: <textarea name="description" rows="" cols=""></textarea><br />
				<center><a href="#" data-role="button" onclick="document.addSinglePOI.submit()" rel="external" data-theme="g" data-inline="true">Publier</a></center>
			</form>
			
			<div data-role="collapsible" data-theme="d" data-content-theme="c" data-collapsed="true" Style="text-align: left;">
			    <h3>Avancé</h3>
				<form action="#" method="post" name="addMultiPOIs" id="addMultiPOIs" enctype="multipart/form-data">
					<input type="hidden" name="addPOI" value="1" />
					<input name="action" type="hidden" value="main" />
					<textarea name="data" rows="" cols=""></textarea>
					 <center><a href="#" data-role="button" onclick="document.addMultiPOIs.submit()" rel="external" data-theme="g" data-inline="true">Publier</a></center>
				</form>
			</div>
		</div>
		
		<!--SUBSCRIBE -->
		<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="c">
			<h3>S'abonner aux commentaires</h3>
			<p>S'abonner aux commentaires de myRiviera:</p>
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>SubscribeForm" id="<?= APPLICATION_NAME ?>SubscribeForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="subscribe" />
				<input type="hidden" name="numberOfOntology" value="1" />
				
				<!-- KEYWORD -->
				<input type="hidden" name="keyword" value="myRivieraTest"/>
				<?php $keywordBean = new MDataBean("keyword", null, KEYWORD); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keywordBean)); ?>">
			
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>SubscribeForm.submit()" >Subscribe</a>
			</form>
		</div>
	
	</div>

</div>

<? include("footer.php"); ?>