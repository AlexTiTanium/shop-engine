Ext.define('Shop.view.ProductsGrid', {
  extend:'Ext.grid.Panel',
  alias:'widget.productsGrid',
  store:'ProductsStore',
  selType:'rowmodel',
  layout:'fit',
  hidden:true,
  flex: 1,
  border:false,
  plugins:[
    { ptype:'rowediting', pluginId:'rowediting' }
  ],
  tbar:[
    {  action:'add-product', xtype:'button', iconCls:'ux-icon-add', text:'Добавить' },
    {  action:'remove-product', xtype:'button', iconCls:'ux-icon-delete', text:'Удалить' },
    {  action:'edit-product', xtype:'button', iconCls:'ux-icon-gear', text:'Редактировать' }
  ],
  columns:[
    { text:'Товар', dataIndex:'name', flex:4, editor:{
      xtype:'textfield',
      allowBlank:false
    }},

    { text:'Цена', dataIndex:'price', flex:2, editor:{
        xtype:'numericfield',
        name:'price',
        allowNegative:false,
        currencySymbol:'Грн.',
        decimalPrecision:2,
        allowDecimals:true,
        alwaysDisplayDecimals:true
      },
      renderer: function(value){
        return Ext.util.Format.currency(value, ' Грн.', 2, true);
      }
    },

    { text:'Количество', dataIndex:'count', flex:1, editor:{
      xtype:'numberfield',
      name:'count'
    }},

    { text:'Статус', dataIndex:'status', flex:2, editor:{
      xtype:'combobox',
      triggerAction:'all',
      selectOnTab:true,
      editable: false,
      store: 'ProductStatusStore',
      displayField: 'text',
      valueField: 'value',
      listClass:'x-combo-list-small'
      },
      renderer: function(key){
        var data = {
          active: 'Активный',
          ends: 'Заканчивается',
          new: 'Новинка',
          promotion: 'Акция',
          ended: 'Нет в наличии' ,
          disable: 'Не показывать',
          coming_soon: 'Ожидается поставка'
        };
        return data[key];
      }
    }
  ],
  dockedItems:[
    {
      xtype:'pagingtoolbar',
      store:'ProductsStore',
      dock:'bottom',
      displayInfo:true
    }
  ]
});