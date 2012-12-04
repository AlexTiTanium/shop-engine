Ext.define('Core.plugin.RowEditing', {
  extend: 'Ext.grid.plugin.RowEditing',
  listeners:{
    validateedit:function (editor, e){

      var newModel = e.record.copy(); //copy the old model
      newModel.set(e.newValues); //set the values from the editing plugin form

      var errors = newModel.validate(); //validate the new data
      if(!errors.isValid()){
        editor.editor.form.markInvalid(errors); //the double "editor" is correct
        return false; //prevent the editing plugin from closing
      }

      return true;
    }
  }
});