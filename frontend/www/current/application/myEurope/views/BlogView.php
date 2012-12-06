<div data-role="page" id="Blog">
	
	<div data-role="content" style="text-align:center;">
		
		<select id="mySelect" data-ajax="false"> 
		<optgroup label="<?= _('Phases du projet') ?>" id="one">  
		<option><?= _('Ideas') ?></option>      
    	<option><?= _('Partners search') ?></option> 
    	<option><?= _('Application') ?></option> 
    	<option><?= _('Project implementation') ?></option> 
    	<option><?= _('Monitoring and evaluation') ?></option> 
    	<option><?= _('Communication') ?></option> 
		</optgroup>
		
		<optgroup label="<?= _('Thèmes de projet') ?>" id="two">  
    	<option><?= ('Education, culture and sport') ?></option> 
		<option><?= ('Work and training') ?></option>
		<option><?= ('Enterprises, Research and Innovation') ?></option>
		<option><?= ('Transport, Facilities and Zoning') ?></option>
		<option><?= ('Health and Consumer Protection') ?></option>
		<option><?= ('Social Affairs') ?></option>
		<option><?= ('Agriculture') ?></option>
		<option><?= ('Fishing') ?></option>
		</optgroup>
		</select>
		<br></br>
		
		<script>
	    $("select").change(function () {
	          var pred1 = "";
	          $("select option:selected").each(function () {
	        	  pred1 = $(this).text();
	              });
	          //$.post("?action=Blog", { 'method': "Search", 'pred1' : pred1 } );
	          window.location.href = "?action=Blog&method=Search&pred1="+pred1;
	        }).trigger('select');
		</script>		
				<ul data-role="listview" data-theme="d">
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4>No result found</h4>
			</li>
			<? endif ?>
			
			<? foreach($this->result as $item) : ?>
				<li>
					<!-- Print Publisher reputation -->
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">		
						<h3><?= $item->pred2 ?></h3>
						
						<p>
							Publisher ID: <?= $item->publisherID ?><br/>
							reputation: 
							<?php for($i=1 ; $i <= 5 ; $i++) { ?>
								<?php if($i*20-20 < $this->reputationMap[$item->publisherID] ) { ?>
									<img alt="rep" src="img/yellowStar.png" width="10" Style="left: <?= $i ?>0px; margin-left: 80px; margin-top:3px;" />
								<?php } else { ?>
									<img alt="rep" src="img/grayStar.png" width="10" Style="left: <?= $i ?>0px; margin-left: 80px; margin-top:3px;"/>
								<?php } ?>
							<? } ?>
						</p>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
	</div>
</div>