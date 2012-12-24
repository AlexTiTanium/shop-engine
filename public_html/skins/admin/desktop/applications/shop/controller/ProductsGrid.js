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
          //me.editProduct(null);
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
          me.editProduct(null);
        }
      },
      'productsGrid button[action=edit-product]':{
        click:function (btn){
          var record = me.getSelectedRecord();
          me.editProduct(record.getId());
        }
      },
      'productsGrid button[action=remove-product]':{
        click:function (btn){

          var record = me.getSelectedRecord();

          Ext.Msg.confirm('Удалить продукт', 'Вы действительно хотите удалить продукт?', function(button) {
            if (button === 'yes') {
              me.removeProduct(record);
            }
          });
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

  removeProduct:function (product){

    var me = this, store = me.getGrid().getStore();

    store.remove(product);
  },

  editProduct:function (id){

    var me = this, window;

    window = Ext.create('Shop.view.ProductEditorWindow');

    if(id){
      window.editId = id;
    }

    window.show();
  }

});