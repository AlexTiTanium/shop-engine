Ext.define('Shop.view.ProductImagesDataView', {
  extend: 'Ext.view.View',
  store: 'ProductImagesStore',
  id: 'product-images-view',
  alias: 'widget.productImagesDataView',
  tpl:[
    '<tpl for=".">',
      '<div class="thumb-wrap" id="{id}">',
      '<div class="thumb"><img src="/storage/{data.storage}/{data.folder}/80x60-outbound:{id}" title="{name}"></div>',
      '<span class="x-editable">{time:date("d.m.y")}</span></div>',
    '</tpl>',
    '<div class="x-clear"></div>'
  ],
  multiSelect:true,
  overflowY: 'auto',
  overItemCls: 'x-item-over',
  itemSelector: 'div.thumb-wrap',
  emptyText:'No images to display'
});
