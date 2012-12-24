Ext.define('Shop.store.ProductsStore', {
  extend:'Ext.data.Store',
  storeId:'ProductsStore',
  autoSync:true,
  model:'Shop.model.ProductModel',
  pageSize: 15,
  remoteSort: true,
  proxy:{
    type:'ajax',
    api:{
      read:'/admin/shop/products.json',
      create:'/admin/shop/product/create.json',
      update:'/admin/shop/product/update.json',
      destroy:'/admin/shop/product/destroy.json'
    }
  }
});