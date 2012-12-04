Ext.define('Core.applications.Admin', {
  extend: 'Core.lib.Application',
  name: 'Admin',
  appFolder: 'admin',
  allowMultiInstance: false,
  controllers: ['Main'],
  views: ['Main'],
  storage: ['AdminStore'],
  model: ['AdminModel'],

  launch: function (){
    var me = this;

    var window = me.getView('Main');
    window.show();
  }

});