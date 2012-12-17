Ext.define('Shop.controller.ProductsGrid', {
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
      'shopMainWindow':{
        show: function(){
          me.editProduct(null);
        }
      },
      'catalog':{
        select:function (rowModel, record){
          var selected = rowModel.getSelection()[0];
          if(selected.isLeaf()) {
            me.loadProperties(selected.getId());
          }
        }
      },
      'productsGrid button[action=add-product]':{
        click:function (btn){
          var id = me.getSelectedRecord();
          me.editProduct(id);
        }
      }
    });


  },

  /**
   * Logic
   */
  loadProperties:function (id){

    var me = this, store = me.getGrid().getStore();

    store.proxy.extraParams = { catalog:id };
    store.load();
  },

  getSelectedRecord:function (){
    return this.getGrid().getSelectionModel().getSelection()[0];
  },

  editProduct:function (id){

    var me = this, window;

    window = Ext.create('Shop.view.ProductEditorWindow');

    window.show();
  }

});