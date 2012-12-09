Ext.define('Shop.view.ProductImagesDataView', {
  extend: 'Ext.view.View',
  store: 'ProductsStore',
  id: 'product-images-view',
  alias: 'widget.productImagesDataView',
  tpl:[
    '<tpl for=".">',
      '<div class="thumb-wrap" id="{name}">',
      '<div class="thumb"><img src="http://lotos.ks.ua/skins/admin/resources/images/noimage.jpg" title="{name}"></div>',
      '<span class="x-editable">20.12.12</span></div>',
    '</tpl>',
    '<div class="x-clear"></div>'
  ],
  multiSelect:true,
  overflowY: 'auto',
  overItemCls: 'x-item-over',
  itemSelector: 'div.thumb-wrap',
  emptyText:'No images to display'
});
