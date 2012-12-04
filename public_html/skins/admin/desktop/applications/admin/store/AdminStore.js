Ext.define('Admin.store.AdminStore', {
  extend:'Ext.data.Store',
  storeId:'AdminStore',
  autoSync:true,
  model:'Admin.model.AdminModel',
  pageSize: 15,
  remoteSort: true,
  proxy:{
    type:'ajax',
    api:{
      read:'/admin/admin.json',
      create:'/admin/admin/create.json',
      update:'/admin/admin/update.json',
      destroy:'/admin/admin/destroy.json'
    }
  }
});