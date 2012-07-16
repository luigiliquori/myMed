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
<!-- tutaj halo-->
		<form action="index.php?action=publish" method="POST" data-ajax="false">
			
			<!--<input type="text" name="pred1" placeholder="pred1"/>
			  <input type="text" name="pred2" placeholder="pred2"/>
			<input type="text" name="pred3" placeholder="pred3"/>-->
			
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
			<input type="submit" name="method" value="Search" />
			
		</form>
<!-- tutaj koniec ehehe -->
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