
Ext.Loader.setConfig({
  enabled: true,
  disableCaching: true
});

Ext.Error.handle = function(err) {
  Ext.Msg.alert('Failure', err.msg);
};

Ext.override(Ext.data.AbstractStore,{
    indexOf: Ext.emptyFn
});

Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
  expires: new Date(new Date().getTime()+(1000*60*60*24*7)) //7 days from now
}));

Ext.define('Override.data.Proxy', {
  override : 'Ext.data.Proxy',
  reader:{
    type:'json',
    root:'data'
  },
  writer:{
    type:'json',
    root: 'data',
    encode: true
  },
  listeners: {
    exception : function(proxy, response, operation) {
      Ext.Msg.alert('Server error', Ext.decode(response.responseText).msg);
    }
  }
});

Ext.Ajax.extraParams = { token: TOKEN };

Ext.imagePath = IMAGES_PATH;

Ext.coreApplication({
  name: 'Core',
  appFolder: DESKTOP_PATH,
  models: [],
  autoCreateViewport: true,
  controllers: ['Main', 'AuthorizationChecker'],
  applications: [

  ],

  launch:function (){
    console.log('App launch');
  }
});