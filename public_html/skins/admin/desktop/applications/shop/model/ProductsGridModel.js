Ext.define('Shop.model.ProductsGridModel', {
  extend:'Ext.data.Model',
  fields:[
    {name:'id', type:'string' },
    {name:'name',   type:'string' },
    {name:'price',  type:'float' },
    {name:'status', type:'string' }
  ],
  validations:[

  ]
});