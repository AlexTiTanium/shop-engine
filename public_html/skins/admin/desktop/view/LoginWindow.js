Ext.define('Core.view.LoginWindow', {
  extend:'Ext.window.Window',
  layout:'fit',
  border: false,
  modal: true,
  title:'Вход в панель управления сайтом: ',

  height:150,
  width:350,

  autoShow:true,

  closable:false,
  draggable:false,
  resizable:false,

  items:[
    Ext.create('Ext.form.Panel', {
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
    })
  ]

});