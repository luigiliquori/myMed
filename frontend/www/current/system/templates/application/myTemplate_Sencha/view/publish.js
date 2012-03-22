Ext.define('myTemplate.view.publish', {
  extend: 'Ext.form.Panel',
  
  config:{
  	title : 'Chercher',
		iconCls : 'search',
		style : "text-align: center;",
		layout : 'vbox',

		items : [
		{
			xtype : 'fieldset',
			title : Ext.os.deviceType != 'Phone'?'Je publie une donnée:':null,
			defaults:{
				xtype: 'textfield'
			},
			items : [
			  {
          hidden: true,
          name: 'application',
          value: Ext.getBody().down('#applicationName').getValue()
			  },
			  {
			  	name : 'keyword',
			  	label : 'Mot clé',
			  	autoCapitalize : false
			  },
			  {
			  	name : 'gps',
			  	label : 'Adresse',
			  	autoCapitalize : true
			  },
			  {
			  	xtype:'selectfield',
			  	name: 'enum',
			  	label: 'Catégorie',
			  	defaultPhonePickerConfig:{
					   hideOnMaskTap: true
					},
			    options: [
			        {text: '', value: ''},
			        {text: 'Anglais', value: 1},
			        {text: 'Francais', value: 2},
			        {text: 'Italien', value: 3}
			    ]
			  },
			  {
			  	xtype:'datepickerfield',
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
			  },
			  {
			  	xtype:'textareafield',
			  	name : 'text',
			  	label : 'Texte'
			  },// _field are hidden fields to give ontologyID to server
			  {
          name : '_keyword',
        	hidden: true,
        	value: 0
			  },
			  {
          name : '_gps',
        	hidden: true,
        	value: 1
			  },
			  {
          name : '_enum',
        	hidden: true,
        	value: 2
			  },
			  {
          name : '_date',
        	hidden: true,
        	value: 3
			  },
			  {
			  	name : '_text',
			  	hidden: true,
			  	value: 4
			  }
			]
		},
		{
			xtype: 'button',
			id: 'publishButton',
			text : 'Publier',
			ui : 'confirm'
		},
		{
			xtype : 'fieldset',
			instructions : 'Au moins un champ doit être rempli'+(Ext.os.deviceType == 'Phone'?'<br>pour la publication d\'une donnée':'')
		}
		]
  }
});