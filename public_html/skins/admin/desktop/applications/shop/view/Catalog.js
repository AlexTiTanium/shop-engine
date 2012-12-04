Ext.define('Shop.view.Catalog', {
  extend:'Ext.tree.Panel',
  title: 'Каталог товаров',
  alias: 'widget.catalog',
  region:'west',
  split:true,
  displayField: 'name',
  border: false,
  hideHeaders: true,
  selModel: { allowDeselect: true },
  tools:[
    { action: 'tree-refresh', type: 'refresh', tooltip: 'Обновить дерево' }
  ],
  plugins: [
    Ext.create('Ext.grid.plugin.CellEditing', {clicksToEdit:2})
  ],
  columns:[
    {
      xtype:'treecolumn',  dataIndex:'name', flex: 1, menuDisabled: true, sortable: false,
      editor:{ xtype:'textfield', allowBlank:false}
    }
  ],
  viewConfig: {
    stateful: true,
    stateId: 'shop_catalog_tree',
    plugins:  [ Ext.create('Core.plugin.TreeStateful'), {ptype: 'treeviewdragdrop'} ]
  },
  bbar: [
    { action:'folder-add', tooltip: 'Создать папку', xtype: 'button', iconCls: 'ux-icon-folder-add' },
    { action:'node-add', tooltip: 'Создать каталог товаров', xtype: 'button', iconCls: 'ux-icon-package-add' },
    { action:'node-delete', tooltip: 'Удалить', xtype: 'button', iconCls: 'ux-icon-delete' }
  ],
  collapsible: true,
  width:150,
  rootVisible: false,
  store: 'catalogStore'
});