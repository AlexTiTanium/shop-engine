/**
 * Thomas Lauria - forms use model validations
 */
Ext.override(Ext.form.Panel, {
// hook in, on every Field by the mixin Ext.form.FieldAncestor onFieldAdded method
  onFieldAdded: function(field) {
    this.applyModelValidation(field);
    this.callOverridden(arguments);
  }
});

/**
 * @class Ext.form.FieldModelValidation
A mixin for {@link Ext.form.Panel} components, adding the validations of a given model to the fields of the panel
 * @docauthor Thomas Lauria <t.lauria@last-it.de>
 */
Ext.define('Ext.form.FieldModelValidation', {
    /**
     * @protected applies the validations of the model configured in the form panel to the given field
     * @param {Ext.form.field.Base} field The field to apply the validations
     */
    applyModelValidation: function(field) {
        var me = this,
        name = field.name,
        noValidations = Ext.isDefined(me.modelValidations) && me.modelValidations === false;
        if(noValidations || !name || ! me.model) {
          return;
        }
        if(Ext.isString(me.model)) {
          me.model = Ext.ModelManager.getModel(me.model);
        }
        if(!me.modelValidations) {
          me.initModelValidations();
        }
        if(me.modelValidations[name]) {
          me.setFieldValidationByModel(field, me.modelValidations[name]);
        }
    },

    /**
     * @protected reorganize the model validations and store them internally
     */
    initModelValidations: function() {
      var me = this,
      validations = me.model.prototype.validations,
      mv = {};
      if(!validations || validations.length == 0){
        me.modelValidations = false;
        return;
      }
      Ext.each(validations, function(v) {
        if(!mv[v.field]) {
          mv[v.field] = [];
        }
        mv[v.field].push(v);
      });
      me.modelValidations = mv;
    },


    /**
     * @protected helper function to apply the validations
     * @param {Ext.form.field.Base} field The field to apply the validations
     * @param {Array} validations An array with the validations matching the field.name
     */
    setFieldValidationByModel: function (field, validations) {
      Ext.each(validations, function(v){
        switch (v.type) {
          case 'presence':
            field.allowBlank = false;
            return;
          case 'length':
            var isNumeric = Ext.isDefined(field.maxValue) || Ext.isDefined(field.minValue),
            target = isNumeric ? 'Value' : 'Length';
            if(v.min) {
              field['min'+target] = v.min;
            }
            if(v.max) {
              field['max'+target] = v.max;
            }
            return;
          case 'email':
            field.vtype = 'email';
            return;
          case 'format':
            field.regex = v.matcher;
            return;
          //@todo
          //case 'inclusion':
          //case 'exclusion':
        }
      });
    }
});

Ext.form.Panel.mixin('FieldModelValidation', Ext.form.FieldModelValidation);