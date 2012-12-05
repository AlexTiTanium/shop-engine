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
        {
          xtype: 'form',
          region: 'west',
          title: 'Основное описание товара',
          flex: 2,

          bodyPadding: 10,
          items: [
            {
              xtype: 'textfield',
              name: 'name',
              fieldLabel: 'Название товара',
              allowBlank: false  // requires a non-empty value
            },
            {
              xtype: 'numericfield',
              name: 'price',
              allowNegative: false,
              currencySymbol: 'Грн.',
              decimalPrecision: 2,
              allowDecimals: true,
              alwaysDisplayDecimals: true,
              fieldLabel: 'Цена'
            }
          ]
        }
      ]
    }
    // ---------------------------------------->

  ]
});