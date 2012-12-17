Ext.define('Shop.view.ProductImageUploadForm', {
  extend:'Ext.window.Window',
  alias: 'widget.ProductImageUploadForm',
  title:'Загрузить изобржение продукта',
  width:400,
  modal:true,
  border:false,
  items:[
    {
      xtype:'form',
      bodyPadding:10,
      items:[
        {
          xtype:'filefield',
          name:'image',
          fieldLabel:'Изображение продукта',
          msgTarget:'side',
          allowBlank:false,
          anchor:'100%',
          buttonText:'Выбрать'
        }
      ],
      buttons:[
        {
          text:'Загрузить',
          action: 'upload'
        }
      ]
    }
  ]
});