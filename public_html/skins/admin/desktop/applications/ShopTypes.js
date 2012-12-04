Ext.define('Core.applications.ShopTypes', {
  extend: 'Core.lib.Application',
  name: 'ShopTypes',
  appFolder: 'shopTypes',
  allowMultiInstance: false,
  controllers: ['ProductsTree', 'ProductsProperties'],
  views: ['Main', 'ProductsTree', 'PropertiesGrid'],
  storage: ['ProductPropertyStore', 'ProductsTreeStore'],
  model: ['ProductPropertyModel'],

  launch: function (){
    var me = this;

    var window = me.getView('Main');
    window.show();
  }

});