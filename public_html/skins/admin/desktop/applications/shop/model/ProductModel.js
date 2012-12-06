Ext.define('Shop.model.ProductModel', {
  extend:'Ext.data.Model',
  fields:[
    { name:'images' }
  ],
  proxy:{
    type:'ajax',
    api:{
      read:'/admin/shop/product.json',
      create:'/admin/shop/product/create.json',
      update:'/admin/shop/product/update.json',
      destroy:'/admin/shop/product/destroy.json'
    }
  }
});
