/**
 * This javascript file help to create easily graphs
 * 
 * It use jqplot library you can download it here: https://bitbucket.org/cleonello/jqplot/downloads/
 * 
 * To use it you have to include in your php file:
 * 
 * 	<script src="<?= APP_ROOT ?>/javascript/jqplot/plugins/jqplot.canvasTextRenderer.min.js" type="text/javascript"></script>
 * 	<script src="<?= APP_ROOT ?>/javascript/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js" type="text/javascript"></script>
 * 	<script type="text/javascript" src="<?= APP_ROOT ?>/javascript/jqplot/jquery.min.js"></script>
 *	<script type="text/javascript" src="<?= APP_ROOT ?>/javascript/jqplot/jquery.jqplot.min.js"></script>
 *	<script type="text/javascript" src="<?= APP_ROOT ?>/javascript/jqplot/jquery.jqplot.js"></script>
 * 	<link href="<?= APP_ROOT ?>/css/jqplot/jquery.jqplot.css" rel="stylesheet" type="text/css" />
 * 
 */

/**
 * Create a plot graphic with jqplot with 4 curves max
 * usage:
 * var curve1 = [[1,3],[2,3],[3,4],...]
 * var curve2 = [[1,3],[2,3],[3,4],...]
 * var serie = [curve1,curve2,...]
 * @param container String container name
 * @param serie cf above
 * @param name String graph name
 * @param padding int 
 * @param labelx String name
 * @param labely String name
 */
function createPlotGraph(conteneur,serie,name,padding,labelx,labely){
	$(document).ready(function(){
		courbe = $.jqplot(conteneur, serie, {
			//graph title
			title: name,
			//axis style
			axesDefaults: {
				labelRenderer: $.jqplot.CanvasAxisLabelRenderer
			},
			axes: {
				xaxis: {
					//x axis
					label: labelx,
					pad: padding
				},
				yaxis: {
					//y axis
					label: labely
				}
			},
			series: createPlotGraphCurveStyle(serie.length)
		});
	});
}

/**
 * Create style of different curve for plot graph
 * @param curveNumber
 * @returns
 */
function createPlotGraphCurveStyle(curveNumber){
	curveStyleArray = new Array();
	for(var i=0;i<curveNumber;i++){
		switch(i){
		//style curve 0
		case 0:
			curveStyleArray.push({
				// Change our line width and use a diamond shaped marker.
				//lineWidth:2,
				//markerOptions: { style:'dimaond' }
			});
			break;
			//style curve 1
		case 1:
			curveStyleArray.push({
				// Use (open) circlular markers.
				markerOptions: { style:"circle" }
			});
			break;
			//style curve 2
		case 2:
			curveStyleArray.push({
				// Don't show a line, just show markers.
				// Make the markers 7 pixels with an 'x' style
				showLine:false,
				markerOptions: { size: 7, style:"x" }

			});
			break;
			//style curve 3
		case 3:
			curveStyleArray.push({
				// Use a thicker, 5 pixel line and 10 pixel
				// filled square markers.
				lineWidth:5,
				markerOptions: { style:"filledSquare", size:10 }
			});
			break;
		}
	}
	return curveStyleArray;
}


function createBarGraph(container,series,labels,names,title){
	$(document).ready(function(){
		$.jqplot.config.enablePlugins = true;
		plot1 = $.jqplot(container, series , {

			//graph title
			title: title,

			// The "seriesDefaults" option is an options object that will
			// be applied to all series in the chart.
			seriesDefaults:{
				renderer:$.jqplot.BarRenderer,
				rendererOptions: {
					barPadding: 1,
					barMargin: 15,
					barDirection: 'vertical',
					barWidth: 50
				},
				pointLabels: { show: true }
			},

			// Custom labels for the series are specified with the "label"
			// option on the series option.  Here a series option object
			// is specified for each series.
			series: createLabelBarGraph(labels),

			// Show the legend and put it outside the grid, but inside the
			// plot container, shrinking the grid to accomodate the legend.
			// A value of "outside" would not shrink the grid and allow
			// the legend to overflow the container.
			legend: {
				show: true,
				placement: 'outsideGrid'
			},
			axes: {
				// Use a category axis on the x axis and use our custom ticks.
				xaxis: {
					renderer: $.jqplot.CategoryAxisRenderer,
					ticks: names
				},
				// Pad the y axis just a little so bars can get close to, but
				// not touch, the grid boundaries.  1.2 is the default padding.
				yaxis: {
					pad: 1.05,
					//tickOptions: {formatString: '$%d'}
				}
			}
		});
	});
}


function createLabelBarGraph(labels){
	arrayLabel = new Array();
	for(var i=0;i<labels.length;i++){
		arrayLabel.push({
			label: labels[i]
		});
	}
	return arrayLabel;
}