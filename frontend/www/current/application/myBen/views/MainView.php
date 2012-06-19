<? include("header.php"); ?>

<div data-role="page">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
		<p>
			Hello <?= $this->user->name ?> Welcome to the main page !
		</p>
	
		<form action="index.php?action=main" method="POST" data-ajax="false">
			
			<input type="text" name="pred1" placeholder="pred1"/>
			<input type="text" name="pred2" placeholder="pred2"/>
			<input type="text" name="pred3" placeholder="pred3"/>
			
			<br/>
			
			<input type="text" name="begin"placeholder="begin"/>
			<input type="text" name="end" placeholder="end"/>
			<input type="text" name="data" placeholder="data"/>

			<br/>
			
			<input type="text" name="data1" placeholder="data1"/>
			<input type="text" name="data2" placeholder="data2"/>
			<input type="text" name="data3" placeholder="data3"/>
			
			<input type="submit" name="method" value="Publish"/>
			<input type="submit" name="method" value="Search" />
			
		</form>
	
	<div>
		<? 
			if (isset($this->result)) {
				print_r($this->result);
			}
		?>
	</div>

	</div>

</div>

<? include("footer.php"); ?>