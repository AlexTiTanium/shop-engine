Ext.define('Core.menu.Shop', {
  extend: 'Ext.menu.Menu',
  xtype: 'ux-menu-shop',
  items:[
    {
      iconCls: 'ux-icon-package-link',
      text: 'Типы товаров',
      handler: function(){
        Ext.runApplication('ShopTypes');
      }
    },
    {
      iconCls: 'ux-icon-package',
      text: 'Каталог товаров',
      handler: function(){
        Ext.runApplication('Shop');
      }
    },
    '-',
    {
      iconCls: 'ux-icon-basket',
      text: 'Заказы',
      handler: function(){
        //Ext.runApplication('Admin');
      }
    }
  ]
});