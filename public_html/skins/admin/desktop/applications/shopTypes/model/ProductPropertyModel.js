Ext.define('ShopTypes.model.ProductPropertyModel', {
  extend:'Ext.data.Model',
  fields:[
    {name:'id',    type:'string' },
    {name:'name',  type:'string' },
    {name:'type',  type:'string' },
    {name:'nodeId', type: 'string'},
    {name:'attribute'}
  ],
  validations:[

  ]
});