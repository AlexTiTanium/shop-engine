Ext.define('Shop.store.ProductStatusStore', {
  extend:'Ext.data.Store',
  storeId:'ProductStatusStore',
  fields: ['value', 'text'],
    data : [
      {"value":"active",       "text":"Активный"},
      {"value":"ends",         "text":"Заканчивается"},
      {"value":"new",          "text":"Новинка"},
      {"value":"promotion",    "text":"Акция"},
      {"value":"ended",        "text":"Нет в наличии"},
      {"value":"disable",      "text":"Не показывать"},
      {"value":"coming_soon",  "text":"Ожидается поставка"}
    ]
});