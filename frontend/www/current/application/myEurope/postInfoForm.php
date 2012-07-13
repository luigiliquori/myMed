
<?php

//ob_start("ob_gzhandler");

require_once 'Template.php';
Template::init();


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
<script type="text/javascript">

$('#select_choice_1').live("change", function() {
    $('#select_display_1 .p_').hide();
    $('#select_display_2 .p_').hide();
    $('#select_display_1 .'+$(this).attr('value')).css('display', '');

});
$('#select_choice_01').live("change", function() {
    $('#select_display_2 .p_').hide();
    $('#select_display_2 .'+$(this).attr('value')).css('display', '');
});
$('#select_choice_02').live("change", function() {
    $('#select_display_2 .p_').hide();
    $('#select_display_2 .'+$(this).attr('value')).css('display', '');
});
$('#select_choice_03').live("change", function() {
    $('#select_display_2 .p_').hide();
    $('#select_display_2 .'+$(this).attr('value')).css('display', '');
});
</script>
</head>

<body>
	<div data-role="page" id="PostOffer">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-icon="back">Retour</a></li>
					<li><a data-icon="gear" data-theme="b" onclick="$('#publishForm').submit();">Envoyer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h1 style="text-align:center;">
				<a href="" style="text-decoration: none;">Commentez votre expérience sur myEurope:</a>
			</h1>
			<br />
			<form action="" method="post" id="publishForm">
				<input name="application" value="myEurope" type="hidden" />
				
				<input type="hidden" name="type" value="offer" />
				
<!-- 				<div data-role="fieldcontain"> -->
<!-- 					<fieldset data-role="controlgroup"> -->
<!-- 						<label for="textinputp2"> Titre du programme: </label> <input id="textinputp2" data-mini="true" name="id" placeholder="" value="programmeA" type="text" /> -->
<!-- 					</fieldset> -->
<!-- 				</div> -->

				<br />
				<div data-role="fieldcontain" style="margin-left:22%" >
				    <select name="select_choice_1" id="select_choice_1" data-mini="true">
				        <option disabled="disabled" selected>Thématique</option>
				        <option value="p1">Energie</option>
				        <option value="p2">B</option>
				        <option value="p3">C</option>
				    </select>
				</div>
				<div data-role="fieldcontain" id="select_display_1" style="margin-left:22%">
				    <div class="p1 p_" id="select-group-2" style="margin: 0; width: 100%; display: none;">
				        <select name="select_choice_01" id="select_choice_01" data-mini="true">
				            <option value="a1">Energie P1</option>
				            <option value="a2">Energie P2</option>
				            <option value="a3">Energie P3</option>
				            <option value="a4">Energie P4</option>
				        </select>
				    </div>
				    <div class="p2 p_" style="margin: 0; width: 100%; display: none;">
				        <select name="select_choice_02" id="select_choice_02" data-mini="true">
				            <option value="b1">B p1</option>
				            <option value="b2">B p2</option>
				            <option value="b3">B p3</option>
				            <option value="b4">B p4</option>
				        </select>
				    </div>
				    <div class="p3 p_" style="margin: 0; width: 100%; display: none;">
				        <select name="select_choice_03" id="select_choice_03" data-mini="true">
				            <option value="c1">C p1</option>
				            <option value="c2">C p2</option>
				            <option value="c3">C p3</option>
				            <option value="c4">C p4</option>
				        </select>
				    </div>
				</div>
				
				<div data-role="fieldcontain"  id="select_display_2" style="margin-left:22%">
				    <div class="a1 p_" style="margin: 0; width: 100%; display: none;">
				        <select name="select_choice_01" data-mini="true">
				            <option value="a1">Energie P1 s1</option>
				            <option value="a2">Energie P1 s2</option>
				            <option value="a3">Energie P1 s3</option>
				            <option value="a4">Energie P1 s4</option>
				        </select>
				    </div>
				    <div class="a2 p_" style="margin: 0; width: 100%; display: none;">
				        <select name="select_choice_02" data-mini="true">
				            <option value="b1">Energie P2 s1</option>
				            <option value="b2">Energie P2 s2</option>
				            <option value="b3">Energie P2 s3</option>
				            <option value="b4">Energie P2 s4</option>
				        </select>
				    </div>
				    <div class="a3 p_" style="margin: 0; width: 100%; display: none;">
				        <select name="select_choice_03" data-mini="true">
				            <option value="c1">Energie P3 s1</option>
				            <option value="c2">Energie P3 s2</option>
				            <option value="c3">Energie P3 s3</option>
				            <option value="c4">Energie P3 s4</option>
				        </select>
				    </div>
				    <div class="a4 p_" style="margin: 0; width: 100%; display: none;">
				        <select name="select_choice_04" data-mini="true">
				            <option value="c1">Energie P4 s1</option>
				            <option value="c2">Energie P4 s2</option>
				            <option value="c3">Energie P4 s3</option>
				            <option value="c4">Energie P4 s4</option>
				        </select>
				    </div>
				</div>
				
				
				<hr>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputp7"> Contenu: </label>
						<textarea style="min-height: 200px;" id="textinputp7" name="text" placeholder="" ></textarea>
					</fieldset>
				</div>

			</form>
		</div>
	</div>
</body>
</html>
