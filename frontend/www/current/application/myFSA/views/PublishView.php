<? include("header.php"); ?>
<div data-role="page" id="PublishView" data-theme="a">
<div class="wrapper">
<div data-role="header" data-theme="a">
<a data-theme="a" data-rel="back" data-role="button"  data-icon="back" >Back</a>
<h3>myFSA</h3>
<a href="?action=ExtendedProfile" data-icon="gear" class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
</div>
<div data-role="content">
<!-- tutaj halo-->
			<form action="index.php?action=publish" method="POST" data-ajax="false">
			
<!-- 			<input type="text" name="pred1" placeholder="pred1"/> -->
			    <div data-role="fieldcontain">
                    <label for="selectmenu1">
                        cathegory:
                    </label>
                    <select name="pred2" id="selectmenu1">
                        <option value="evenement">
                            Evénement
                        </option>
                        <option value="actualite">
                            Actualité
                        </option>
                        <option value="Offre d’emploi">
                            Offre d’emploi
                        </option>
                        <option value="Offre de stage">
                            Offre de stage
                        </option>
                         <option value="Location bureaux">
                            Location bureaux
                         </option>
                         <option value="Partenariats">
                            Partenariats
                         </option>
                         <option value="Organisme">
                            Organisme
                         </option>
                         <option value="Visite d’entreprise">
                            Visite d’entreprise
                         </option>
                    </select>
                </div>
			
<!-- 		<input type="text" name="pred2" placeholder="pred2"/> -->
			<input type="text" name="pred3" placeholder="title"/>			
						
			<input type="text" name="begin"placeholder="field1"/>
			<input type="text" name="end" placeholder="field2"/>
			
<!-- 			<input type="text" name="wrapped1" placeholder="wrapped1"/> -->
<!-- 			<input type="text" name="wrapped2" placeholder="wrapped2"/> -->
			<br/>
			<script type="text/javascript">
			 	// Dictionnary of already initliazed pages
				gInitialized = {}
				// Initialization made on each page 
				$('[data-role=page]').live("pagebeforeshow", function() {
 				var page = $(this);
 				var id = page.attr("id");
			 	// Don't initialize twice
				if (id in gInitialized) return;
 				gInitialized[id] = true;
				//debug("init-page : " + page.attr("id"));
				console.log('hello');
				$("#CLEeditor").cleditor({width:500, height:180, useCSS:true})[0].focus();
				});
			</script>			
			<textarea id="CLEeditor" name="data1"></textarea>
<!-- 			<input type="text" name="data2" placeholder="data2"/> -->
<!-- 			<input type="text" name="data3" placeholder="data3"/> -->
			
			<input type="submit" name="method" value="Publish"/>
			<!--<input type="submit" name="method" value="Search" />-->
			
		</form>
<!-- tutaj koniec ehehe -->
	<div class="push"></div>
				</div>
			</div>
			<div data-role="footer" data-theme="c" class="footer">
			<div style="text-align: center;">
				
			</div>
		</div>
<? include("footer.php"); ?>
		</div>
	</body>
</html>