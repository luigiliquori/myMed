<? include("header.php"); ?>
<div data-role="page" id="Search">
	<div class="wrapper">
		<div data-role="header" data-theme="a">
			<a data-rel="back" data-role="button"  data-icon="back" data-transition="slide">Back</a>
			<h2>myFSA</h2>
			<a href="?action=ExtendedProfile" data-icon="gear" class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
<!-- 			<a data-ajax="false" href="?action=Localisate" type="button" data-transition="slide" >Publish</a> -->
		</div>
		<div data-role="content" data-theme="a">
			<br />
			<ul data-role="listview" data-filter="true" data-inset="true" data-filter-placeholder="...">
				<!-- petla koncze! -->		
				<? if (count($this->result) == 0) :?>
				<li>
					<h4>No result found</h4>
				</li>
				<? endif ?>
				<?// $_SESSION['publication'] = $this->result; ?>
				<? foreach($this->result as $item) : ?>
				<li><a data-ajax="false" href="?action=details&predicate=<?= $item->getPredicateStr() ?>&author=<?= $item->publisherID ?>">			
					<!--<b>Publisher ID</b> : --><?= $item->publisherID ?>,
					<!--<b>Pred1</b>: --><?//= $item->pred1 ?>
					<!--<b>Pred2</b>: --><?= $item->pred2 ?>,
					<!--<b>Pred3</b>: --><?= $item->pred3 ?>,
					<!--<b>Begin</b>: --><?= $item->begin ?>,
					<!--<b>End</b>: --><?= $item->end ?>
					<!--<b>Wrapped1</b>: --><?//= $item->wrapped1 ?><br/>
					<!--<b>Wrapped2</b>: --><?//= $item->wrapped2 ?><br/>
				</a></li>
				<? endforeach ?>
				<!-- petla koncze! -->
			</ul>
			<div data-role="collapsible" data-collapsed="true" data-theme="a" data-icon="plus">
				<h3>Recherche avancée</h3>
				<form action="#" id="subscribeForm">
				<div>
					<div data-role="fieldcontain" style="margin-left: auto;margin-right: auto;">
						<fieldset data-role="controlgroup" >
							<label for="textinputs1"> Cathegory: </label> <input id="textinputs1"  name="nom" placeholder="" value="" type="text" />
							</fieldset>
					</div>
					<div data-role="fieldcontain" style="margin-left: auto;margin-right: auto;">
						<fieldset data-role="controlgroup" >
						<label for="textinputs2"> Title: </label> <input id="textinputs2"  name="lib" placeholder="" value="" type="text" />
						</fieldset>
					</div>
						<a href="" type="button" data-icon="gear" onclick="$('#subscribeForm').submit();" style="width:280px;margin-left: auto;margin-right: auto;">rechercher</a></div>
						</form>
				</div>
				<div class="push"></div>
				</div>
			</div>
<? include("footer.php"); ?>
</div>
