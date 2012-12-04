Ext.define('Shop.controller.ProductsPanel', {
  extend:'Core.lib.Controller',
  views:['Shop.view.Main'],
  requires:[],
  refs:[
    { ref:'window', selector:'shopMainWindow' },
    { ref:'tree', selector:'shopMainWindow > catalog'},
    { ref:'grid', selector:'productsGrid'}
  ],

  init:function (){

    var me = this;

    me.control({
      'catalog':{
        /**
         * @param rowModel Ext.selection.RowModel
         * @param record Ext.data.Model record
         */
        select:function (rowModel, record){

          var selected = rowModel.getSelection()[0];

          if(selected.isLeaf()) {
            me.getGrid().show();
          } else {
            me.getGrid().hide();
          }
        },

        deselect:function (){
          me.getGrid().hide();
        }
      }
    });

  }

  /**
   * Logic
   */


});