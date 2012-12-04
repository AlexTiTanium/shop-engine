Ext.define('Core.view.TaskBar', {
  extend:'Ext.toolbar.Toolbar',
  cls:'ux-core-TaskBar',
  alias:'widget.TaskBar',
  border: false,
  items:[],
  layout:{ overflowHandler:'Scroller' },

  Btn: {
    maxWidth: 100,
    minWidth: 22
  },

  initComponent:function (){
    var me = this;

    Ext.CoreApplication.TaskBar = this;

    me.callParent(arguments);
  },

  onTaskClick: function (btn){
    var me = this, win = btn.win;

    if(win.currentState == win.state.HIDDEN){
      me.showTask(win);
    }

    if(win.currentState == win.state.ACTIVE){
      win.minimizeWin();
      me.activeNextWindow();
    }

    if(win.currentState == win.state.NOT_ACTIVE){
      win.activateWin();
      win.toFront();
    }
  },

  /**
   *
   * @param {Core.lib.Window} win
   * @return {Ext.toolbar.Item}
   */
  getTask: function(win){
    var found, me = this;

    me.items.each(function (item){
     if(item.win === win) {
       return found = item;
     }
    });

    if(found) {
     return found;
    }

    Ext.Error.raise('Not found task');
    return false;
  },

  /**
   *
   * @param {Core.lib.Window} win
   */
  activeWindow: function(win){
    var me = this;

    win = Ext.WindowManager.getActive();
    if(!win.appName){ return; }

    //console.log(win.appName);
    me.items.each(function (item){
      if(item.win === win) {
        item.win.setStateWindow(item.win.state.ACTIVE);
        item.win.toFront(true);
      }else if(item.win.currentState == item.win.state.ACTIVE){
        item.win.setStateWindow(win.state.NOT_ACTIVE);
      }
    });
  },

  activeNextWindow: function(){
    var me = this;

    me.items.each(function (item){
      if(item.win.currentState === item.win.state.NOT_ACTIVE) {
        item.toggle(true);
        item.win.setStateWindow(item.win.state.ACTIVE);
        item.win.toFront(true);
        return true;
      }
    });
  },

  addTask:function (win){
    var me = this;

    var config = {
      iconCls: win.iconCls || 'ux-icon-application',
      enableToggle:true,
      toggleGroup:'all',
      width: me.Btn.maxWidth,
      margins:'0 0 0 0',
      text:Ext.util.Format.ellipsis(win.title, 15),
      listeners:{
        click: me.onTaskClick,
        scope: me
      },
      win: win,
      tip: null
    };

    var cmp = me.add(config);

    cmp.tip = Ext.create('Ext.tip.ToolTip', {
      target: cmp.getEl(),
      anchor: 'top',
      html: cmp.text
    });

    cmp.tip.disable();

    cmp.toggle(true);
  },

  removeTask: function (win){
    var task, me = this;

    task = me.getTask(win);

    if(task) {
      Ext.closeApplication(win.appName);
      return me.remove(task);
    }

    Ext.Error.raise('Not found task for remove');
    return false;
  },

  activeTask: function(win){
    var me = this, task;

    task = me.getTask(win);
    if(!task){ return; }

    task.toggle(true);
    me.activeWindow(win);
  },

  /**
   *
   * @param {Core.lib.Window} win
   * @this {Core.view.TaskBar}
   */
  hideTask: function(win){
    var me = this, task;

    task = me.getTask(win);
    if(!task){ return; }

    win.hide(task, function(){
      task.setWidth(me.Btn.minWidth);
      task.toggle(false);
      task.tip.enable();
      me.activeNextWindow();
    });

  },

  /**
   *
   * @param {Core.lib.Window} win
   * @this {Core.view.TaskBar}
   */
  showTask: function(win){
    var me = this, task;

    task = me.getTask(win);
    if(!task){ return; }

    win.show(task, function(){
      task.setWidth(me.Btn.maxWidth);
      task.toggle(true);
      win.setStateWindow(win.state.ACTIVE);
      task.tip.disable();
    });
  },

  /**
   *
   * @param {Core.lib.Window} win
   * @this {Core.view.TaskBar}
   */
  updateStateTask: function (win){
    var me = this, state, currentState;

    state = win.state;
    currentState = win.getStateWindow();

    switch(currentState){
      case state.ACTIVE:
        me.activeTask(win);
        break;
      case state.HIDDEN:
        me.hideTask(win);
        break;
      case state.NOT_ACTIVE:
        // do nothing
        break;
    }

  }

});