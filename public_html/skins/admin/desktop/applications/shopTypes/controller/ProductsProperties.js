Ext.define('ShopTypes.controller.ProductsProperties', {
  extend:'Core.lib.Controller',
  views:['ShopTypes.view.PropertiesGrid', 'ShopTypes.view.ProductsTree', 'ShopTypes.view.Main'],
  requires:['ShopTypes.store.ProductPropertyStore'],
  refs:[
    { ref:'window', selector:'shopTypesWindow' },
    { ref:'tree', selector:'shopTypesWindow > ProductsTree'},
    { ref:'grid', selector:'PropertiesGrid'},
    { ref:'attrEdit', selector:'PropertiesGrid button[action=edit-attribute]'}
  ],
  init:function (){

    var me = this;

    me.control({

      'PropertiesGrid button[action=add-property]':{
        click:function (btn){
          var grid = me.getGrid(), storage = grid.getStore(), edit = grid.getPlugin('rowediting');

          var record = Ext.ModelManager.create({
            name:'New property',
            type:'string',
            nodeId:me.getSelectedNodeId()
          }, 'ShopTypes.model.ProductPropertyModel');

          edit.cancelEdit();
          storage.insert(0, record);
          edit.startEdit(0, 0);

          //console.log(index);
        }
      },

      'PropertiesGrid button[action=remove-property]':{
        click:function (btn){
          var grid = btn.up('window').down('grid');

          if(!grid.getSelectionModel().hasSelection()) {
            Ext.Msg.alert('Ошибка', 'Вы должны выбрать хотя бы одну запись.');
            return;
          }

          Ext.Msg.confirm('Удалить свойство', 'Вы действительно хотите удалить свойство?', function (button){
            if(button === 'yes') {
              grid.getStore().remove(me.getSelectedRecord());
            }
          });
        }
      },

      'PropertiesGrid button[action=edit-attribute]':{
        click:function (btn){

          var grid = btn.up('window').down('grid');

          if(!grid.getSelectionModel().hasSelection()) {
            Ext.Msg.alert('Ошибка', 'Вы должны выбрать хотя бы одну запись.');
            return;
          }

          me.editAttribute();
        }
      },

      'ProductsTree':{
        /**
         * @param rowModel Ext.selection.RowModel
         * @param record Ext.data.Model record
         */
        select:function (rowModel, record){

          var selected = rowModel.getSelection()[0];

          if(selected.isLeaf()) {
            me.getGrid().show();
            me.loadProperties(selected.getId());
          } else {
            me.getGrid().hide();
          }
        },

        deselect:function (){
          me.getGrid().hide();
        }
      },

      'PropertiesGrid':{
        selectionchange: function (row, model){

          model = model[0];

          if(!model) {
            me.getAttrEdit().hide();
            return;
          }

          if(model.get('type') == 'list' || model.get('type') == 'number') {
            me.getAttrEdit().show();
          } else {
            me.getAttrEdit().hide();
          }
        }
      },
      'ProductsAttributeNumber button[action=save]':{
        click:function (btn){

          var form = btn.up('form').getForm();
          me.saveAttribute(form.getValues());
          btn.up('window').close();
        }
      },

      'ProductsAttributeList button[action=save]':{
        click:function (btn){

          var array = [];
          var store = btn.up('grid').getStore();

          store.each(function(record){
            array.push(record.get('name'));
          });

          me.saveAttribute({list: array});
          store.removeAll();
          btn.up('window').close();
        }
      },

      'ProductsAttributeList':{
        show:function(window){
          var store = window.down('grid').getStore();

          store.on('load', function(){
            var data = me.getSelectedRecord().get('attribute').list || [];

            store.removeAll();
            Ext.each(data, function(name){
              store.add({name: name});
            });
          });

          store.load();
        }
      }

    });

  },

  /**
   * Logic
   */
  getSelectedNodeId:function (){

    return this.getSelectedNode().getId();
  },
  getSelectedNode:function (){

    return this.getTree().getSelectionModel().getSelection()[0];
  },

  getSelectedRecord:function (){

    return this.getGrid().getSelectionModel().getSelection()[0];
  },

  loadProperties:function (id){

    var me = this, store = me.getGrid().getStore();

    store.proxy.extraParams = { nodeId:id };
    store.load();
  },

  editAttribute: function(){

    var me = this, nodeType = me.getSelectedRecord().get('type'), window;

    if(nodeType == 'number'){
      window = Ext.create('ShopTypes.view.ProductsAttributeNumber');
      var form = window.down('form').getForm();
      form.setValues(me.getSelectedRecord().get('attribute'));
    }

    if(nodeType == 'list'){
      window = Ext.create('ShopTypes.view.ProductsAttributeList');
    }

    window.show();
  },

  saveAttribute: function(value){

    this.getSelectedRecord().set('attribute', value);
  }
});