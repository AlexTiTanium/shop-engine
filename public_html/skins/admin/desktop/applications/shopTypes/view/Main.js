Ext.define('ShopTypes.view.Main', {
  id: 'shopTypes-main-window',
  alias: 'widget.shopTypesWindow',
  extend : 'Core.lib.Window',
  title: 'Типы товаров',
  appName: 'ShopTypes',
  iconCls: 'ux-icon-package-link',
  border: false,
  height: 400,
  width: 700,
  layout: 'border',
  items: [
    {xtype: 'ProductsTree'},
    {xtype: 'panel',
      alias: 'widget.propertiesPanel',
      title:'Свойства товара',
      layout:'fit',
      border:false,
      items: [ {xtype: 'PropertiesGrid'} ],
      region:'center',
      html:'<center style="padding-top: 150px;"><b>Выберите товар что бы посмотреть его свойства</b></center>'
    }
  ]
});