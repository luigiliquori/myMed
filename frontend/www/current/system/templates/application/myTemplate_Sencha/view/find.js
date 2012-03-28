Ext.define('myTemplate.view.find', {
  extend: 'Ext.form.Panel',
  
  config:{
  	title : 'Chercher',
		iconCls : 'search',
		style : "text-align: center;",
		layout : 'vbox',

		items : [
		{ 
			xtype : 'fieldset',
			title: Ext.os.deviceType != 'Phone'?'Je cherche une donnée par:':null,
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
			  }
			]
		},
		{
			xtype: 'button',
			id: 'findButton',
			text : 'Chercher',
			ui : 'confirm'
		},
		{
			xtype : 'fieldset',
			instructions : 'Au moins un champ doit être rempli'+(Ext.os.deviceType == 'Phone'?'\npour la recherche d\'une donnée':'')
		}
		]
  }
});