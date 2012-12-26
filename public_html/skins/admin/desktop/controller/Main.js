Ext.define('Core.controller.Main', {
  extend:'Ext.app.Controller',

  quitRequest: function(){
    Ext.Ajax.request({
        url: '/admin/users/quit.json',
        success: function(response){
          window.location.reload();
        },
        failure: function(response, opts) {
          Ext.Msg.alert('Failure', 'Ajax communication failed: ' +  response.status);
        }
    });
  },

  init:function (){
    var me = this;

    me.control({
      '#quit':{
        click: me.quit
      }
    });

    Ext.runApplication('Shop');

  },

  quit: function (){
    var me = this;

    Ext.Msg.confirm('Выход из панели управления', 'Вы действительно хотите выйти?', function(button) {
      if (button === 'yes') {
        me.quitRequest();
      }
    });
  }

});