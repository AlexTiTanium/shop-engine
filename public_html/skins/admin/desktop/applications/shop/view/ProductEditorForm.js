Ext.define('Shop.view.ProductEditorForm', {
  extend:'Ext.form.Panel',
  alias:'widget.productEditorForm',
  border:false,
  fieldDefaults:{
    labelAlign:'top',
    msgTarget:'side'
  },

  layout: {
    type: 'vbox',
    align: 'stretch',
    pack: 'start'
  },

  bodyPadding:5,
  items:[
    {
      height: 140,
      xtype:'fieldset',
      title:'Общие свойства',
      defaultType:'textfield',
      layout:'anchor',
      defaults:{
        anchor:'100%'
      },
      items:[
        {
          xtype:'container',
          layout:'hbox',
          items:[

            {
              xtype:'image',
              width:100, height:100,
              padding:'0 10 0 0',
              src:Ext.imagePath + '/noimage.jpg'
            },

            {
              xtype:'container',
              flex:3,
              border:false,
              layout:'anchor',
              defaultType:'textfield',
              items:[
                {
                  fieldLabel:'Название товара',
                  name:'name',
                  anchor:'95%'
                },
                {
                  xtype:'combobox',
                  name: 'status',
                  fieldLabel:'Статус',
                  triggerAction:'all',
                  selectOnTab:true,
                  editable:false,
                  value:'active',
                  displayField:'text',
                  valueField:'value',
                  store:'ProductStatusStore',
                  anchor:'95%'
                }

              ]
            },
            {
              xtype:'container',
              flex:2,
              layout:'anchor',
              defaultType:'textfield',
              items:[
                {
                  xtype:'numericfield',
                  name:'price',
                  allowNegative:false,
                  currencySymbol:'Грн.',
                  decimalPrecision:2,
                  allowDecimals:true,
                  alwaysDisplayDecimals:true,
                  fieldLabel:'Цена',
                  anchor:'95%'
                },
                {
                  xtype:'textfield',
                  fieldLabel:'Артикул',
                  name:'marking',
                  anchor:'95%'
                }
              ]
            },
            {
              xtype:'container',
              flex:1,
              layout:'anchor',
              defaultType:'textfield',
              items:[
                {
                  xtype:'numberfield',
                  fieldLabel:'Кол.',
                  name:'count',
                  anchor:'95%'
                }
              ]
            }
          ]
        }
      ]
    },
    {
      flex:1,
      xtype:'tabpanel',
      plain:true,
      activeTab: 0,
      layout:'fit',
      defaults:{
        bodyPadding:10
      },
      items:[
        {
          title:'Описание товара',
          layout:'fit',
          defaults:{
            width:230
          },
          defaultType:'textfield',
          items:{
            xtype:'htmleditor',
            name:'description',
            enableFont:false
          }
        },
        {
          title:'Свойства',
          defaults:{
            width:230
          },
          defaultType:'textfield',

          items:[
            {
              fieldLabel:'Home',
              name:'home',
              value:'(888) 555-1212'
            },
            {
              fieldLabel:'Business',
              name:'business'
            },
            {
              fieldLabel:'Mobile',
              name:'mobile'
            },
            {
              fieldLabel:'Fax',
              name:'fax'
            }
          ]
        },
        {
          title:'Изображения товара',
          layout:'fit',
          bodyPadding:'0',
          items:[
            {
              xtype:'panel',
              autoScroll: true,
              items:[
                { xtype:'productImagesDataView' }
              ],
              border:false,
              bodyPadding:'5',
              dockedItems:{
                xtype:'toolbar',
                dock:'bottom',
                items:[
                  {
                    text:'Добавить',
                    iconCls:'ux-icon-add',
                    handler:function (){
                      var win = Ext.create('Shop.view.ProductImageUploadForm');
                      win.show();
                    }
                  }
                ]
              }
            }
          ]
        }
      ]
    }
  ]
});