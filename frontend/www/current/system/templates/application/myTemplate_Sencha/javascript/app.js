function initialize(){}

Ext.application({

	name : 'myApp',
	icon : 'icon.png',
	tabletStartupScreen : 'tablet_startup.png',
	phoneStartupScreen : 'phone_startup.png',
	glossOnIcon : false,

	launch : function() {
    
		var fieldKeyword = Ext.create('Ext.field.Text', {
			name : 'keyword',
			label : 'Mot clé',
			autoCapitalize : false
		}),
		_fieldKeyword = Ext.create('Ext.field.Text', {//_field* are fields to give ontologyID
			name : '_keyword',
			hidden: true,
			value: 0
		}),
		fieldGps = Ext.create('Ext.field.Text', {
			name : 'gps',
			label : 'Adresse',
			autoCapitalize : true
		}),
		_fieldGps = Ext.create('Ext.field.Text', {
			name : '_gps',
			hidden: true,
			value: 1
		}),
		fieldEnum = Ext.create('Ext.field.Select', {
			name: 'enum',
			label: 'Catégorie',
	    options: [
	        {text: '', value: ''},
	        {text: 'Anglais', value: 1},
	        {text: 'Francais', value: 2},
	        {text: 'Italien', value: 3},
	    ]
		}),
		_fieldEnum = Ext.create('Ext.field.Text', {
			name : '_enum',
			hidden: true,
			value: 2
		}),
		fieldDate = Ext.create('Ext.field.DatePicker', {
			name : 'date',
			label : 'Date',
			dateFormat: 'd/m/Y',
			picker : {
				value: new Date(),
				yearFrom : 1980,
				cancelButton: false,
				//hideOnMaskTap: 'true',
				toolbar : {
          items : [
            {
              text    : 'Clear',
              ui      : 'plain',
              handler : function(btn) {
                var picker = btn.up('datepicker');
                picker.fireEvent('change', picker, null);
                picker.hide();
              }
            }
          ]
				}
			}
		}),
		_fieldDate = Ext.create('Ext.field.Text', {
			name : '_date',
			hidden: true,
			value: 3
		}),
		fieldKeyword2 = Ext.create('Ext.field.Text', {
			name : 'keyword',
			label : 'Mot clé',
			autoCapitalize : false
		}),
		fieldGps2 = Ext.create('Ext.field.Text', {
			name : 'gps',
			label : 'Adresse',
			autoCapitalize : true
		}),
		fieldEnum2 = Ext.create('Ext.field.Select', {
			name: 'enum',
			label: 'Catégorie',
      options: [
          {text: '', value: ''},
          {text: 'Anglais', value: 1},
          {text: 'Francais', value: 2},
          {text: 'Italien', value: 3}
      ]
		}),
		fieldDate2 = Ext.create('Ext.field.DatePicker', {
			name : 'date',
			label : 'Date',
			dateFormat: 'd/m/Y',
			picker : {
				yearFrom : 1980,
				cancelButton: false,
				//hideOnMaskTap: 'true',
				toolbar : {
          items : [
            {
              text    : 'Clear',
              ui      : 'plain',
              handler : function(btn) {
                var picker = btn.up('datepicker');
                picker.fireEvent('change', picker, null);
                picker.hide();
              }
            }
          ]
				}
			}
		}),
		fieldKeyword3 = Ext.create('Ext.field.Text', {
			name : 'keyword',
			label : 'Mot clé',
			autoCapitalize : false
		}),
		fieldGps3 = Ext.create('Ext.field.Text', {
			name : 'gps',
			label : 'Adresse',
			autoCapitalize : true
		}),
		fieldEnum3 = Ext.create('Ext.field.Select', {
			name: 'enum',
			label: 'Catégorie',
      options: [
          {text: '', value: ''},
          {text: 'Anglais', value: 1},
          {text: 'Francais', value: 2},
          {text: 'Italien', value: 3}
      ]
		}),
		fieldDate3 = Ext.create('Ext.field.DatePicker', {
			name : 'date',
			label : 'Date',
			dateFormat: 'd/m/Y',
			picker : {
				yearFrom : 1980,
				cancelButton: false,
				hideOnMaskTap: 'true',
				toolbar : {
          items : [
            {
              text    : 'Clear',
              ui      : 'plain',
              handler : function(btn) {
                var picker = btn.up('datepicker');
                picker.fireEvent('change', picker, null);
                picker.hide();
              }
            }
          ]
				}
			}
		}),
		fieldText = Ext.create('Ext.field.TextArea', {
			name : 'text',
			label : 'Texte'
		}),
		_fieldText = Ext.create('Ext.field.Text', {
			name : '_text',
			hidden: true,
			value: 4
		});
		

		var store = Ext.create('Ext.data.Store', {
	      fields: ['publisherID', 'publisherName', 'publisherProfilePicture', 'predicate', 
	               'publisherReputation', 'data', 'end', 'begin', // results got from find
	               'date',//,'keyword2','keyword3','enum1', 'date', //not necessary as already in predicate string
	               'text'], //details got from getDetail
	      sorters: ['publisherName', 'predicate'],
	      grouper: {
	          groupFn: function(record) {
	              return record.get('publisherName')[0];
	          }
	      }
	  });
		
		var formlistdetail = Ext.create('Ext.form.Panel', { });
		
		var mylist = Ext.create('Ext.List', {
			  title: 'Résultats',
	      id: 'mylist',
	      itemTpl: ['<img src="',
        '<tpl if="publisherProfilePicture">',
        	'{publisherProfilePicture}',  
        '<tpl else>',
       		'http://graph.facebook.com//picture?type=large',
        '</tpl>',
        '" width="60" height="60" style="vertical-align: middle;">',
	      '<span style="margin-left: 10px;"><b>{publisherName}</b>',
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
	      form: Ext.create('Ext.form.Panel', {}),
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
	          this.config.form.submit({
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
	  								mylist.getStore().getAt(storeindex).set('text', j[i].value);
	  								break;
	  							}
	  						}
	  						detailPanel.setData(store.getAt(storeindex).getData());
	  						view.push(detailPanel);
	  	        },
	  	        failure: function() {
	  	        	console.log('failed');
	  	        	Ext.Msg.alert("<strong>Aucun détail trouvé</strong>");
	  	        }
	  				});
	      	}
	      },
	
	      store: store
	  });
		
		var buttonPublish = Ext.create('Ext.Button', {
			text : 'Publier',
			ui : 'confirm',
			handler: function() {	

				if (fieldDate.getValue()) console.log("date set");
				var form = this.up('formpanel');
				form.submit({
			    url: 'lib/dasp/request/publish.php',
	        success: function(elt, response) {
            console.log(response);
            Ext.Msg.alert("Donnée publiée");
	        },
	        failure: function(elt, response) {
	        	console.log(response);
	        	Ext.Msg.alert("Donnée non publiée", response.description);
	        }
				});

			}
		}),
		
		buttonFind = Ext.create('Ext.Button', {
			text : 'Chercher',
			ui : 'confirm',
			handler: function() {
				var form = this.up('formpanel');
				form.submit({
			    url: 'lib/dasp/request/find.php',
	        success: function(elt, response) {
						var j= eval(response.data.results);
						mylist.getStore().setData([]);
						for (var i=0; i<j.length; i++){						
							if (j[i].predicate.indexOf('date')>0){
								j[i]['date'] = j[i].predicate.match(/date\(([^)]+)\)/)[1];
							}
							mylist.getStore().add(j[i]);
						}
						view.push(mylist);
	        },
	        failure: function(elt, response) {
	        	console.log('failed');
	        	Ext.Msg.alert("Aucun résultat trouvé", response.description);
	        }
				});	
			}
		}),
		
		buttonSubscribe = Ext.create('Ext.Button', {
			text : 'Souscrire',
			ui : 'confirm',
			handler: function() {
				var form = this.up('formpanel');
				form.submit({
			    url: 'lib/dasp/request/subscribe.php',
	        success: function(elt, response) {
	        	console.log(response);
	        	Ext.Msg.alert("<strong>Souscription faite</strong>");
	        },
	        failure: function(elt, response) {
	        	console.log('failed');
	        	Ext.Msg.alert("Souscription non soumise", response.description);
	        }
				});	
			}
		});
		
		
		var detailPanel = Ext.create('Ext.Panel', {
			  title: 'Détails',
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
	        '<b>Nom</b>: <span style="left-margin:5px; color:DarkBlue; font-size:120%;">{publisherName}</span>',
	        '<br>',
	        '<b>Prédicat</b>: {predicate}',
	        '<br>',
	        '<b>Texte</b>:',
	        '<div id="detailstext">{text}</div>',
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
				title : 'Publier',
				iconCls : 'action',
				style : "text-align: center;",
				layout : 'vbox',

				items : [
				{
					xtype : 'fieldset',
					title : Ext.os.deviceType != 'Phone'?'Je publie une donnée:':null,
					items : [
						{
						  xtype: 'textfield',
						  hidden: true,
						  name: 'application',
						  value: Ext.getBody().down('#applicationName').getValue()
						},
						fieldKeyword,
						_fieldKeyword,
					  fieldGps,
					  _fieldGps,
					  fieldEnum,
					  _fieldEnum,
					  fieldDate,
					  _fieldDate,
					  fieldText,
					  _fieldText
				  ]
				},
				buttonPublish,
				{
					xtype : 'fieldset',
					instructions : 'Au moins un champ doit être rempli'+(Ext.os.deviceType == 'Phone'?'<br>pour la publication d\'une donnée':'')
				}
				]
			},
			{
				xtype : 'formpanel',
				title : 'Chercher',
				iconCls : 'search',
				style : "text-align: center;",
				layout : 'vbox',

				items : [
				{ 
					xtype : 'fieldset',
					title: Ext.os.deviceType != 'Phone'?'Je cherche une donnée par:':null,
					items : [
					  {
              xtype: 'textfield',
              hidden: true,
              name: 'application',
              value: Ext.getBody().down('#applicationName').getValue()
					  },
					  fieldKeyword2,
					  fieldGps2,
					  fieldEnum2,
					  fieldDate2
					]
				},
				buttonFind,
				{
					xtype : 'fieldset',
					instructions : 'Au moins un champ doit être rempli'+(Ext.os.deviceType == 'Phone'?'\npour la recherche d\'une donnée':'')
				}
				]
			},{
				xtype : 'formpanel',
				title : 'Souscrire',
				iconCls : 'time',
				style : "text-align: center;",
				layout : 'vbox',

				items : [
				{
					xtype : 'fieldset',
					title: Ext.os.deviceType != 'Phone'?'Je souscris à une donnée par:':null,
					items : [
					  {
              xtype: 'textfield',
              hidden: true,
              name: 'application',
              value: Ext.getBody().down('#applicationName').getValue()
					  },
					  fieldKeyword3,
					  fieldGps3,
					  fieldEnum3,
					  fieldDate3
					]
				},
				buttonSubscribe,
				{
					xtype : 'fieldset',
					instructions : 'Au moins un champ doit être rempli'+(Ext.os.deviceType == 'Phone'?'\npour la souscription à une donnée':'')
				}
				]
			}]
		});
		
		var view = Ext.create('Ext.NavigationView', {
	    fullscreen: true,
	    autoDestroy: false,
	    useTitleForBackButtonText: true,
	    //defaultBackButtonText: 'retour',
	    navigationBar: {
        items: [
          {
            xtype: 'button',
            ui: 'forward',
            style: 'position: absolute;right: 0;top: .4em;',
            html: 'Quitter',
            align: 'right',
            handler: function(){
            	window.location="?application=0";
            }
          }
        ]
      },
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
