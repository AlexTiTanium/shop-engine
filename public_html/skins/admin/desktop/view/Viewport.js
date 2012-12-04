Ext.define('Core.view.Viewport', {
  extend:'Ext.container.Viewport',
  uses:[
    'Core.view.Desktop'
  ],
  layout:'fit',
  items:[
    { xtype:'Desktop' }
  ]

});