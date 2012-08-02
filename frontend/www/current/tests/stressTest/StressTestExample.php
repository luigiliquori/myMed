<?php 

require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');

define('NB_LOOP', 13);

// Setup the graph
$graph = new Graph(1024,768);
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;

$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$graph->title->Set('Response Time From the Backend (ms)');
$graph->SetBox(false);

$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$xaxis = array();
for($i=0 ; $i < NB_LOOP ; $i++) {
	array_push($xaxis, 'Test' . $i);
}
$graph->xaxis->SetTickLabels($xaxis);
$graph->xgrid->SetColor('#E3E3E3');

// GET THE CREDENTIALs
$credential = json_decode(file_get_contents("http://" . $_SERVER['SERVER_ADDR'] . "/tests/stressTest/TestRequest.php?method=Initialize"));

// PUBLISH_TEST
$publishCounter = 0;
$yaxis = array();
for($i=0 ; $i < NB_LOOP ; $i++) {
	$y = file_get_contents("http://" . $_SERVER['SERVER_ADDR'] . "/tests/stressTest/TestRequest.php?" .
	"method=Publish" .
	"&pred1=a" . $i . 
	"&pred2=b" . $i . 
	"&pred3=c" . $i . 
	"&data=tes" .
	"&userID=" . $credential->userID . 
	"&accessToken=" . $credential->accessToken);
	
	$publishCounter += $y;
	array_push($yaxis, round($y*100));
}
// Create the first line
$p1 = new LinePlot($yaxis);
$graph->Add($p1);
$p1->SetColor("#B22222");
$p1->SetLegend('Publish');

// FIND_TEST
$findCounter = 0;
$yaxis = array();
for($i=0 ; $i < NB_LOOP ; $i++) {
	$y = file_get_contents("http://" . $_SERVER['SERVER_ADDR'] . "/tests/stressTest/TestRequest.php?" .
	"method=Find" .
	"&pred1=a" . $i . 
	"&pred2=b" . $i . 
	"&pred3=c" . $i . 
	"&userID=" . $credential->userID . 
	"&accessToken=" . $credential->accessToken);
	
	$findCounter += $y;
	array_push($yaxis, round($y*100));
}
// Create the second line
$p2 = new LinePlot($yaxis);
$graph->Add($p2);
$p2->SetColor("#6495ED");
$p2->SetLegend('Find');

$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();

echo "Pubilsh average time: " . $publishCounter/NB_LOOP;
echo "<br />";
echo "Find average time: " . $findCounter/NB_LOOP;

?>