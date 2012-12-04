Ext.define('ShopTypes.store.ProductsTreeStore', {
  extend:'Ext.data.TreeStore',
  fields : ['name','index'],
  storeId:'productsTreeStore',
  autoSync: true,
  remoteSort: true,
  proxy:{
    type:'ajax',
    api:{
      read:'/admin/shop/shopTypesTree.json',
      create:'/admin/shop/shopTypesTree/create.json',
      update:'/admin/shop/shopTypesTree/update.json',
      destroy:'/admin/shop/shopTypesTree/destroy.json'
    }
  }
});