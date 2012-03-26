Ext.define('myTemplate.view.results', {
  extend: 'Ext.List',

  config: {
		id: 'resultList',
	  title: 'Résultats',
    itemTpl: ['<span><img src="',
    '<tpl if="publisherProfilePicture">',
    	'{publisherProfilePicture}',  
    '<tpl else>',
   		'http://graph.facebook.com//picture?type=large',
    '</tpl>',
    '" width="60" height="60" /></span>',
    '<span style="padding-left: 10px;"><b>{publisherName}</b>',
    '<tpl if="date">',
    	' le {date}',
    '</tpl>',
    //'<tpl if="gps">',
    //	' à {gps}',
    //'</tpl>',
    '</span>'
    ],
    grouped: true,
    indexBar: true,
    plugins: 'pullrefresh',
    form: Ext.create('Ext.form.Panel', {}),
    store: Ext.create('Ext.data.Store', {
      fields: ['publisherID', 'publisherName', 'publisherProfilePicture', 'predicate', 
               'publisherReputation', 'data', 'end', 'begin', // results got from find
               'date', //only one predicate that is extracted from predicate to give more details in the resultview
               'text'], //details got from getDetail, as a second step
      sorters: ['publisherName', 'predicate'],
      grouper: {
          groupFn: function(record) {
              return record.get('publisherName')[0];
          }
      }
    })
  }
});