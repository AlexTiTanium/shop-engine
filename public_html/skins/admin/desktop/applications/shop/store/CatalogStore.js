Ext.define('Shop.store.CatalogStore', {
  extend:'Ext.data.TreeStore',
  fields : ['name', 'index'],
  storeId:'catalogStore',
  autoSync:true,
  pageSize: 15,
  proxy:{
    type:'ajax',
    api:{
      read:'/admin/shop/shopCatalog.json',
      create:'/admin/shop/shopCatalog/create.json',
      update:'/admin/shop/shopCatalog/update.json',
      destroy:'/admin/shop/shopCatalog/destroy.json'
    }
  }
});