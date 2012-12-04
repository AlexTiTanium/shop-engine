(function($){

  function SelectController(placeholder, options_){

    var config = {
      ajaxAnimation:'',
      changeSelect: '',
      url: '',
      changeEvent: function(){}
    };

    init(options_);

    function init(options){
      if(options) {
        $.extend(config, options);
      }

      addOnChangeEvent();
    }

    function addOnChangeEvent(){
      placeholder.change(changedSelectEvent);
      config.changeSelect.change(function(){
        config.changeEvent();
      });
    }

    function startAnimation(){
      config.changeSelect.hide();
      config.ajaxAnimation.show();
    }

    function endAnimation(){
      config.changeSelect.show();
      config.ajaxAnimation.hide();
    }

    function changedSelectEvent(){
      config.changeEvent();
      var countryId = placeholder.val();

      $.ajaxSendData({
        url: config.url + '?id=' + countryId,
        onSuccess:function(data){
          setSelect(data);
        },
        onError:function(data){
          alert(data);
        },
        onStartSend:function(){ startAnimation(); },
        onGetResult:function(){ endAnimation(); }
      });

    }

    function setSelect(data){
      config.changeSelect.find('option').remove();
      $.each(data, function (index) {
        config.changeSelect.append(
          $('<option></option>').val(index).html(this.toString())
        );
      });
    }

  } // end class ------------------------------------------------------------


  $.fn.selectController = function(options){
    var selectController = new SelectController(this, options);

    return selectController;
  };

})(jQuery);