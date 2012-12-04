Ext.define('Authorizer.view.LoginForm', {
  extend:'Ext.form.Panel',
  alias:'widget.LoginForm',
  frame:true,
  bodyPadding: 10,
  layout:'form',
  defaultType: 'textfield',
  items:[
    {
      fieldLabel:'Логин',
      name:'login',
      allowBlank:false
    },
    {
      fieldLabel:'Пароль',
      name:'password',
      inputType:'password',
      allowBlank:false
    }
  ],
  buttons:[
    {
      text:'Отмена',
      handler:function (){
        this.up('form').getForm().reset();
      }
    },
    {
      id: 'enter',
      text:'Войти',
      formBind:true, //only enabled once the form is valid
      disabled:true
    }
  ]
});