Ext.define('Shop.view.ProductEditorWindow', {
  extend:'Ext.window.Window',
  border:false,
  layout: 'fit',
  modal:true,
  title:'Редактировать или создать товар',
  alias: 'widget.productEditorWindow',
  height: 425,
  width:700,

  items:[
    { xtype: 'productEditorForm' }
  ]
});