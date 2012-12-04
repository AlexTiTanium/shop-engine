Ext.Loader.setConfig({
  enabled: true,
  disableCaching: true
});

Ext.Error.handle = function(err) {
  Ext.Msg.alert('Failure', err.msg);
};

Ext.Ajax.extraParams = { token: TOKEN };

Ext.application({
  name: 'Authorizer',
  controllers: ['Login'],
  appFolder: AUTHORIZER_PATH,
  autoCreateViewport: true,
  launch: function() {

  }
});