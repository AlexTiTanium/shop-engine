var MESSAGES = [];

$(function() {

  $('.state-may-hover').hover(function(){
    $(this).toggleClass('state-hover')
  });

  $('#search').watermark('Что вы хотите найти?');

  if($.pnotify){
    $.pnotify.defaults.delay -= 3000;
    while(MESSAGES.length > 0){
      $.pnotify(MESSAGES.shift());
    }
  }

  $('#menu-menu ul').nmcDropDown({
    trigger: 'hover',
    active_class: 'drop-down-menu-show',
    submenu_selector: 'div.drop-down-menu-content'
  });
});

function addMessage(title, text, type){
  MESSAGES.push({ history: false, title: title, text: text, type: type });
}

function goTo(url){
  document.location = url;
}