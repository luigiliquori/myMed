Ext.define('myTemplate.controller.handler', {
  extend: 'Ext.app.Controller',

  config: {
    refs: {
  		navView: 'navigationview',
  		homeButton:'#homeButton',
  		mapButton:'#mapButton',
      findButton: '#findButton',
      publishButton: '#publishButton',
      subscribeButton: '#subscribeButton',
      resultList: 'list',
      detailPanel: '#details'
    },

    control: {
    	navView: {
        push: 'onNavPush',
        beforepop: 'onNavBeforePop',
        pop: 'onNavPop'
	    },
	    homeButton: {
        tap: 'onHome'
      },
      mapButton: {
        tap: 'onMap'
      },
    	findButton: {
        tap: 'onFind'
      },
      publishButton: {
        tap: 'onPublish'
	    }, 
	    subscribeButton: {
	    	tap: 'onSubscribe'
	    },
	    resultList: {
	    	itemtap: 'onGetDetail'
	    },
    },
    
    routes: {
			'': 'showView2'
		},
		
		stack: []
    
  },
  
  onNavPush: function(view, item) {
  	var mapButton = this.getMapButton();
  	var homeButton = this.getHomeButton();
  	if (!homeButton.isHidden())
  		homeButton.hide();
  	if (item.id == "details"){
  		mapButton.show();
  	}
  },
  
  onNavBeforePop: function() {
    var history = this.getApplication().getHistory(),
      urlId = (record && record.get('urlId')) ? record.get('urlId') : '',
      stack = this.getStack();


    history.add(new Ext.app.Action({
        url: urlId
    }), true);

    stack.pop();
    this.setStack(stack);

	},

  
  onNavPop: function(view, item) {
  	var mapButton = this.getMapButton();
  	var homeButton = this.getHomeButton();
  	if (!mapButton.isHidden())
  		mapButton.hide();
  	if (item.id == "resultList") {
  		homeButton.show();
  	}else if(item.id == "details"){
  		mapButton.hide();
  	}
  },
  
  showView2: function() {
    /*var stack = this.getStack();

    if (stack.length) {
    	this.getNavView().pop();
      return;
    }

    this.setStack([]);*/
  	
  	this.getNavView().setActiveItem(this.getResultList());

	},
  
  onHome: function(){
  	window.location.href="?application=0";
  },
  
  onMap: function(){
  	var view  = this.getNavView();
  	view.push({
      xtype: 'map',
      useCurrentLocation: Ext.create('Ext.util.Geolocation', {
        //autoUpdate: false,
        listeners: {
          locationupdate: function(geo) {
            alert('New latitude: ' + geo.getLatitude());
          },
          locationerror: function(geo, bTimeout, bPermissionDenied, bLocationUnavailable, message) {
            if(bTimeout){
              alert('Timeout occurred.');
            } else {
              alert('Error occurred.');
            }
          }
        }
      })
	  });
  },
  
  onFind: function() {
    var form = this.getFindButton().up('formpanel'), list;
    if (! (list = this.resultList)) {
	    list = this.resultList = Ext.create('myTemplate.view.results');
	  }
    var view  = this.getNavView();
		form.submit({
	    url: 'lib/dasp/request/find.php',
      success: function(elt, response) {
				var j= eval(response.data.results);
				list.getStore().setData([]);
				for (var i=0; i<j.length; i++){
					if (j[i].predicate.indexOf('date')>0){
						j[i]['date'] = j[i].predicate.match(/date\(([^)]+)\)/)[1];
					}
					list.getStore().add(j[i]);
				}
				view.push(list);
      },
      failure: function(elt, response) {
      	console.log('failed');
      	Ext.Msg.alert("Aucun résultat trouvé", response.description);
      }
		});
	},
	
	onPublish: function() {
		var form = this.getPublishButton().up('formpanel');
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
	},
	
	onSubscribe: function() {
    var form = this.getSubscribeButton().up('formpanel');
		form.submit({
	    url: 'lib/dasp/request/subscribe.php',
      success: function(elt, response) {
      	console.log(response);
      	Ext.Msg.alert("Souscription faite");
      },
      failure: function(elt, response) {
      	console.log('failed');
      	Ext.Msg.alert("Souscription non soumise", response.description);
      }
		});
	},
	
	onGetDetail: function(elt, index, item, record, e) {
    e.stopEvent();
    var id = index, detailPanel;
    if (! (detailPanel =this.detailPanel)) {
    	detailPanel = this.detailPanel = Ext.create('myTemplate.view.details');
	  }
    var list = this.getResultList();
    var view  = this.getNavView();
    console.log(id+" "+list.getStore().getAt(id).get('predicate'));
    if (list.getStore().getAt(id).get('text')){ //data already in store
    	detailPanel.setData(list.getStore().getAt(id).getData());
    	view.push(detailPanel);
    	return;
    }
    list.config.form.submit({
	    url: 'lib/dasp/request/getDetail.php',
	    params: {
        'user': list.getStore().getAt(id).get('publisherID'),
        'predicate': list.getStore().getAt(id).get('predicate'),
        'application': Ext.getBody().down('#applicationName').getValue()
      },
      success: function(elt, response) {
				console.log(response);
				console.log(id+' '+elt.id);
				var j= eval(response.data.details);
				for (var i=0; i<j.length; i++){
					if (j[i].key=='text'){
						list.getStore().getAt(id).set('text', j[i].value);
						break;
					}
				}
				detailPanel.setData(list.getStore().getAt(id).getData());
				view.push(detailPanel);
      },
      failure: function() {
      	console.log('failed');
      	Ext.Msg.alert("Aucun détail trouvé");
      }
		});
	}

});