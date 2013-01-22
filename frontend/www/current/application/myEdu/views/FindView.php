<!-- 2nd Page : Find/Subscribe -->

<div id="find" data-role="page">

	<? print_header_bar(true, true, "Find"); ?>
	
	<div data-role="content">
	
		<form action="index.php?action=find" method="POST" data-ajax="false">
			
			<input type="hidden" name="method" value="Find" />
			<input type="hidden" id="find_area" name="zone" value="" />
			<input type="hidden" id="find_category" name="category" value="" />
			
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3><?= _("How to find") ?>?</h3>
				<?= _("Here how to find")?>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Find a publication') ?> :</h3>
			
	   			<fieldset data-role="controlgroup">
					<select name="Area">
						<option value=""><?= _("Area") ?></option>
						<option value="Aerospaziale"><?= _("Aéronautique") ?></option>
						<option value="Ambientale"><?= _("Environnement") ?></option>
						<option value="Autoveicolo"><?= _("Véhicules automobiles") ?></option>
						<option value="Biomeccania"><?= _("Biomeccania") ?></option>
						<option value="Cinema"><?= _("Cinèma") ?></option>
						<option value="Civile"><?= _("Civil") ?></option>
						<option value="Elettrica"><?= _("Èlectricité") ?></option>
						<option value="Elettronica"><?= _("Electronics") ?></option>
						<option value="Energetica"><?= _("Energie") ?></option>
						<option value="Fisica"><?= _("Physique") ?></option>
						<option value="Gestionale"><?= _("Gestion") ?></option>
						<option value="Informatica"><?= _("Informatique") ?></option>
						<option value="Matematica"><?= _("Mathématiques") ?></option>
						<option value="Materiali"><?= _("Matériaux") ?></option>
						<option value="Meccanica"><?= _("Mécanique") ?></option>
						<option value="Telecomunicazioni"><?= _("Télécommunications") ?></option>
					</select>
						
					<?php $dataBean = new MDataBean("Area", null, KEYWORD); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
					<!-- Categoria -->
					<select name="Categoria">
						<option value=""><?= _("Category") ?></option>
						<option value="Stage"><?= _("Stage") ?></option>
						<option value="Job"><?= _("Job") ?></option>
						<option value="Tesi"><?= _("Thèse") ?></option>
						<option value="Appunti"><?= _("Remarques") ?></option>
					</select>
					<?php $dataBean = new MDataBean("Categoria", null, KEYWORD); ?>
					<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($dataBean)); ?>"><br/>
				</fieldset>
			
				<!-- ADVANCED RESEARCH -->
				<div data-role="collapsible" data-collapsed="true" data-theme="a" data-content-theme="d" data-mini="true" style="margin-left:25px; margin-right:25px;">
					<h3><?= _("Advanced research")?></h3>
					
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="orga" id="check-view-a"/> <label for="check-view-a"><?= _("Organization")?></label>
						</div>
						<div class="ui-block-b">
							<select name="Organisation">
								<option value=""><?= _("??") ?></option>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="loca" id="check-view-b"/> <label for="check-view-b"><?= _("Localisation")?></label>
						</div>
						<div class="ui-block-b">
							<select name="Localisation">
								<option value=""><?= _("??") ?></option>
							</select>
						</div>
					</div>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a">
							<input type="checkbox" name="cate" id="check-view-c"/> <label for="check-view-c"><?= _("Category")?></label>
						</div>
						<div class="ui-block-b">
							<select name="Categoria">
								<option value=""><?= _("??") ?></option>
							</select>
						</div>
					</div>
					
				</div>
			
				<div style="text-align: center;">
					<input type="submit" data-icon="search" data-theme="g" value="<?=_('Search') ?>"  data-iconpos="right" data-inline="true" onclick="
						$('#search_theme').val($('#search_theme_content').val());
						$('#search_other').val($('#search_other_content').val());
					"/>
				</div>
			</div>
		</form>
		
		<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
			<h3><?= _('Last publications') ?> :</h3>
			<?	
			$i=0;
			foreach($results as $result) { ?>
				<li>
					<!-- RESULT DETAILS -->
					<form action="#DetailView" method="post" name="getDetailForm<?= $i ?>">
						<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
						<input type="hidden" name="method" value="getDetail" />
						<input type="hidden" name="user" value="<?= $result->publisherID ?>" />
						<input type="hidden" name="predicate" value="<?= $result->predicate ?>" />
					</form>
					<a href="#" onclick="document.getDetailForm<?= $i ?>.submit()">
						<?php 
						$area=strstr($result->predicate, "Area");
						$n=strpos($area, "Categoria");
						$area=substr($area, 4, $n-4);
						$categoria=strstr($result->predicate, "Categoria");
						$n=strpos($categoria, "begin");
						$categoria=substr($categoria, 9 , $n-9);


						?><span><?= $categoria." ".$area?></span>

						<span><font color=”blue”><center><?= $result->data ?></center></font></span>

						<?php
						$data=strstr($result->predicate, "begin");
						$data=substr($data, 5,10);
						?>
						<span><?= $data?></span>
					</a>
				</li>
				<?php $i++ ?>
			<?php } ?>
		</div>
			
	</div>
	
	<? print_footer_bar_main("#find"); ?>

</div>

