Ext.define('Core.lib.CoreApplication', {
  extend:'Ext.app.Controller',

  requires:[
    'Ext.ModelManager',
    'Ext.data.Model',
    'Ext.data.StoreManager',
    'Ext.tip.QuickTipManager',
    'Ext.ComponentManager',
    'Ext.app.EventBus'
  ],

  /**
   * @cfg {String} name The name of your application. This will also be the namespace for your views, controllers
   * models and stores. Don't use spaces or special characters in the name.
   */

  /**
   * @cfg {Object} scope The scope to execute the {@link #launch} function in. Defaults to the Application
   * instance.
   */
  scope:undefined,

  /**
   * @cfg {Boolean} enableQuickTips True to automatically set up Ext.tip.QuickTip support.
   */
  enableQuickTips:true,

  /**
   * @cfg {String} defaultUrl When the app is first loaded, this url will be redirected to.
   */

  /**
   * @cfg {String} appFolder The path to the directory which contains all application's classes.
   * This path will be registered via {@link Ext.Loader#setPath} for the namespace specified in the {@link #name name} config.
   */
  appFolder:'app',

  /**
   * @cfg {Boolean} autoCreateViewport True to automatically load and instantiate AppName.view.Viewport
   * before firing the launch function.
   */
  autoCreateViewport:false,

  TaskBar: null,

  /**
   * Creates new Application.
   * @param {Object} [config] Config object.
   */
  constructor:function (config){
    config = config || {};
    Ext.apply(this, config);

    Ext.CoreApplication = this;

    var requires = config.requires || [];

    Ext.Loader.setPath(this.name, this.appFolder);

    if(this.paths) {
      Ext.Object.each(this.paths, function (key, value){
        Ext.Loader.setPath(key, value);
      });
    }

    this.callParent(arguments);

    this.eventbus = Ext.create('Ext.app.EventBus');

    var controllers = Ext.Array.from(this.controllers), ln = controllers && controllers.length, i, controller, bundle;

    this.controllers = Ext.create('Ext.util.MixedCollection');

    if(this.autoCreateViewport) {
      requires.push(this.getModuleClassName('Viewport', 'view'));
    }

    for(i = 0; i < ln; i++) {
      requires.push(this.getModuleClassName(controllers[i], 'controller'));
    }

    var applications = Ext.Array.from(this.applications);
    this.applications = Ext.create('Ext.util.MixedCollection');
    for(i = 0; i < applications && applications.length; i++) {
      requires.push(this.name + '.applications.' + applications[i]);
    }

    Ext.require(requires);

    Ext.onReady(function (){
      for(i = 0; i < controllers.length; i++) {
        controller = this.getController(controllers[i]);
        controller.init(this);
      }
      for(i = 0; i < applications.length; i++) {
        var application = this.getApplication(applications[i]);
        application.init(this);
      }

      this.onBeforeLaunch.call(this);
    }, this);

  },

  control:function (selectors, listeners, controller){
    this.eventbus.control(selectors, listeners, controller);
  },

  /**
   * Called automatically when the page has completely loaded. This is an empty function that should be
   * overridden by each application that needs to take action on page load
   * @property launch
   * @type Function
   * @param {String} profile The detected {@link #profiles application profile}
   * @return {Boolean} By default, the Application will dispatch to the configured startup controller and
   * action immediately after running the launch function. Return false to prevent this behavior.
   */
  launch:Ext.emptyFn,

  /**
   * @private
   */
  onBeforeLaunch:function (){
    if(this.enableQuickTips) {
      Ext.tip.QuickTipManager.init();
    }

    if(this.autoCreateViewport) {
      this.getView('Viewport').create();
    }

    this.launch.call(this.scope || this);
    this.launched = true;
    this.fireEvent('launch', this);

    this.controllers.each(function (controller){
      controller.onLaunch(this);
    }, this);
  },

  getModuleClassName:function (name, type){
    var namespace = Ext.Loader.getPrefix(name);

    if(namespace.length > 0 && namespace !== name) {
      return name;
    }

    return this.name + '.' + type + '.' + name;
  },

  getController:function (name){
    var controller = this.controllers.get(name);
    if(!controller) {
      controller = Ext.create(this.getModuleClassName(name, 'controller'), {
        application:this,
        id:name
      });
      this.controllers.add(controller);
    }
    return controller;
  },

  getApplication:function (name){
    var application = this.applications.get(name);
    if(!application || application.allowMultiInstance) {
      Ext.log('Application: ' + name + ' was start');

      application = Ext.create(this.getModuleClassName(name, 'applications'), {
        application: this,
        id: name
      });

      this.applications.add(application);
    }else{
      Ext.Msg.alert('Application '+ name, 'Application already opened. Please close it before launch new instance.');
    }

    return application;
  },

  closeApplication:function (name){
    var application = this.applications.get(name);
    if(application) {
      Ext.log('Application: ' + name + ' was closed');
      this.unloadResources(application);
      this.applications.remove(application);
    }
  },

  unloadResources: function(application){
    application.unloadResources();
  },

  getStore:function (name){
    var store = Ext.StoreManager.get(name);

    if(!store) {
      store = Ext.create(this.getModuleClassName(name, 'store'), {
        storeId:name
      });
    }

    return store;
  },

  getModel:function (model){
    model = this.getModuleClassName(model, 'model');
    return Ext.ModelManager.getModel(model);
  },

  getView:function (view){
    view = this.getModuleClassName(view, 'view');
    return Ext.ClassManager.get(view);
  }
});

Ext.coreApplication = function (config){
  //Ext.require('Core.lib.CoreApplication');

  Ext.onReady(function (){
    Ext.globalApp = Ext.create('Core.lib.CoreApplication', config);
  });
};

Ext.runApplication = function (name){
  Ext.CoreApplication.getApplication(name);
};

Ext.closeApplication = function (name){
  if(!name){ return Ext.MessageBox.alert('Error', 'Try close app with name "null"'); }
  Ext.CoreApplication.closeApplication(name);
};