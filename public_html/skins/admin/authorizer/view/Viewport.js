Ext.define('Authorizer.view.Viewport', {
  extend:'Ext.container.Viewport',

  uses: [
    'Authorizer.view.LoginWindow'
  ],

  layout:'fit',
  items:[
    { xtype: 'LoginWindow'}
  ]

});