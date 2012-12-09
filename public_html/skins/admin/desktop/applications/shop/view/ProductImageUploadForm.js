Ext.define('Shop.view.ProductImageUploadForm', {
  extend:'Ext.window.Window',
  title:'Загрузить изобржение продукта',
  width:400,
  modal:true,
  border:false,
  items:[
    {
      xtype:'form',
      bodyPadding:10,
      items:[
        {
          xtype:'filefield',
          name:'image',
          fieldLabel:'Изображение продукта',
          msgTarget:'side',
          allowBlank:false,
          anchor:'100%',
          buttonText:'Выбрать'
        }
      ],
      buttons:[
        {
          text:'Загрузить',
          handler:function (btn){

            var form = this.up('form').getForm();

            if(form.isValid()) {

              form.submit({
                url:'photo-upload.php',
                waitMsg:'Uploading your photo...',
                success:function (fp, o){
                  console.log(o);
                  Ext.Msg.alert('Success', 'Your photo "' + o.result.file + '" has been uploaded.');
                }
              });

            } // form valid

          } // handler
        }
      ]
    }
  ]
});