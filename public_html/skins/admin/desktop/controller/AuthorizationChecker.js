Ext.define('Core.controller.AuthorizationChecker', {
  extend:'Ext.app.Controller',

  checkAuthTask: null,

  init:function (){

    var me = this;

    me.checkAuthTask = {
      run: me.checkAuthorization,
      interval: 50000,
      scope: me
    };

    Ext.TaskManager.start(me.checkAuthTask);

    me.control({
      '#enter':{
        click: me.enterEvent
      }
    });
  },

  /**
   * Logic
   */
  checkAuthorization: function(){

    var me = this;

    Ext.Ajax.request({
      url: '/admin/users/isAuthorized.json',
      success: function(response){
        var data = Ext.JSON.decode(response.responseText);

        if(data.isAuthorized == false){
          Ext.TaskManager.stop(me.checkAuthTask);
          me.showLoginWindow();
        }

      }
    });

  },

  showLoginWindow: function(){

    var window = Ext.create('Core.view.LoginWindow');
    window.show();
  },

  enterEvent:function (el){

    var me = this, form = el.up('form').getForm();
    form.submit({
      clientValidation: true,
      url:'/admin/users/login.json',
      success:function (form, action){
        el.up('form').up('window').close();
        Ext.TaskManager.start(me.checkAuthTask);
      },
      waitMsg: 'Обработка запроса...',
      failure:function (form, action){
        switch(action.failureType) {
          case Ext.form.action.Action.CLIENT_INVALID:
            Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
            break;
          case Ext.form.action.Action.CONNECT_FAILURE:
            Ext.Msg.alert('Failure', 'Ajax communication failed');
            break;
          case Ext.form.action.Action.SERVER_INVALID:
            Ext.Msg.alert('Failure', action.result.msg);
        }
      }
    });
  }

});