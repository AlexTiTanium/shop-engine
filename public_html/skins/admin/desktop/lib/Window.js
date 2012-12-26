Ext.define('Core.lib.Window', {extend:'Ext.window.Window',
  state:{
    ACTIVE:'active',
    HIDDEN:'hidden',
    NOT_ACTIVE:'notActive'
  },
  appName:null,
  iconCls:'ux-icon-application',

  layout:'fit',
  height:300,
  width:400,
  constrain:true,
  minimizable:true,
  maximizable:true,
  currentState:null,

  renderTo: Ext.select("div.ux-core-desktop-body").first(),

  taskBar:null,

  /**
   *
   */
  initComponent:function (){

    var me = this;

    me.taskBar = Ext.CoreApplication.TaskBar;
    me.currentState = me.state.ACTIVE;

    Ext.apply(me, {
      listeners:me.geListeners()
    });

    me.callParent(arguments);
  },

  /**
   *
   */
  geListeners:function (){
    var me = this;
    return {
      close:me.closeWin,
      boxready:me.createWin,
      minimize:me.minimizeWin,
      el:{
        blur:{
          fn:me.deactivateWin,
          scope:me
        },
        focus:{
          fn:me.activateWin,
          scope:me
        },
        click:{
          fn:me.activateWin,
          scope:me
        }
      }
    }
  },

  closeWin:function (){
    var me = this;

    if(!me.appName) {
      Ext.MessageBox.alert('Error', 'Try close app with name "null", maybe you need set appName window?');
      Ext.Error.raise('Try close app with name "null", maybe you need set appName window?');
      return false;
    }
    me.taskBar.activeNextWindow();
    return me.taskBar.removeTask(me);
  },

  createWin:function (){
    var me = this;

    me.taskBar.addTask(me);
  },

  minimizeWin:function (){
    var me = this;

    me.setStateWindow(me.state.HIDDEN);
    me.taskBar.updateStateTask(me);
  },

  activateWin:function (el){
    var me = this;

    me.setStateWindow(me.state.ACTIVE);
    me.taskBar.updateStateTask(me);
  },

  deactivateWin:function (el){
    var me = this;

    if(me.getStateWindow() == me.state.ACTIVE) {
      me.taskBar.updateStateTask(me);
    }
  },

  getStateWindow:function (){
    var me = this;
    return me.currentState;
  },

  setStateWindow:function (state){
    var me = this;
    //console.log(state);
    switch(state) {
      case me.state.ACTIVE:
        me.currentState = me.state.ACTIVE;
        break;
      case me.state.HIDDEN:
        me.currentState = me.state.HIDDEN;
        break;
      case me.state.NOT_ACTIVE:
        me.currentState = me.state.NOT_ACTIVE;
        break;
      default:
        Ext.Error.raise('Unknown state: ' + state);
    }
  }

});