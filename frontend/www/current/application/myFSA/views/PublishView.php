<? include("header.php"); ?>
</head>
<body>

<div data-role="page" id="PublishView" data-theme="b">
	<div class="wrapper">

	<? include("header-bar.php"); ?>

	<div data-role="content">

			<form action="index.php?action=publish" method="POST" data-ajax="false">
			
					<input type="text" name="pred3" placeholder="<?= translate('Title') ?>"/>
					<br>
			
                    <select name="pred2" id="selectmenu1">
                        <option value="evenement">
                            <?= translate("Events") ?>
                        </option>
                        <option value="actualite">
                            <?= translate("News") ?>
                        </option>
                        <option value="Offre d’emploi">
                            <?= translate("Internship offers") ?>
                        </option>
                        <option value="Offre de stage">
                            <?= translate("Job offers") ?>
                        </option>
                         <option value="Location bureaux">
                            <?= translate("Rent Offices") ?>
                         </option>
                         <option value="Partenariats">
                            <?= translate("Partnerships") ?>
                         </option>
                         <option value="Organisme">
                            <?= translate("Organization") ?>
                         </option>
                         <option value="Visite d’entreprise">
                            <?= translate("Company visits") ?>
                         </option>
                    </select>
			
<!-- 		<input type="text" name="pred2" placeholder="pred2"/> -->
            <br />
			
<!-- 			<input type="text" name="wrapped1" placeholder="wrapped1"/> -->
<!-- 			<input type="text" name="wrapped2" placeholder="wrapped2"/> -->
			<script type="text/javascript">
				// Dictionnary of already initliazed pages
				gInitialized = {}
				// Initialization made on each page 
				$('[data-role=page]').live("pagebeforeshow", function() {
				var page = $("PublishView");
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
			
			<input type="hidden" name="method" value="Publier" />
			<center><input type="submit" value="<?= translate('Publish') ?>" data-inline="true" data-icon="check"/></center>
			<!--<input type="submit" name="method" value="Search" />-->
			
		</form>
<!-- tutaj koniec ehehe 
	<div class="push"></div>
				</div>
			</div>
			<div data-role="footer" data-theme="c" class="footer">
			<div style="text-align: center;">
				
			</div>
		</div>-->
		
		<? include("footer.php"); ?>
		
		</div>
	</body>
</html>