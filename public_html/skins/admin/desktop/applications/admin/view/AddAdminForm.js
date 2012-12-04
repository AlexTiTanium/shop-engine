Ext.define('Admin.view.AddAdminForm', {
  extend:'Ext.window.Window',
  title:'Добавить администратора',
  width:300,
  modal:true,
  layout:'fit',
  iconCls:'ux-icon-add-user',
  items:[
    {
      xtype:'form',
      id: 'admin-create-form',
      model: 'Admin.model.AdminModel',
      bodyPadding: 10,
      border: false,
      defaultType:'textfield',
      items:[
        { name:'login', fieldLabel: 'Логин', allowBlank: false },
        { name:'email', fieldLabel: 'E-mail', allowBlank: false },
        { name:'password', fieldLabel: 'Пароль', allowBlank: false }
      ],
      buttons:[
        {
          action: 'save',
          text: 'Сохранить',
          formBind:true, //only enabled once the form is valid
          disabled:true
        }
      ]
    }
  ]
});