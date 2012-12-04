Ext.define('ShopTypes.view.ProductsAttributeNumber', {
  extend:'Ext.window.Window',
  layout:'fit',
  border:false,
  modal:true,
  title:'Редактировать свойства: ',
  alias: 'widget.ProductsAttributeNumber',
  height:150,
  width:350,

  items:[
     {
      xtype: 'form',
      frame:true,
      bodyPadding:10,
      layout:'form',
      defaultType:'textfield',
      items:[
        {
          fieldLabel:'Префикс',
          name:'prefix',
          allowBlank:true
        },
        {
          fieldLabel:'Постфикс',
          name:'postfix',
          allowBlank:true
        },
        {
          xtype: 'checkbox',
          fieldLabel:'С точкой',
          name:'float',
          allowBlank:true
        }
      ],
      buttons:[
        {
          action:'save',
          text:'Сохранить',
          formBind:true, //only enabled once the form is valid
          disabled:true
        }
      ]
    }
  ]
});