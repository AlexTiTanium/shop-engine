Ext.define('ShopTypes.controller.ProductsTree', {
  extend:'Core.lib.Controller',
  views: ['ShopTypes.view.ProductsTree', 'ShopTypes.view.Main'],
  requires: ['ShopTypes.store.ProductsTreeStore'],
  refs: [
    { ref: 'window', selector: 'shopTypesWindow' },
    { ref: 'tree', selector: 'shopTypesWindow > ProductsTree'}
  ],

  init:function (){

    var me = this;

    me.control({
      'ProductsTree button[action=folder-add]':{
        click: function(btn){
          me.addChild(false);
        }
      },
      'ProductsTree button[action=node-add]':{
        click: function(btn){
          me.addChild(true);
        }
      },
      'ProductsTree button[action=node-delete]':{
        click: function(btn){
          Ext.Msg.confirm('Удалить папку или продукт', 'Вы действительно хотите удалить папку или продукт?', function(button) {
            if (button === 'yes') {
              me.remove();
            }
          });
        }
      },
      'ProductsTree tool[action=tree-refresh]':{
        click: function(btn){
          me.getStore('productsTreeStore').reload();
        }
      }

    });

  },

  /**
   * Logic
   */
  addChild: function(leaf){

    var me = this, tree = me.getTree(), parentNode = null;

    var selection = tree.getSelectionModel().getSelection()[0];

    if(selection){
      if(selection.isLeaf() && selection.parentNode){
        parentNode = selection.parentNode;
      }else{
        parentNode = selection;
      }
    }

    if(!parentNode){
      parentNode = me.getStore('productsTreeStore').getRootNode();
    }

    parentNode.appendChild( { name: 'new', leaf: leaf });
    parentNode.expand();
  },

  remove: function(){

    var me = this, tree = me.getTree();
    var selection = tree.getSelectionModel().getSelection()[0];

    if(selection){
      selection.parentNode.removeChild(selection);
    }else{
      Ext.Msg.alert('Ошибка', 'Виберите ноду для удаления');
    }
  }

});