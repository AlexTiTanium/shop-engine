Ext.define('Core.view.Wallpaper', {
  extend:'Ext.Img',
  alias:'widget.Wallpaper',
  autoEl: {
    tag: 'div',
    cls: 'ux-widget-wallpaper'
  },

  src: IMAGES_PATH+'/desk.jpg',

  initComponent:function (){
    var me = this;
    me.callParent(arguments);
  }

});