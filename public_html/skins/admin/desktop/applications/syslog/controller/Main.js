Ext.define('Syslog.controller.Main', {
  extend:'Core.lib.Controller',
  views: ['Syslog.view.Main'],

  refs: [
    {
      ref: 'window',
      selector: '#syslog-main-window'
    },
    {
      ref: 'recordCounter',
      selector: '#syslog-record-counter'
    }
  ],

  init:function (){
     var me = this;

     me.control({
       'window':{
         afterrender: function(el){
           if(me.getWindow() != el){ return; }
           me.updateLog();
         }
       },
       '#syslog-tools-refresh':{
         click: me.updateLog
       },
       '#syslog-clear-all':{
         click: function(){
           Ext.Msg.confirm('Очистка логов', 'Вы действительно хотите удалить все логи?', function(button) {
             if (button === 'yes') {
              me.clearAll();
             }
           });
         }
       }
     });

   },

  updateLog: function(){
    var me = this;

    me.getWindow().setLoading(true);

    Ext.Ajax.request({
        url: '/admin/syslog/getLog.json',
        success: function(response){
          var responseObj = Ext.decode(response.responseText);
          me.getRecordCounter().setText(responseObj.count.toString());
          me.getWindow().update(responseObj.data);
          me.getWindow().setLoading(false);
        },
        failure: function(response, opts) {
          Ext.Msg.alert('Failure', 'Ajax communication failed: ' +  response.status);
          me.getWindow().setLoading(false);
        }
    });
  },

  clearAll: function(){
    var me = this;

    me.getWindow().setLoading(true);

    Ext.Ajax.request({
        url: '/admin/syslog/clearLog.json',
        success: function(response){
          me.updateLog();
          me.getWindow().setLoading(false);
        },
        failure: function(response, opts) {
          Ext.Msg.alert('Failure', 'Ajax communication failed: ' +  response.status);
          me.getWindow().setLoading(false);
        }
    });
  }



});