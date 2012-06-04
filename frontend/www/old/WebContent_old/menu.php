<!-- MENU -->
	<div id="menu">
		<div style="position:absolute; width: 702px; left:-1px; height: 30px; background-image:url('img/toolbar.png'); border: thin #d2d2d2; solid; ">
			<table>
			  <tr>
			    <td><img alt="" src="img/menu/fichier.png" height="15px" onmouseover="changeMenu('fichier')" /></td>
			    <td><img alt="" src="img/menu/edition.png" height="15px" onmouseover="changeMenu('edition')" /></td>
			    <td><img alt="" src="img/menu/aide.png" height="15px" onmouseover="changeMenu('aide')" /></td>
			  </tr>
			</table>
		</div>
		
		<!-- MENU ITEMS -->
		<!-- FICHIER -->
		<div id="fichier" style="position: absolute; top:21px; width:100px; background-color: #415b68; opacity : 0.8; filter : alpha(opacity=10); z-index: 99; padding: 2px; display: none;">
			<div style="position: relative; left:10px;">
				<a href="#" onclick="displayWindow('#warning')">New</a><br>
				<a href="#" onclick="displayWindow('#warning')">Open</a><br>
				<hr style="position:relative; left: -10px;">
				<a href="#" onclick="displayWindow('#warning')">Close</a><br>
				<hr style="position:relative; left: -10px;">
				<a href="#" onclick="displayWindow('#warning')">Save</a><br>
				<hr style="position:relative; left: -10px;">
				<a href="#" onclick="displayWindow('#warning')">Quit</a><br>
			</div>
		</div>
		
		<!-- Edition -->
		<div id="edition" style="position: absolute; top:21px; left: 57px; width:100px; background-color: #415b68; opacity : 0.8; filter : alpha(opacity=10); z-index: 99; padding: 2px; display: none;">
			<div style="position: relative; left:10px;">
				<a href="#" onclick="displayWindow('#warning')">Annuler</a><br>
				<a href="#" onclick="displayWindow('#warning')">Rétablir</a><br>
				<hr style="position:relative; left: -10px;" />
				<a href="#" onclick="displayWindow('#warning')">Couper</a><br>
				<a href="#" onclick="displayWindow('#warning')">Couper</a><br>
				<a href="#" onclick="displayWindow('#warning')">Coller</a><br>
				<hr style="position:relative; left: -10px;" />
				<a href="#" onclick="displayWindow('#warning')">Tout séléctionner</a><br>
				<hr style="position:relative; left: -10px;" />
				<a href="#" onclick="displayWindow('#warning')">Rechercher</a><br>
			</div>
		</div>
		
		<!-- Aide -->
		<div id="aide" style="position: absolute; top:21px; left: 113px; width:100px; background-color: #415b68; opacity : 0.8; filter : alpha(opacity=10); z-index: 99; padding: 2px; display: none;">
			<div style="position: relative; left:10px;">
				<a href="#" onclick="displayWindow('#warning')">Aide myMed</a><br>
				<a href="#" onclick="displayWindow('#warning')">A propos</a><br>
				<hr style="position:relative; left: -10px;" />
				<a href="#" onclick="displayWindow('#warning')">Contact</a><br>
			</div>
		</div>
	</div>