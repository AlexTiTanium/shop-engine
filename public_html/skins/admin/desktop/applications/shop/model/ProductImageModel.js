Ext.define('Shop.model.ProductImageModel', {
  extend:'Ext.data.Model',
  fields:[
    { name: 'id',   type: 'string'},
    { name: 'time', type: 'date', dateFormat: 'timestamp'},
    { name: 'data'}
  ]
});