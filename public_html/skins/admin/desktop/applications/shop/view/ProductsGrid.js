Ext.define('Shop.view.ProductsGrid', {
  extend:'Ext.grid.Panel',
  alias:'widget.productsGrid',
  store:'ProductsGridStore',
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
    { text:'Товар', dataIndex:'name', flex:1, editor:{
      xtype:'textfield',
      allowBlank:false
    }},

    { text:'Цена', dataIndex:'price', flex:1, editor:{
      xtype:'spinnerfield',
      allowBlank:false
    }},

    { text:'Статус', dataIndex:'status', editor:{
      xtype:'combobox',
      triggerAction:'all',
      selectOnTab:true,
      editable: false,
      store: 'ProductStatusStore',
      lazyRender:true,
      listClass:'x-combo-list-small'
      },
      renderer: function(key){
        var data = {
          active: 'Активный',
          ends: 'Заканчивается',
          new: 'Новинка',
          promotion: 'Акция',
          text: 'Нет в наличии' ,
          disable: 'Не показывать'
        };
        return data[key];
      }
    }
  ],
  dockedItems:[
    {
      xtype:'pagingtoolbar',
      store:'ProductsGridStore',
      dock:'bottom',
      displayInfo:true
    }
  ]
});