Ext.define('Shop.controller.ProductEditorWindow', {
  extend:'Core.lib.Controller',
  views:['Shop.view.ProductEditorWindow'],
  requires:[],
  refs:[
    { ref:'window',         selector:'productEditorWindow' },
    { ref:'form',           selector:'productEditorForm' },
    { ref:'productImages',  selector:'productImagesDataView' },
    { ref:'catalog',        selector:'shopMainWindow > catalog' },
    { ref:'productDefaultImage', selector:'#productDefaultImage' }
  ],

  init:function (){

    var me = this;

    me.control({
      'productEditorWindow':{
        show:function (window){
          me.loadDataToForm(window);
        },
        beforeclose:function (window){

          var form = window.down('form'), me = this;

          if(me.isNeedSync(form)) {
            me.syncData(form, function (){
              window.close();
            });
            return false;
          }

          return true;
        }
      },
      'productEditorWindow tool[type=save]':{
        click:function (btn){
          var form = btn.up('window').down('form');
          me.syncData(form);
        }
      },

      'productEditorWindow button[action=default]':{
        click:function (btn){
          console.log(btn); me.setProductDefaultImage('dsds');
        }
      },

      'ProductImageUploadForm button[action=upload]': {
        click:function (btn){

          var form = btn.up('form');
          me.imageUpload(form);
        }
      }
    });

  },

  /**
   * Logic
   */
  loadDataToForm:function (window){

    var me = this, record, form = window.down('form');

    if(window.editId){

      Shop.model.ProductModel.load(window.editId, {
        success: function(product) {
          form.loadRecord(product);
          me.loadImagesStore(product);
        }
      });

    }else{

      record = Ext.create('Shop.model.ProductModel');

      record.set('catalog', me.getCurrentSelectedCatalogNode().getId());

      record.save({
        callback:function (){
          form.loadRecord(record);
          me.loadImagesStore(record.getId());
        }
      });
    }
  },

  setProductDefaultImage: function(url){

    var me = this;

    console.log(me.getProductDefaultImage());

  },

  loadImagesStore: function(product){

    var me = this, store = me.getProductImages().getStore();

    store.proxy.extraParams = { productId:  product.getId() };

    store.load(function(records, operation, success) {

      var image = store.getById(product.get('defaultImageId'));

      var storage = image.get('data').storage;
      var folder = image.get('data').storage;

      me.getProductDefaultImage().setSrc('/storage/'+storage+'/25/80x60-outbound:50eddbd13334b7_59603449.jpg');
      console.log(store.getById(id));
    });

    //me.setProductDefaultImage('dsds');
  },

  syncData: function (form, callback){

    var me = this;

    if(!form.getForm().isValid()) {
      Ext.msg('Ошибка сохранения', 'Данные не прошли валидацию, форма не будет сохранена');
      return;
    }

    form.setLoading('Сохранение данных');

    var record = form.getRecord(),
      values = form.getForm().getValues();

    record.set(values);

    record.save({
      callback:function (){
        form.setLoading(false);
        if(callback) {
          callback();
        }
      }
    });
  },

  getCurrentProductId: function(){
    return this.getForm().getRecord().getId();
  },

  getCurrentSelectedCatalogNode: function(){
    return this.getCatalog().getSelectionModel().getSelection()[0];
  },

  isNeedSync:function (form){

    var record = form.getRecord(), values = form.getForm().getValues();

    record.set(values);

    return !Ext.isEmptyObject(record.getChanges());
  },

  imageUpload: function (form){

    var me = this;

    if(!form.getForm().isValid()) {
      Ext.msg('Ошибка загрузки', 'Данные не прошли валидацию, форма не будет сохранена');
      return;
    }

    form.submit({
      url:'/admin/shop/productImages/upload.json',
      waitMsg:'Отправка файла на сервер...',
      params: {
          id: me.getCurrentProductId()
      },
      success:function (fp, o){
        form.up('window').close();
        me.getProductImages().getStore().reload();
      },
      failure:function (form, action){
        Ext.Msg.alert('Error', action.result.msg);
      }
    });

  }

});