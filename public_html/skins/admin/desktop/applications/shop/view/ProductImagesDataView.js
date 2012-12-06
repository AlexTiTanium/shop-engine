Ext.define('Shop.view.ProductImagesDataView', {
  extend: 'Ext.view.View',
  alias: 'widget.productImagesDataView',
  tpl:[
    '<tpl for=".">',
      '<div class="thumb-wrap" id="{name}">',
      '<div class="thumb"><img src="{url}" title="{name}"></div>',
      '<span class="x-editable">{shortName}</span></div>',
    '</tpl>',
    '<div class="x-clear"></div>'
  ],
  multiSelect:true,
  height: 310,
  trackOver:true,
  overItemCls:'x-item-over',
  itemSelector:'div.thumb-wrap',
  emptyText:'No images to display'
});
