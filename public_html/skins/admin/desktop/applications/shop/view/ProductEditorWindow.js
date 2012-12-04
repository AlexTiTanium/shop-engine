Ext.define('Shop.view.ProductEditorWindow', {
  extend:'Ext.window.Window',
  border:false,
  layout: 'border',
  modal:true,
  title:'Редактировать продукт: ',
  alias: 'widget.productEditorWindow',
  height:350,
  width:700,

  items:[
    {
      xtype: 'panel',  region: 'north',
      height: 100,
      margin: '10',
      border: false,
      layout: 'border',
      items: [
        {region: 'west', width: 100, height: 100, xtype: 'image', src: Ext.imagePath + '/noimage.jpg' }
      ]
    }


  ]
});