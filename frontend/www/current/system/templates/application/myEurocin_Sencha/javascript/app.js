function initialize(){}

Ext.define('myCheckbox', {
  extend : 'Ext.field.Checkbox',
  xtype : 'myCheckbox',
  config : {
  	listeners : {
      element : 'label',
      tap     : function() {
        var checked = this.getChecked();
        this.setChecked(!checked);
      }
	  }
  }
});

defaultPhonePickerConfig:{
  hideOnMaskTap: true
}

Ext.application({

	name : 'myApp',
	icon : 'icon.png',
	tabletStartupScreen : 'tablet_startup.png',
	phoneStartupScreen : 'phone_startup.png',
	glossOnIcon : false,

	launch : function() {
    
		var fieldApp = Ext.create('Ext.field.Text', {
		  hidden: true,
		  name: 'application',
		  value: Ext.getBody().down('#applicationName').getValue()
		}),
		fieldApp2 = Ext.create('Ext.field.Text', {
		  hidden: true,
		  name: 'application',
		  value: Ext.getBody().down('#applicationName').getValue()
		}),
		fieldKeyword = Ext.create('Ext.field.Text', {
			name : 'title',
			label : 'Titolo',
			autoCapitalize : false
		}),
		_fieldKeyword = Ext.create('Ext.field.Text', {//_fields* are hidden fields to give ontologyID
			name : '_title',
			hidden: true,
			value: 0
		}),
		fieldenumL = Ext.create('Ext.field.Select', {
			name: 'enumL',
			label: 'Lingua',
			defaultPhonePickerConfig:{
			   hideOnMaskTap: true
			},
	    options: [
	        {text: '', value: ''},
	        {text: 'italiano', value: 1},
	        {text: 'francese', value: 2},
	        {text: 'inglese', value: 3}
	    ]
		}),
		_fieldenumL = Ext.create('Ext.field.Text', {name : '_enumL', hidden: true, value: 2}),
		fieldenumC = Ext.create('Ext.field.Select', {
			name: 'enumC',
			label: 'Città',
			defaultPhonePickerConfig:{
			   hideOnMaskTap: true
			},
	    options: [
	        {text: '', value: ''},
	        {text: 'Alessandria', value: 'Alessandria'},
	        {text: 'Asti', value: 'Asti'},
	        {text: 'Cuneo', value: 'Cuneo'}
	    ]
		}),
		_fieldenumC = Ext.create('Ext.field.Text', {name : '_enumC', hidden: true, value: 2}),
		
		fieldcheckA = Ext.create('myCheckbox', { name: 'a', label: 'Arte/Cultura'}),
		fieldcheckN = Ext.create('myCheckbox', { name: 'n', label: 'Natura'}),
		fieldcheckT = Ext.create('myCheckbox', { name: 't', label: 'Tradizioni'}),
		fieldcheckG = Ext.create('myCheckbox', { name: 'g', label: 'Enogastronomia'}),
		fieldcheckB = Ext.create('myCheckbox', { name: 'b', label: 'Benessere'}),
		fieldcheckS = Ext.create('myCheckbox', { name: 's', label: 'Storia'}),
		fieldcheckR = Ext.create('myCheckbox', { name: 'r', label: 'Religione'}),
		fieldcheckE = Ext.create('myCheckbox', { name: 'e', label: 'Escursioni/Sport'}),
		_fieldcheckA = Ext.create('Ext.field.Text', {name : '_a',	hidden: true, value: 2}),
		_fieldcheckN = Ext.create('Ext.field.Text', {name : '_n',	hidden: true, value: 2})
		_fieldcheckT = Ext.create('Ext.field.Text', {name : '_t',	hidden: true, value: 2}),
		_fieldcheckG = Ext.create('Ext.field.Text', {name : '_g',	hidden: true, value: 2}),
		_fieldcheckB = Ext.create('Ext.field.Text', {name : '_b',	hidden: true, value: 2}),
		_fieldcheckS = Ext.create('Ext.field.Text', {name : '_s',	hidden: true, value: 2}),
		_fieldcheckR = Ext.create('Ext.field.Text', {name : '_r',	hidden: true, value: 2}),
		_fieldcheckE = Ext.create('Ext.field.Text', {name : '_e',	hidden: true, value: 2}),
		
		fieldenumL2 = Ext.create('Ext.field.Select', {
			name: 'enumL',
			label: 'Lingua',
			defaultPhonePickerConfig:{
			   hideOnMaskTap: true
			},
	    options: [
	        {text: '', value: ''},
	        {text: 'italiano', value: 1},
	        {text: 'francese', value: 2},
	        {text: 'inglese', value: 3}
	    ]
		}),
		fieldenumC2 = Ext.create('Ext.field.Select', {
			name: 'enumC',
			label: 'Città',
			defaultPhonePickerConfig:{
			   hideOnMaskTap: true
			},
	    options: [
	        {text: '', value: ''},
	        {text: 'Alessandria', value: 'Alessandria'},
	        {text: 'Asti', value: 'Asti'},
	        {text: 'Cuneo', value: 'Cuneo'}
	    ]
		}),
		fieldcheckA2 = Ext.create('myCheckbox', { name: 'a', label: 'Arte/Cultura'}),
		fieldcheckN2 = Ext.create('myCheckbox', { name: 'n', label: 'Natura'}),
		fieldcheckT2 = Ext.create('myCheckbox', { name: 't', label: 'Tradizioni'}),
		fieldcheckG2 = Ext.create('myCheckbox', { name: 'g', label: 'Enogastronomia'}),
		fieldcheckB2 = Ext.create('myCheckbox', { name: 'b', label: 'Benessere'}),
		fieldcheckS2 = Ext.create('myCheckbox', { name: 'c', label: 'Storia'}),
		fieldcheckR2 = Ext.create('myCheckbox', { name: 'r', label: 'Religione'}),
		fieldcheckE2 = Ext.create('myCheckbox', { name: 'e', label: 'Escursioni/Sport'}),
		
		fieldText = Ext.create('Ext.field.TextArea', {
			name : 'text',
			label : 'Testo'
		}),
		_fieldText = Ext.create('Ext.field.Text', {
			name : '_text',
			hidden: true,
			value: 4
		});
		

		var store = Ext.create('Ext.data.Store', {
	      fields: ['publisherID', 'publisherName', 'publisherProfilePicture', 'predicate', 
	               'publisherReputation', 'data', 'end', 'begin', // results got from find
	               'title','city','a','n','t','g','b','s','r','e', // extracted from predicate string
	               'text'], //details got from getDetail
	      sorters: ['publisherName', 'predicate'],
	      grouper: {
	          groupFn: function(record) {
	              return record.get('city')[0];
	          }
	      }
	  });
		
		var formlistdetail = Ext.create('Ext.form.Panel', {});
		
		var mylist = Ext.create('Ext.List', {
			  title: 'Risultato',
	      id: 'mylist',
	      itemTpl: ['<img src="',
        '<tpl if="publisherProfilePicture">',
        	'{publisherProfilePicture}',  
        '<tpl else>',
       		'http://graph.facebook.com//picture?type=large',
        '</tpl>',
        '" width="60" height="60" style="vertical-align: middle;">',
	      '<span style="margin-left: 10px;"> <strong>{title}, {city}, in ',
	      '<tpl if="language===1">',
	      	'IT',
	      '<tpl elseif="language===2">',
	      	'FR',
	      '</tpl>',
	      '</strong></span>'
        ],
	      grouped: true,
	      indexBar: true,
	      plugins: 'pullrefresh',
	      listeners:{
	      	itemtap: function(elt, index, item, record, e){
	      		e.stopEvent();
	          var storeindex = index;
	          console.log(index+" "+store.getAt(index).get('predicate'));
	          if (mylist.getStore().getAt(storeindex).get('text')){ //data already in store
	          	detailPanel.setData(store.getAt(storeindex).getData());
	          	view.push(detailPanel);
	          	return;
	          }
	          formlistdetail.submit({
	  			    url: 'lib/dasp/request/getDetail.php',
	  			    params: {
                'user': store.getAt(index).get('publisherID'),
                'predicate': store.getAt(index).get('predicate'),
                'application': Ext.getBody().down('#applicationName').getValue()
	            },
	  	        success: function(elt, response) {
	  						console.log(response);
	  						console.log(storeindex+' '+elt.id);
	  						var j= eval(response.data.details);
	  						for (var i=0; i<j.length; i++){
	  							if (j[i].key=='text'){
	  								mylist.getStore().getAt(storeindex).set('text', j[i].value.replace(/\n/g,'<br>'));
	  							}else{
	  								mylist.getStore().getAt(storeindex).set(j[i].key, j[i].value);
	  							}
	  						}
	  						detailPanel.setData(store.getAt(storeindex).getData());
	  						view.push(detailPanel);
	  	        },
	  	        failure: function() {
	  	        	console.log('failed');
	  	        	Ext.Msg.alert("<strong>Nessun dato trovato</strong>");
	  	        }
	  				});
	      	}
	      },
	
	      store: store
	  });
		
		var buttonFind = Ext.create('Ext.Button', {
			text : 'Ricerca',
			ui : 'confirm',
			handler: function() {
				var form = this.up('formpanel');
				form.submit({
			    url: 'lib/dasp/request/find.php',
	        success: function(elt, response) {
						var j= eval(response.data.results);
						mylist.getStore().setData([]);
						for (var i=0; i<j.length; i++){
							j[i]['title'] = j[i].predicate.match(/title\(([^)]+)\)/)[1];
							j[i]['city'] = j[i].predicate.match(/enumC\(([^)]+)\)/)[1];
							j[i]['language'] = j[i].predicate.match(/enumL\(([^)]+)\)/)[1];
							mylist.getStore().add(j[i]);
						}
						view.push(mylist);
	        },
	        failure: function() {
	        	console.log('failed');
	        	Ext.Msg.alert("<strong>Nessun dato trovato</strong>");
	        }
				});
			}
		}),
		buttonPublish = Ext.create('Ext.Button', {
			text : 'Pubblicare',
			ui : 'confirm',
			handler: function() {	
				var form = this.up('formpanel');
				form.submit({
			    url: 'lib/dasp/request/publish.php',
	        success: function(elt, response) {
            console.log(response);         
            Ext.Msg.alert("<strong>Testo pubblicato</strong>");
	        },
	        failure: function() {
	        	Ext.Msg.alert("<strong>Il vostro testo non pu&oacute; essere pubblicato</strong>");
	        }
				});

			}
		});
		
		
		var detailPanel = Ext.create('Ext.Panel', {
			  title: 'Dettagli',
	      layout: 'fit',
	      scrollable: true,
        styleHtmlContent: true,
	      tpl: ['<div>',
	        '<p><strong>Nome dell publicatore</strong>: {publisherName} <img src="',
	        '<tpl if="publisherProfilePicture">',
	        	'{publisherProfilePicture}',
	        '<tpl else>',
	       		'http://graph.facebook.com//picture?type=large',
	        '</tpl>',
	        '" width="180" style="float:right;">',
	        '</p>',
	        //'<p><strong>Predicato</strong>: {predicate}</p>',
	        '<p><strong>Città</strong>: {city}</p>',
	        '<p><strong>Titolo</strong>: {title}</p>',
	        '<p><tpl if="a"> Arte/Cultura</tpl>',
	        '<tpl if="n"> Natura</tpl>',
	        '<tpl if="t"> Tradizioni</tpl>',
	        '<tpl if="g"> Enogastronomia</tpl>',
	        '<tpl if="b"> Benessere</tpl>',
	        '<tpl if="s"> Storia</tpl>',
	        '<tpl if="e"> Religione</tpl>',
	        '<tpl if="e"> Escursioni/Sport</tpl></p>',
	        '<p style="text-align: justify;"><strong>Testo</strong>:<br>{text}</p>',
	        '</div>'
	      ]
	  });

		var carousel = Ext.create('Ext.Carousel', {
			cls: 'test',
			defaults : {
				scrollable: {
				    direction: 'vertical',
				    directionLock: true
				},
			},
			items : [
			{
				xtype : 'formpanel',
				title : 'Chercher',
				iconCls : 'search',
				style : "text-align: center;",
				layout : 'vbox',

				items : [
				{ 
					xtype : 'fieldset',
					title: Ext.os.deviceType != 'Phone'?'Cercando un testo:':null,
					items : [
						fieldApp2,
						fieldenumC2,
						fieldenumL2,
						fieldcheckA2,
						fieldcheckN2,
						fieldcheckT2,
						fieldcheckG2,
						fieldcheckB2,
						fieldcheckS2,
						fieldcheckR2,
						fieldcheckE2
					]
				},
				buttonFind,
				{
					xtype : 'fieldset',
					instructions : 'Almeno un campo deve essere riempito'+(Ext.os.deviceType == 'Phone'?'\nper la ricerca di un testo':'')
				}
				]
			},
			{
				xtype : 'formpanel',
				title : 'Publier',
				iconCls : 'action',
				style : "text-align: center;",
				layout : 'vbox',

				items : [
				{
					xtype : 'fieldset',
					title : Ext.os.deviceType != 'Phone'?'Pubblicare un testo:':null,
					items : [
						fieldApp,
						fieldKeyword,
						fieldenumC,
						fieldenumL,
						fieldcheckA,
					  fieldcheckN,
					  fieldcheckT,
					  fieldcheckG,
					  fieldcheckB,
					  fieldcheckS,					  
					  fieldcheckR,
					  fieldcheckE,
					  _fieldKeyword,
					  _fieldenumC,
					  _fieldenumL,
					  _fieldcheckA,
					  _fieldcheckN,
					  _fieldcheckT,
					  _fieldcheckG,
					  _fieldcheckB,
					  _fieldcheckS,
					  _fieldcheckR,
					  _fieldcheckE
					  ]				
				},
				{
					xtype : 'fieldset',
					items : [
					  fieldText,
					  _fieldText
				  ]				
				
				},
				buttonPublish,
				{
					xtype : 'fieldset',
					instructions : 'Almeno un campo deve essere riempito'+(Ext.os.deviceType == 'Phone'?'<br>per la pubblicazione di un testo':'')
				}
				]
			}
			]
		});
		
		var view = Ext.create('Ext.NavigationView', {
	    fullscreen: true,
	    autoDestroy: false,
	    //useTitleForBackButtonText: true,
	    defaultBackButtonText: 'indietro',
	    items: [{
        title: Ext.getBody().down('#applicationName').getValue(),
        layout : 'fit',
        items: [
          carousel
          ]
	    	}
	    ]
		});
		
	}


});
