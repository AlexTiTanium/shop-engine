Ext.define('Admin.controller.Main', {
  extend:'Core.lib.Controller',
  views: ['Admin.view.Main'],
  requires: ['Admin.store.AdminStore'],
  refs: [
    { ref: 'window', selector: '#admin-main-window' },
    { ref: 'grid', selector: '#admin-grid' },
    { ref: 'form', selector: '#admin-create-form' }
  ],

  init:function (){

    var me = this;

    me.control({
      'grid':{
        afterrender: function(el){ el.getStore().load(); }
      },
      '#admin-tools-refresh':{
        click: function(el) { me.getStore('AdminStore').reload(); }
      },

      'window button[action=add-admin]':{
        click: me.showAddForm
      },
      'window button[action=delete-admin]':{
        click: function(btn){

          var grid = btn.up('window').down('grid');

          if(!grid.getSelectionModel().hasSelection()){
            Ext.Msg.alert('Ошибка', 'Вы должны выбрать хотя бы одну запись.');
            return;
          }

          Ext.Msg.confirm('Удалить администратора', 'Вы действительно хотите удалить администратора?', function(button) {
            if (button === 'yes') {
              me.deleteAdmin(grid.getSelectionModel().getSelection());
            }
          });
        }
      },

      'form button[action=save]':{
        click: me.addAdmin
      }

    });

  },

  /**
   * Logic
   */

  showAddForm: function(){

    var form = Ext.create('Admin.view.AddAdminForm');
    form.down('form').loadRecord(Ext.create('Admin.model.AdminModel'));
    form.show();
  },

  addAdmin: function(btn){

    var me = this;
    var form = btn.up('form').getForm();
    var window = btn.up('window');
    var record = form.getRecord();

    form.updateRecord(record);

    if(record.isValid()) {
      me.getStore('AdminStore').add(record);
      window.close();
    }else{
      Ext.Msg.alert('Error', 'You have error in form');
      console.log(record.validate());
    }
  },

  deleteAdmin: function(selected){
    this.getStore('AdminStore').remove(selected);
  }

});