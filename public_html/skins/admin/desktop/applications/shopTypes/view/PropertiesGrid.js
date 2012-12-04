Ext.define('ShopTypes.view.PropertiesGrid', {
  extend:'Ext.grid.Panel',
  alias:'widget.PropertiesGrid',
  store:'ProductPropertyStore',
  selType:'rowmodel',
  layout:'fit',
  hidden:true,
  split:true,
  flex: 1,
  border:false,
  plugins:[
    { ptype:'rowediting', pluginId:'rowediting' }
  ],
  tbar:[
    {  action:'add-property', xtype:'button', iconCls:'ux-icon-add', text:'Добавить' },
    {  action:'remove-property', xtype:'button', iconCls:'ux-icon-delete', text:'Удалить' },
    {  action:'edit-attribute', xtype:'button', hidden: true, iconCls:'ux-icon-gear', text:'Редактировать свойства' }
  ],
  columns:[
    { text:'Свойство товара', dataIndex:'name', flex:1, editor:{
      xtype:'textfield',
      allowBlank:false
    }},

    { text:'Тип свойства', dataIndex:'type', editor:{
      xtype:'combobox',
      triggerAction:'all',
      selectOnTab:true,
      editable: false,
      store:[
        ['string', 'Строка'],
        ['number', 'Число'],
        ['text', 'Текст'],
        ['list', 'Список'],
        ['bool', 'Опция']
      ],
      lazyRender:true,
      listClass:'x-combo-list-small'
      },
      renderer: function(key){
        var data = {string: 'Строка', number: 'Число', text: 'Текст' , list: 'Список' , bool: 'Опция'};
        return data[key];
      }
    }
  ],
  dockedItems:[
    {
      xtype:'pagingtoolbar',
      store:'ProductPropertyStore',
      dock:'bottom',
      displayInfo:true
    }
  ]
});