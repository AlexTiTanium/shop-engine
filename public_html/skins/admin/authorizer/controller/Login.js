Ext.define('Authorizer.controller.Login', {
  extend:'Ext.app.Controller',

  submit:{
    clientValidation: true,
    url:'/admin/users/login.json',
    success:function (form, action){
      window.location.reload();
    },
    waitMsg: 'Обработка запроса...',
    failure:function (form, action){
      console.log(action);
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
  },

  init:function (){
    var me = this;

    me.control({
      'textfield': {
        specialkey: function(field, e) {
          if(e.getKey() == e.ENTER) {
            me.enterEvent(field);
          }
        },
        afterrender: function(field){
          if(field.name == 'login'){
            field.focus(true, 500);
          }
        }
      },
      '#enter':{
        click: me.enterEvent
      }
    });
  },

  enterEvent:function (el){

    var me = this, form = el.up('form').getForm();
    form.submit(me.submit);
  }
});