Ext.define('Shop.model.ProductModel', {
  extend:'Ext.data.Model',
  fields:[
    {name: 'name', type: 'string', defaultValue: 'Unknown'},
    {name: 'catalog', type: 'string'},
    {name: 'status', type: 'string'},
    {name: 'price', type: 'float'},
    {name: 'marking', type: 'string'},
    {name: 'count', type: 'integer'},
    {name: 'description', type: 'string'}
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
