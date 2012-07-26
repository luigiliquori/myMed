<? include("header.php"); ?>
<div data-role="page" id="PublishView" data-theme="a">
<div class="wrapper">
<div data-role="header" data-theme="a">
<a data-theme="a" data-rel="back" data-role="button"  data-icon="back" >Back</a>
<h3>myFSA</h3>
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
			<input type="text" name="pred3" placeholder="pred3"/>
			
			<br/>
			
			<input type="text" name="begin"placeholder="begin"/>
			<input type="text" name="end" placeholder="end"/>

			<br/>
			
			<input type="text" name="wrapped1" placeholder="wrapped1"/>
			<input type="text" name="wrapped2" placeholder="wrapped2"/>
			
			<br/>
			
			<input type="text" name="data1" placeholder="data1"/>
			<input type="text" name="data2" placeholder="data2"/>
			<input type="text" name="data3" placeholder="data3"/>
			
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