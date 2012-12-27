Ext.define('Shop.store.ProductImagesStore', {
  extend:'Ext.data.Store',
  storeId:'ProductImagesStore',
  autoSync:true,
  model:'Shop.model.ProductImageModel',
  proxy:{
    type:'ajax',
    api:{
      read:'/admin/shop/productImages.json',
      update:'/admin/shop/productImages/update.json',
      destroy:'/admin/shop/productImages/remove.json'
    }
  }
});