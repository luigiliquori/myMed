function MyDesktop(/*HTMLDivElement*/ desktop/*=document.getElementById("desktop")*/, /*int*/ colNb/*=3*/)
{
	var colNb	= colNb||3;
	var columns	= new Array();
	var desktop	= desktop||document.getElementById("desktop");
	/*HTMLDivElement*/ this.getHTMLElement	= function(){return desktop;};
	this.addWindow = function(/*MyWindow*/ window)
	{
		if(!columns[window.getColumn()])
			window.setColumn(0);
		columns[window.getColumn()].appendChild(window);
	};
	this.getColumnByPixelOffset = function(/*int*/ x)
	{
		var i = Math.floor((x*colNb)/desktop.clientWidth);
		if(i<0)			i=0;
		if(i>=colNb)	i = colNb-1;
		return columns[i];
	}
	{
		for(var i=0 ; i<colNb ; i++)
		{
			var div	= document.createElement("div");
			div.className	= "column col"+i;
			div.style.width	= (100/colNb)+"%";
			columns.push(div);
			desktop.appendChild(div);
		}
	}
}
