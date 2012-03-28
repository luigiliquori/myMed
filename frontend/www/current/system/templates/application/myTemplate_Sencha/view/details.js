Ext.define('myTemplate.view.details', {
  extend: 'Ext.Panel',
  
  config:{
  	title: 'Détails',
  	id: 'details',
    layout: 'fit',
    scrollable: true,
    styleHtmlContent: true,
    tpl: ['<div>',
      '<img src="',
      '<tpl if="publisherProfilePicture">',
      	'{publisherProfilePicture}',
      '<tpl else>',
     		'http://graph.facebook.com//picture?type=large',
      '</tpl>',
      '" width="180" style="float:right;">',
      '<b>Nom</b>: <span style="left-margin:5px; color:DarkBlue; font-size:160%;">{publisherName}</span>',
      '<br>',
      '<b>Prédicat</b>: {predicate}',
      '<br>',
      '<b>Texte</b>:',
      '<div id="detailstext">{text}</div>',
      '</div>'
    ],
    
    map: Ext.create('Ext.Map')
		
  }

});