Ext.define('Shop.controller.Catalog', {
  extend:'Core.lib.Controller',
  views: ['Shop.view.Main', 'Shop.view.Catalog'],
  requires: ['Shop.store.CatalogStore'],
  refs: [
    { ref: 'window', selector: 'shopMainWindow' },
    { ref: 'tree', selector: 'shopMainWindow > catalog'}
  ],

  init:function (){

    var me = this;

    me.control({
      'catalog button[action=folder-add]':{
        click: function(btn){
          me.addChild(false);
        }
      },
      'catalog button[action=node-add]':{
        click: function(btn){
          me.addChild(true);
        }
      },
      'catalog button[action=node-delete]':{
        click: function(btn){
          Ext.Msg.confirm('Удалить папку', 'Вы действительно хотите удалить папку?', function(button) {
            if (button === 'yes') {
              me.remove();
            }
          });
        }
      },
      'catalog tool[action=tree-refresh]':{
        click: function(btn){
          me.getTree().getStore().reload();
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
      parentNode = me.getTree().getStore().getRootNode();
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