Ext.define('ShopTypes.store.ProductPropertyStore', {
  extend:'Ext.data.Store',
  storeId:'ProductPropertyStore',
  autoSync:true,
  autoLoad:false,
  model:'ShopTypes.model.ProductPropertyModel',
  pageSize: 15,
  remoteSort: true,
  proxy:{
    type:'ajax',
    api:{
      read:'/admin/shop/shopTypesProperties.json',
      create:'/admin/shop/shopTypesProperties/create.json',
      update:'/admin/shop/shopTypesProperties/update.json',
      destroy:'/admin/shop/shopTypesProperties/destroy.json'
    }
  }
});