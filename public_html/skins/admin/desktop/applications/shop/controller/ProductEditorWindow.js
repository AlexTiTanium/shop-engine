Ext.define('Shop.controller.ProductEditorWindow', {
  extend:'Core.lib.Controller',
  views:['Shop.view.ProductEditorWindow'],
  requires:[],
  refs:[
    { ref:'window', selector:'productEditorWindow' },
    { ref:'form',   selector:'productEditorForm' }
  ],

  init:function (){

    var me = this;

    me.control({
      'productEditorWindow':{
        show: function(c){
          me.loadDataToForm();
        }
      }
    });

  },

  /**
   * Logic
   */
  loadDataToForm: function(){

    var me = this, data,
      form = me.getForm().getForm(),
      model = me.getModel('Shop.model.ProductModel');

    model.load('somrecord', function(product){

      console.log(product);

    });

    //form.loadRecord(data);

  }


});