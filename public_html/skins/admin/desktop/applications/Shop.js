Ext.define('Core.applications.Shop', {
  extend: 'Core.lib.Application',
  name: 'Shop',
  appFolder: 'shop',
  allowMultiInstance: false,
  controllers: ['Catalog', 'ProductsGrid', 'ProductsPanel', 'ProductEditorWindow'],
  views: [
    'Main',
    'Catalog',
    'ProductsGrid',
    'ProductEditorWindow',
    'ProductEditorForm',
    'ProductImagesDataView'
  ],
  storage: ['CatalogStore', 'ProductsStore', 'ProductStatusStore', 'ProductImagesStore'],
  model: ['ProductModel', 'ProductImageModel'],

  css: ['css/shop.css'],

  launch: function (){
    var me = this;

    var window = me.getView('Main');
    window.show();
  }

});