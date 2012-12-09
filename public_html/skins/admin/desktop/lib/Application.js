Ext.define('Core.lib.Application', {
  extend:'Ext.app.Controller',
  appFolder: 'application',
  allowMultiInstance: false,
  rootApplicationsPath: DESKTOP_PATH+'/applications/',
  css:[],
  loadedCss: [],
  storage: [],
  model: [],

  /**
   * Creates new Application.
   * @param {Object} [config] Config object.
   */
  constructor: function (config){
    config = config || {};

    config.appFolder = this.rootApplicationsPath + this.appFolder;

    Ext.apply(this, config);

    var requires = config.requires || [];

    if(this.name) {
      Ext.Loader.setPath(this.name, this.appFolder);
    }

    if(this.paths) {
      Ext.Object.each(this.paths, function (key, value){
        Ext.Loader.setPath(key, value);
      });
    }

    this.prepareViews();

    var controllers = Ext.Array.from(this.controllers),
      ln = controllers && controllers.length, i, controller;

    this.controllers = Ext.create('Ext.util.MixedCollection');

    for(i = 0; i < ln; i++) {
      requires.push(this.getModuleClassName(controllers[i], 'controller'));
    }

    // Css
    this.loadedCss = [];

    Ext.each(this.css,      this.loadCss,   this);
    Ext.each(this.model,    this.loadModel, this);
    Ext.each(this.storage,  this.loadStore, this);

    Ext.require(requires, function (){
      for(i = 0; i < ln; i++) {
        controller = this.getController(controllers[i]);
        controller.init(this);
      }
      this.onBeforeLaunch.call(this);
      return true;
    }, this);

  },

  prepareViews: function(){
    var views = Ext.Array.from(this.views),
          ln = views && views.length, i, requires = [];

    for(i = 0; i < ln; i++) {
      requires.push(this.getModuleClassName(views[i], 'view'));
    }

    Ext.require(requires);
  },

  control:function (selectors, listeners, controller){
    this.application.control(selectors, listeners, controller);
  },

  launch:Ext.emptyFn,

  /**
   * @private
   */
  onBeforeLaunch:function (){
    this.launch.call(this.scope || this);
    this.fireEvent('launch', this);

    this.controllers.each(function (controller){
      controller.onLaunch(this);
    }, this);
  },

  unloadResources: function(){
    var me = this, eventBus = me.application.eventbus, controllersIds = [];

    this.controllers.each(function (controller){
      controllersIds.push(controller.id);
    }, me);

    eventBus.uncontrol(controllersIds);

    Ext.each(me.loadedCss, me.unloadCss, me);
  },

  getModuleClassName:function (name, type){
    var namespace = Ext.Loader.getPrefix(name);

    if(namespace.length > 0 && namespace !== name) {
      return name;
    }
    return this.id + '.' + type + '.' + name;
  },

  getController:function (name){
    var controller = this.controllers.get(this.id + name);

    if(!controller) {
      controller = Ext.create(this.getModuleClassName(name, 'controller'), {
        application:this.application,
        id:this.id + name
      });
      this.controllers.add(controller);
    }
    return controller;
  },

  loadStore:function (name){
    var store = Ext.StoreManager.get(name);
    if(!store) {
      store = Ext.create(this.getModuleClassName(name, 'store'));
    }
    return store;
  },

  loadModel:function (model){
    model = this.getModuleClassName(model, 'model');
    var loadedModel;

    Ext.require(model, function(){
      loadedModel = Ext.ModelManager.getModel(model);
      if(!loadedModel){
        Ext.Error.raise('Loading model error, can not load model: ' + model);
      }
    });
  },

  getView:function (view){
    view = this.getModuleClassName(view, 'view');
    return Ext.create(view);
  },

  loadCss: function(cssPath){
    var me  = this,
      link = document.createElement('link'),
      linkConfig = {
        rel: 'stylesheet',
        type: 'text/css',
        href: me.appFolder + '/' + cssPath
      };

    console.log('Loaded css '+cssPath);

    // apply options to the stylesheet element
    Ext.apply(link, linkConfig);

    // append element to HEAD
    document.getElementsByTagName('head')[0].appendChild(link);

    // Save link
    me.loadedCss.push(link);
  },

  unloadCss: function(link){
    console.log('Unload css ');
    document.getElementsByTagName('head')[0].removeChild(link);
  }
});