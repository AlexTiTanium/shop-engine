Ext.define('Admin.view.Main', {
  id: 'admin-main-window',
  extend : 'Core.lib.Window',
  title: 'Управление администраторами',
  appName: 'Admin',
  iconCls: 'ux-icon-system-users',
  border: false,
  height: 400,
  width: 600,
  layout: 'fit',
  tools: [{
    id: 'admin-tools-refresh',
    type: 'refresh',
    tooltip: 'Обновить список админов'
  }],
  items: [{
    xtype: 'grid',
    store: 'AdminStore',
    selType: 'rowmodel',
    plugins: [
      Ext.create('Core.plugin.RowEditing')
    ],

    tbar: [
      {  action: 'add-admin', xtype: 'button', iconCls: 'ux-icon-add-user', text: 'Создать администратора' },
      '-',
      {  action: 'delete-admin', xtype: 'button', iconCls: 'ux-icon-user-delete', text: 'Удалить администратора' }
    ],
    columns: [
      { text: 'Логин', dataIndex: 'login', editor: 'textfield' },
      { text: 'Email', dataIndex: 'email', flex: 1,  editor: 'textfield' },
      { text: 'Дата', dataIndex: 'date', xtype: 'datecolumn',  format:'Y-m-d' },
      { text: 'Активирован', dataIndex: 'enable', xtype: 'booleancolumn',  editor: 'checkboxfield' }
    ],
    dockedItems: [{
      xtype: 'pagingtoolbar',
      store: 'AdminStore',
      dock: 'bottom',
      displayInfo: true
    }]
  }]
});