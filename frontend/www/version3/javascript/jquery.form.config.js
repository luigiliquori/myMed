if(!$.tools.dateinput.localize.languages)$.tools.dateinput.localize.languages = new Array();console.log($.tools.dateinput.conf);
$.tools.dateinput.localize.languages["en"]	= true;
$.tools.dateinput.conf.lang	= $.tools.dateinput.localize.languages[document.documentElement.lang]?document.documentElement.lang:"en";
$.tools.dateinput.conf.format	= 'yyyy-mm-dd';
$.tools.dateinput.conf.firstDay	= 1;	// use monday as first day (default is Sunday)
//$.tools.dateinput.conf.selectors	= true;	// use <select> fr month and year
$.tools.dateinput.conf.yearRange	= [-20, 20];
