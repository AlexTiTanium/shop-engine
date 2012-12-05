Ext.define('Shop.controller.ProductEditorWindow', {
  extend:'Core.lib.Controller',
  views:['Shop.view.ProductEditorWindow'],
  requires:[],
  refs:[
    { ref:'window', selector:'productEditorWindow' }
  ],

  init:function (){

    var me = this;

    me.control({
      '#productImage':{
        afterrender: function(c){
          c.el.on('click', me.beginUploadProductImage);
        }
      }
    });

  },

  /**
   * Logic
   */
  beginUploadProductImage: function(){
    var fibasic = Ext.create('Ext.form.field.File', {
        hideLabel: true
    });

    var v = fibasic.getValue();
                console.log('Selected File', v && v != '' ? v : 'None');
  }


});