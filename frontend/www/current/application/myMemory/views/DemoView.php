<!--
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
 -->
<?php include("header.php")?>
<? include("notifications.php")?>
<div data-role="page" id="Home">
	<div data-role="header" data-position="fullscreen">
		<a href="index.php?action=login" class="homeButton ui-btn-left" data-transition="pop" >Log In</a>
		<a href="#Quit" class="homeButton ui-btn-right" data-transition="pop" data-icon="delete">Quit</a>
		<h3>myMemory</h3>
	</div>
	<div data-role="content" data-theme="a">
		<a href="#GoingBack" data-role="button" class="homeButton ui-btn-up" data-transition="slide" data-direction="reverse" data-icon="home">Je rentre chez moi !</a>
		<a href="need_help.php" data-ajax="false" data-role="button" class="homeButton ui-btn-up-r" data-transition="slide" data-direction="reverse" data-icon="alert">Au secours !</a>
	</div>
	
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
		<ul>
			<li><a href="#Dial" data-role="button" data-transition="slide">Dial</a></li>
			<li><a href="#Map" data-role="button" data-transition="slide">Map</a></li>
			<li><a href="#Profile" data-role="button" data-transition="slide">Profile</a></li>
			<li><a href="#ImGood" data-role="button" data-transition="slide">+</a></li>
			<li><a href="#NotSoGood" data-role="button" data-transition="slide">-</a></li>
		</ul>
		</div><!-- /navbar -->
	</div><!-- /footer -->
</div>
<div data-role="page" id="GoingBack">
	<div data-role="content" data-theme="a">
		<div id="map_canvas" style="height:200px;"></div>
		<br />
		<ul data-role="listview" data-inset="true" data-filter="true">
			<li data-icon="home" data-theme="b"><a href="#">Domicile</a></li>
			<li><a href="#">Mme Rose Dupont</a></li>
			<li><a href="#">M. Luc Davan</a></li>
			<li><a href="#">Association "Moi et Toi"</a></li>
			<li data-icon="alert" data-theme="e"><a href="#">SAMU</a></li>
		</ul>
	</div>
	<div data-role="footer" data-position="fixed">
	</div>
</div>


<div data-role="page" id="Subscribe">
	<div data-theme="b" data-role="header" data-position="fixed">
		<a href="login.php" class="homeButton ui-btn-left" data-transition="slide">Log In</a>
		<h3>myTemplate</h3>
		<div data-role="navbar" data-iconpos="left">
			<ul>
				<li><a href="#Publish" data-theme="c" data-icon="grid"> Publish </a></li>
				<li><a href="#Subscribe" data-theme="c" data-icon="alert" class="ui-btn-active ui-state-persist"> Subscribe </a></li>
			</ul>
		</div>
	</div>
	<div data-role="content">
		<form action="subscribe.php" method="post" id="subscribeForm" >
			<input name="application" value="myTemplate" type="hidden" />
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputs1"> keyword: </label> <input id="textinputs1"  name="keyword" placeholder="" value="" type="text" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputs2"> position: </label> <input id="textinputs2"  name="data" placeholder="" value="" type="text" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputs3"> catégorie: </label> <input id="textinputs3"  name="enum" placeholder="" value="" type="text" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputs4"> date: </label> <input id="textinputs4"  name="end" placeholder="" value="" type="date" />
				</fieldset>
			</div>
			<input type="submit" data-theme="g" value="Souscrire" />
		</form>
		<form action="subscriptions.php">
			<input name="application" value="myTemplate" type="hidden" />
			<input type="submit" data-theme="e" value="Gérer ses Souscriptions" data-transition="flip" />
		</form>
	</div>
</div>


<div data-role="page" id="Results">
	<div data-theme="b" data-role="header" data-position="fixed">
		<h3>Résultats</h3>
	</div>
	<div data-role="content">
		<ul data-role="listview" data-filter="true">
			<li><a href="">Hello </a></li>
			<li><a href="">Bye	</a></li>
		</ul>
	</div>
</div>

<div data-role="page" id="Profile">
	<div data-theme="b" data-role="header" data-position="fixed">
		<a href="#Find" class="ui-btn-left" data-transition="slide">Back</a>
		<h3>myTemplate</h3>
	</div>
	<div data-role="content">
		<form action="">
			<a href="#Deconnect" type="button" onclick="disconnect();">Déconnecter</a>
			<a href="#Update" type="button" onclick="updateProfile();" data-transition="flip">Mettre à jour</a>
		</form>
	</div>
</div>
<div data-role="page" id="Register">
	<div data-theme="b" data-role="header" data-position="fixed">
		<a href="#Connect" class="ui-btn-left" data-transition="flip" data-direction="reverse">Back</a>
		<h3>myTemplate</h3>
	</div>
	<div data-role="content">
		<form action="" id="registerForm">
			<input name="application" placeholder="" value="jqm" type="hidden" />
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputr1"> Prénom: </label> <input id="textinputr1"  name="prenom" placeholder="" value="" type="text" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputr2"> Nom: </label> <input id="textinputr2"  name="nom" placeholder="" value="" type="text" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputr3"> eMail: </label> <input id="textinputr3"  name="email" placeholder="" value="" type="email" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputr4"> Password: </label> <input id="textinputr4"  name="password" placeholder="" value="" type="password" />
				</fieldset>
			</div>
			<a href="#Find" type="button" onclick="register();">Créer</a>
		</form>
	</div>
</div>

<div data-role="page" id="Update">
	<div data-theme="b" data-role="header" data-position="fixed">
		<a href="#Profile" class="ui-btn-left" data-transition="flip" data-direction="reverse">Back</a>
		<h3>myTemplate</h3>
	</div>
	<div data-role="content">
		<form action="update.php" id="updateForm">
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputu1"> Prénom: </label> <input id="textinputu1"  name="prenom" placeholder="" value="" type="text" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputu2"> Nom: </label> <input id="textinputu2"  name="nom" placeholder="" value="" type="text" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputu3"> eMail: </label> <input id="textinputu3"  name="email" placeholder="" value="" type="email" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputu4"> Ancien Password: </label> <input id="textinputu4"  name="oldPassword" placeholder="" value="" type="password" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputu5"> Nouveau Password: </label> <input id="textinputu5"  name="password" placeholder="" value="" type="password" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputu6"> Date de naissance: </label> <input id="textinputu6"  name="birthday" placeholder="" value="" type="date" />
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<label for="textinputu7"> Photo du profil (lien url): </label> <input id="textinputu7"  name="thumbnail" placeholder="" value="" type="text" />
				</fieldset>
			</div>
			<a href="#Find" type="button" onclick="update();">Modifier</a>
		</form>
	</div>
</div>

<?php include("footer.php")?>