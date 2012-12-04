Ext.define('Core.view.LaunchPanel', {
  extend:'Ext.toolbar.Toolbar',
  cls:'ux-core-LaunchPanel',
  alias:'widget.LaunchPanel',
  height: 30,
  uses:[
    'Core.menu.System',
    'Core.menu.Shop',
    'Core.view.TaskBar'
  ],

  taskBar: null,

  initComponent:function (){
    var me = this;

    me.taskBar = Ext.create('Core.view.TaskBar');

    Ext.apply(me, {
      items: me.getItems()
    });

    me.callParent(arguments);
  },

  getTaskBar:function (){
    return this.taskBar;
  },

  getItems: function (){
    return [
      {
        text:'Система',
        iconCls:'ux-icon-system',
        menu:{ xtype:'ux-menu-system'}
      },
      {
        text: 'Магазин',
        iconCls:'ux-icon-cart',
        menu:{ xtype:'ux-menu-shop'}
      },
      '->',
      this.getTaskBar(),
      '-',
      {
        id: 'quit',
        iconCls:'ux-icon-shutdown'
      }
    ]
  }//---

});