Ext.define('Core.menu.System', {
  extend: 'Ext.menu.Menu',
  xtype: 'ux-menu-system',
  items:[
    {
      iconCls: 'ux-icon-journal',
      text: 'Системный лог',
      handler: function(){
        Ext.runApplication('Syslog');
      }
    },
    {
      iconCls: 'ux-icon-system-users',
      text: 'Управление админами',
      handler: function(){
        Ext.runApplication('Admin');
      }
    }
/*,
    {xtype: 'menuseparator'},

    {
      iconCls: 'ux-icon-application',
      text:'Start Authorizer',
      handler: function(){
        Ext.runApplication('Authorizer');
      }
    }
    */
  ]
});