Ext.define('Shop.view.Main', {
  alias: 'widget.shopMainWindow',
  extend : 'Core.lib.Window',
  title: 'Каталог товаров',
  appName: 'Shop',
  iconCls: 'ux-icon-package',
  border: false,
  height: 400,
  width: 700,
  layout: 'border',
  items: [
    {xtype: 'catalog'},
    {xtype: 'panel',
     alias: 'widget.productsPanel',
     title:'Товары',
     layout:'fit',
     border:false,
     items: [ {xtype: 'productsGrid'} ],
     region:'center',
     html:'<center style="padding-top: 150px;"><b>Выберите каталог товаров</b></center>'
  }]
});