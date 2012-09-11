
<?

function tabs_main($activeTab, $tabs, $banner = false) {
	
 	$reverse = true;
 	?>
 	
  	
  		
  	<div data-role="header" data-theme="c" data-position="fixed">
 		
 		<? if ($banner): ?>
  		<div class="ui-header ui-bar-e" data-mini="true">
  			<span style="color: #588fbe; margin: 9px; font-size: 13px; font-weight: bold; "><a href="./"><img alt="myMed" src="../../system/img/logos/mymed" style="vertical-align: -25%;"/></a>Réseau social transfontalier</span>
  		</div>
  		<? endif; ?>
	  	<div data-role="navbar" data-theme="b"  data-iconpos="left"> 
		  	<ul >
		  		<? foreach ($tabs as $id => $v) { ?>
		  		<li>
		  			<a 
		  				href="#<?= $id ?>"  
		  				data-transition="slide" 
		  				data-icon="<?= $v[1] ?>" 
		  				<?= ($reverse) ? 'data-direction="reverse"' : '' ?>
		  				<?= ($activeTab == $id) ? 'class="ui-btn-down-c ui-state-persist"' : '' ?> >
		  				<?= $v[0] ?>
		  			</a>
		  		</li><? 
		  		
		  		if ($id == $activeTab) {
		  			$reverse = false;
		  		}
		  	}
		  
		  	?> 
		  	</ul>
	  	</div>
	  	
  	</div>
  		
  	
  	
 	
  	
  	 <?
   }
   
?>
