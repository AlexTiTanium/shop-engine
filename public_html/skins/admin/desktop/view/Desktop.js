Ext.define('Core.view.Desktop', {
  extend: 'Ext.panel.Panel',
  alias: 'widget.Desktop',
  cls: 'ux-core-desktop',
  bodyCls: 'ux-core-desktop-body',
  uses: [
    'Core.view.Wallpaper',
    'Core.view.LaunchPanel'
  ],

  initComponent:function (){
    var me = this;

    Ext.apply(me, {
      dockedItems: me.getMenu(),
      items: me.getItems()
    });

    me.callParent(arguments);
  },

  getItems: function(){
    var me = this;

    return [
      { xtype: 'Wallpaper'}
    ];
  },

  getMenu: function (){
    return [
      { xtype:'LaunchPanel' }
    ];
  }

});