

Ext.Loader.setPath({
  'myTemplate': 'system/templates/application/myTemplate_Sencha'
});

Ext.application({
  name: 'myTemplate',
  icon: 'resources/images/icon.png',
  tabletStartupScreen: 'resources/images/tablet_startup.png',
  phoneStartupScreen: 'resources/images/phone_startup.png',
  glossOnIcon: false,

  views: ['publish', 'find', 'subscribe'],
  controllers: ['handler'],

  launch: function() {
  	

		Ext.create('Ext.NavigationView', {
	    fullscreen: true,
	    id: 'navView',
	    autoDestroy: false,
	    useTitleForBackButtonText: true,
	    //defaultBackButtonText: 'retour',
	    items: [{
        title: Ext.getBody().down('#applicationName').getValue(),
        layout : 'fit',
        items: [
						{
						  xtype: 'carousel',
						  cls: 'test',
							defaults : {
								scrollable: {
								    direction: 'vertical',
								    directionLock: true
								},
							},
							items : [
							   Ext.create('myTemplate.view.publish'),
							   Ext.create('myTemplate.view.find'),
							   Ext.create('myTemplate.view.subscribe')
							]
						}
          ]
	    	}
	    ],
	    navigationBar: {
	    	defaults:{
	    		xtype: 'button',
          ui: 'forward',
          style: 'position: absolute;right: 0;top: .4em;',
          iconMask: true,
          align: 'right',
          showAnimation: 'fade'
	    	},
        items: [
          {
            id: 'homeButton',
            iconCls: 'home'
          },
          {
            id: 'mapButton',
            iconCls: 'locate',
            hidden: true
          }
        ]
      }
		});

  	
  	
  	
  }
});
