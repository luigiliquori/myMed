<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=650" />
<title>
myFSA
</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js">
</script>
<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js">
</script>
</head>
<body>
<div data-role="page" id="PublishView">
<div class="wrapper">
<div data-role="header" data-theme="b">
<a data-theme="b" data-rel="back" data-role="button"  data-icon="back">Back</a>
<h3>myFSA</h3>
</div>
<div data-role="content">
					<form action="?action=Publish" method="post" name="Publish" id="Publish" data-ajax="false">
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp1"> key1 </label> <input id="textinputp1"  name="key1" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp2"> key2  </label> <input id="textinputp2"  name="key2" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp3"> key3</label> <input id="textinputp3"  name="key3" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp6"> Text: </label> <textarea id="textinputp6"  name="publication" placeholder="" value=""></textarea>
							</fieldset>
						</div>
						<input type="submit" data-theme="g" value="Publish"/>
					</form>
					<div class="push"></div>
				</div>
			</div>
			<div data-role="footer" data-theme="c" class="footer">
			<div style="text-align: center;">
				
			</div>
		</div>
		</div>
	</body>
</html>