Ext.define('Authorizer.view.LoginWindow', {
  extend:'Ext.window.Window',
  alias:'widget.LoginWindow',
  layout:'fit',
  border: false,
  uses:[
    'Authorizer.view.LoginForm'
  ],

  title:'Вход в панель управления сайтом: ' + SITE_NAME,

  height:150,
  width:350,

  autoShow:true,

  closable:false,
  draggable:false,
  resizable:false,

  items:[
    { xtype:'LoginForm' }
  ]

});