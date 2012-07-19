<? include("header.php"); ?>
		<div data-role="page" id="Search">
		<div class="wrapper">
		<div data-role="header" data-theme="b">
		<a href="?action=ExtendedProfile" data-icon="arrow-r" class="ui-btn-left" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
							<h2>myFSA</h2>
							<a data-ajax="false" href="?action=Localisate" data-theme="b" type="button" data-transition="slide" >Publish</a>
						</div>
						<div data-role="content">
						<br />
							<ul data-role="listview" data-filter="true" data-inset="true" data-filter-placeholder="...">
			
			
			<!-- halo zaczynam! -->		
			
			<? if (count($this->result) == 0) :?>
			<li>
				<h4>No result found</h4>
			</li>
			<? endif ?>
			
			<? foreach($this->result as $item) : ?>
				<li>
					<a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">			
						<b>Publisher ID</b> : <?= $item->publisherID ?><br/>
						<b>Pred1</b>: <?= $item->pred1 ?><br/>
						<b>Pred2</b>: <?= $item->pred2 ?><br/>
						<b>Pred3</b>: <?= $item->pred3 ?><br/>
						<b>Begin</b>: <?= $item->begin ?><br/>
						<b>End</b>: <?= $item->end ?><br/>
						<b>Wrapped1</b>: <?= $item->wrapped1 ?><br/>
						<b>Wrapped2</b>: <?= $item->wrapped2 ?><br/>
					</a>
				</li>
			<? endforeach ?>
			
		</ul>
		<!-- halo zaczynam! -->
							
							<div data-role="collapsible" data-collapsed="true">
								<h3>Recherche avancée</h3>
								<form action="#" id="subscribeForm">
									<div>
									<div data-role="fieldcontain" style="margin-left: auto;margin-right: auto;">
										<fieldset data-role="controlgroup" >
											<label for="textinputs1"> Nom de l'organisme bénéficiaire: </label> <input id="textinputs1"  name="nom" placeholder="" value="" type="text" />
										</fieldset>
									</div>
									<div data-role="fieldcontain" style="margin-left: auto;margin-right: auto;">
										<fieldset data-role="controlgroup" >
											<label for="textinputs2"> Libellé du projet: </label> <input id="textinputs2"  name="lib" placeholder="" value="" type="text" />
										</fieldset>
									</div>
									<a href="" type="button" data-icon="gear" onclick="$('#subscribeForm').submit();" style="width:280px;margin-left: auto;margin-right: auto;">rechercher</a></div>
								</form>
							</div>
							<div class="push"></div>
						</div>
					</div>
					</div>
<? include("footer.php"); ?>