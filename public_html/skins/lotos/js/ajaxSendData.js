(function($) {

  function AjaxSendData(placeholder, options_) {

    var config = {
      url: '',
      dataType: 'json',
      type: 'POST',
      onSuccess:function(data){},
      onError:function(message){},
      onStartSend: function(){},
      onGetResult: function(){}
    };

    var data = {};

    init(options_);

    function init(options){
      if (options){ $.extend(config, options); }

      if(placeholder){
        getDataFromForm();
      }
      
      sendAjaxQuery();
    }
   
    function getDataFromForm(){
       data = $(placeholder).serializeArray();
    }

    function sendAjaxQuery(){

      config.onStartSend();

      $.ajax({
        url:  config.url,
        dataType: config.dataType,
        data: data,
        type: config.type,
        cache: false,
        timeout: 30000,
        success: success,
        error:   error
      });

    }

    function success(data, textStatus){
      config.onGetResult();
      if(data.error){
        config.onError(data.error);
        return;
      }
      config.onSuccess(data);
    }

    function error(XMLHttpRequest, textStatus, errorThrown){
      config.onGetResult();
      config.onError(errorThrown);
    }

  }

  $.fn.ajaxSendData = function(options) {
    var ajaxSendData = new AjaxSendData(this,options);

    return ajaxSendData;
  }

  $.ajaxSendData = function (options){
     var ajaxSendData = new AjaxSendData(null,options);
     return ajaxSendData;
  }

})(jQuery);
