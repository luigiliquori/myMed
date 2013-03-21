<? include("header.php"); ?>
</head>
<body>
	<div data-role="page" id="PublishView" data-theme="b">
		<div data-role="header" data-theme="b" data-position="fixed">
			<h1 style="color: white;"><?= _("Publish") ?></h1>
			<a href="?action=main" data-inline="true" rel="external" data-role="button" data-icon="back"><?= _("Back")?></a>
			<? include_once "notifications.php"; ?>
		</div>

		<div data-role="content" data-theme="d" data-content-theme="d">
			<? print_notification($this->success.$this->error); ?>
			
			<form action="index.php?action=publish" method="POST" data-ajax="false">
				<h3><?= _('Title') ?><b>*</b> :</h3>
				<input type="text" name="pred3"/>
				<br>
		
				<h3><?= _('Category') ?><b>*</b> :</h3>
                <select name="pred2" id="selectmenu1" data-theme="d" data-native-menu="false" data-overlay-theme="d">
                   	<option value=""><?= _("Select category") ?></option>
                    <option value="Agenda"><?= _("Agenda") ?></option>
                    <option value="News"><?= _("News") ?></option>
                    <option value="Enterprises"><?= _("Enterprises") ?></option>
                    <option value="Jobs"><?= _("Jobs") ?></option>
                    <option value="Internships"><?= _("Internships") ?></option>
                    <option value="Visit an enterprise"><?= _("Visit an enterprise") ?></option>
                    <option value="Projects/partnership"><?= _("Projects/partnership") ?></option>
                    <option value="Office rental"><?= _("Office rental") ?></option>
                </select>
			
<!-- 			<input type="text" name="pred2" placeholder="pred2"/> -->
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
		
				<h3><?= _('Description') ?><b>*</b> :</h3>
				<textarea id="CLEeditor" name="data1"></textarea>
<!-- 			<input type="text" name="data2" placeholder="data2"/> -->
<!-- 			<input type="text" name="data3" placeholder="data3"/> -->
		
				<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
		
				<input type="hidden" name="method" value="Publier" />
			
				<center><input type="submit" value="<?= _('Publish') ?>" data-theme='g' data-inline="true" data-icon="check"/></center>
				<!--<input type="submit" name="method" value="Search" />-->
		
			</form>
		</div>
	</div>
</body>
</html>