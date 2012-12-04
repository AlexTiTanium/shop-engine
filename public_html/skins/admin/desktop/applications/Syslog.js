Ext.define('Core.applications.Syslog', {
  extend: 'Core.lib.Application',
  name: 'Syslog',
  appFolder: 'syslog',
  allowMultiInstance: false,

  controllers: ['Main'],
  views: ['Main'],

  css: ['css/syslog.css'],

  launch: function (){
    var me = this;

    var window = me.getView('Main');
    window.show();
  }

});