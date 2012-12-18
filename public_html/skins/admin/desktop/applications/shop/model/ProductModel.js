Ext.define('ProductImageModel', {
  extend:'Ext.data.Model',
  fields:[
    { id: 'id' }
  ],
  belongsTo: 'Shop.model.ProductModel'
});

Ext.define('Shop.model.ProductModel', {
  extend:'Ext.data.Model',
  fields:[
    {name: 'name', type: 'string', defaultValue: 'Unknown'},
    {name: 'catalog', type: 'string'}

  ],
  hasMany: [
    {model: 'ProductImageModel', name: 'images'}
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
