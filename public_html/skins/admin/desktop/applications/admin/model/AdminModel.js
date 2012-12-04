Ext.define('Admin.model.AdminModel', {
  extend:'Ext.data.Model',
  fields:[
    {name:'id',     type:'string' },
    {name:'login',  type:'string' },
    {name:'email',  type:'string' },
    {name:'date',   type:'date', dateFormat: 'Y-m-d H:i:s' },
    {name:'enable', type:'boolean' },
    {name:'password', type:'string' }
  ],
  validations:[
    {type:'email',   field:'email'},
    {type:'length',  field:'login', min:4 }
  ]
});