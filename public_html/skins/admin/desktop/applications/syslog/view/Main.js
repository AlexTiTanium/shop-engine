Ext.define('Syslog.view.Main', {
  id: 'syslog-main-window',
  extend : 'Core.lib.Window',
  title: 'Системынй лог',
  appName: 'Syslog',
  iconCls: 'ux-icon-journal',
  height: 400,
  width: 600,
  layout: 'fit',
  autoScroll:true,
  bodyCls: 'ux-syslog-main-window',
  tools: [{
    id: 'syslog-tools-refresh',
    type: 'refresh',
    tooltip: 'Обновить лог'
  }],
  dockedItems: [{
    xtype: 'toolbar',
    dock: 'bottom',
    items: [
      { xtype: 'tbtext', text: 'Записей: ' },
      { xtype: 'tbtext', id: 'syslog-record-counter', text: '0' },
      '->',
      {
        text: 'Очистить',
        iconCls: 'ux-icon-trash',
        id: 'syslog-clear-all'
      }
    ]
  }]
});