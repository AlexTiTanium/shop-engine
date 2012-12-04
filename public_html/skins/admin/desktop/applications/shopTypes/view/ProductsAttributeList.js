Ext.define('ShopTypes.view.ProductsAttributeList', {
  extend:'Ext.window.Window',
  layout:'fit',
  border:false,
  modal:true,
  title:'Редактировать свойства: ',
  alias:'widget.ProductsAttributeList',
  height:300,
  width:200,

  items:[
    {
      xtype:'grid',
      selType:'cellmodel',
      plugins:[
        { ptype:'cellediting', pluginId:'cellediting',  clicksToEdit: 2 }
      ],
      columns:[
        {text:'Имя', flex:1, dataIndex:'name', editor: 'textfield'}
      ],
      store:Ext.create('Ext.data.Store', {
        autoLoad: false,
        autoSync: true,
        fields:['name'],
        proxy:{ type:'memory'}
      }),
      bbar:[
        {
          xtype:'button',
          action:'add',
          iconCls:'ux-icon-add',
          handler:function (btn){

            var grid = btn.up('grid'), storage = grid.getStore(), edit = grid.getPlugin('cellediting');

            edit.cancelEdit();
            storage.insert(0, {name: ''});
            edit.startEdit(0, 0);
          }
        },
        {
          xtype:'button',
          action:'delete',
          iconCls:'ux-icon-delete',
          handler:function (btn){

            var grid = btn.up('grid'), storage = grid.getStore(), selected = grid.getSelectionModel().getSelection()[0];

            if(!selected) {
              Ext.Msg.alert('Ошибка', 'Вы должны выбрать хотя бы одну запись.');
              return;
            }

            grid.getStore().remove(selected);
          }
        },
        '->',
        {
          xtype:'button',
          action:'save',
          text:'Cохранить',
          iconCls:'ux-icon-save'
        }
      ]
    }
  ]
});