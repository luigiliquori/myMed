Ext.define('myTemplate.controller.handler', {
  extend: 'Ext.app.Controller',
  config: {
    refs: {  
    	navView: 'navigationview',
    	button1 : '#button1',
    	button2 : '#button2',
    	panel1: '#panel1',
    	panel2: '#panel2',
    	panel3: '#panel3'
    },
    control: {
	    button1: {
        tap: 'on1'
      },
      button2: {
        tap: 'on2'
      }
    },
    routes: {
	     'view1': 'showView1',
	     'view2': 'showView2',
	     'view3': 'showView3',
	  }
  },
  on1: function() {
    var view  = this.getNavView();
    view.push(this.getPanel2());
	},
	on2: function() {
    var view  = this.getNavView();
    view.push(this.getPanel3());
	},
	showView1: function() {
	},
});