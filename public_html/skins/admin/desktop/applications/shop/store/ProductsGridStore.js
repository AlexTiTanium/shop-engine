Ext.define('Shop.store.ProductsGridStore', {
  extend:'Ext.data.Store',
  storeId:'ProductsGridStore',
  autoSync:true,
  model:'Shop.model.ProductsGridModel',
  pageSize: 15,
  remoteSort: true,
  proxy:{
    type:'ajax',
    api:{
      read:'/admin/shop/products.json',
      create:'/admin/shop/products/create.json',
      update:'/admin/shop/products/update.json',
      destroy:'/admin/shop/products/destroy.json'
    }
  }
});