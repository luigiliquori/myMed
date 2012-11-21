<!-- 2nd Page : Find/Subscribe -->
<div id="find" data-role="page">

	<? print_header_bar(false, true); ?>
	
	<div data-role="content">
	
		<form action="index.php?action=main" method="POST" data-ajax="false">
			
			<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
			
				<div data-role="collapsible" data-collapsed="false">
					<h3>Predicates</h3>
					<input type="text" name="pred1" placeholder="pred1"/>
					<input type="text" name="pred2" placeholder="pred2"/>
					<input type="text" name="pred3" placeholder="pred3"/>
				</div>
				
				<center><input type="submit" name="method" value="Search" data-theme="g" data-inline="true" data-icon="search"/></center>
			</div>
			
		</form>
			
	</div>
	
	<? print_footer_bar_main("#find"); ?>

</div>

