(function($) {

function FlotPlugin(placeholder, options_) {

  var config = {
    placeholderPreviewGraph:'',
    placeholderSum:{
      from: '',
      to:'',
      ulContainer:''
    },
    unSelect: '',
    overview: '',
    listPlaceholder:'',
    dataUrl: '',
    formatter: function(value){ return value; },
    defaultIndexes: [],
    flotOptions: {
      lines: { show: true },
      points: { show: true },
      series: { lines: { show: true } },
      xaxis: {
        mode: "time",
        minTickSize: [1, "day"],
        min: null,
        max: null,
        monthNames: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек']
      },
      selection: { mode: "x" },
      grid: { markings: _weekendAreas, hoverable: true, clickable: true }
    },
    overviewOptions:{
      series: {
        lines: { show: true, lineWidth: 1 },
        shadowSize: 0
      },
      legend: { show: false },
      xaxis: { ticks: [], mode: "time"},
      yaxis: { ticks: [], min: 0, autoscaleMargin: 0.1 },
      selection: { mode: "x" }
    }
  };

  var data = [];

  var activeIndexes = [];

  var cache = [];

  var overview;

  var plot;

  var flotPlaceHolder = placeholder;

  var flotPlugin  = this;

  flotPlugin.loadIndex = function(indexes){ _loadIndex(indexes); }
  flotPlugin.unselect = function(){ _unselect(); }

  _init(options_);

  function _init(options){
    if (options){ $.extend(config, options); }
    _loadIndex(config.defaultIndexes);
    _initTooltip();
    _initSelectors();
    _initCheckBoxes();
    _initUnSelect();
  }

  function _initSelectors(){

    flotPlaceHolder.bind("plotselected", function (event, ranges) {

      var difference = ranges.xaxis.to - ranges.xaxis.from;

      _setSum(ranges.xaxis.from, ranges.xaxis.to);

      if(difference<264732733){
        _redrawChart(true);
        return true;
      }

      _setOptions({xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to }});
      overview.setSelection(ranges, true);
    });

    config.overview.bind("plotselected", function (event, ranges) {
        plot.setSelection(ranges);
    });
  }

  function _setSum(from, to){

    if(!from || !to){
      from = config.flotOptions.xaxis.min;
      to = config.flotOptions.xaxis.max;
    }

    if(!from || !to){
      var ax = plot.getAxes();
      from = ax.xaxis.datamin;
      to = ax.xaxis.datamax;
    }

    var dateFrom = new Date(from);
    var dateTo = new Date(to);

    config.placeholderSum.ulContainer.text('');

    var i = data.length; while (i--) {
      var sum = 0;
      var j = data[i].data.length; while (j--) {
        if(data[i].data[j][0]>=from && data[i].data[j][0]<=to){
          sum += data[i].data[j][1];
        }
      }
      config.placeholderSum.ulContainer.append('<li><b>'+data[i].label+'</b>: '+config.formatter(sum)+'</li>');
    }

    config.placeholderSum.from.text(dateFrom.getDate()+"."+(dateFrom.getMonth()+1)+"."+dateFrom.getFullYear());
    config.placeholderSum.to.text(dateTo.getDate()+"."+(dateTo.getMonth()+1)+"."+dateTo.getFullYear());
  }

  function _initTooltip(){
    var previousPoint = null;

    flotPlaceHolder.bind("plothover", function (event, pos, item) {
      if (item) {
        if (previousPoint != item.datapoint[1]) {
          var y = item.datapoint[1];
          previousPoint = y;
          $("#tooltip").remove();
          _showTooltip(item.pageX, item.pageY, item.series.label + ': ' + config.formatter(y));
        }
      }else {
        $("#tooltip").remove();
        previousPoint = null;
      }
    });

    flotPlaceHolder.mouseleave(function(){
      $("#tooltip").remove();
      previousPoint = null;
    });

  }

  function _initCheckBoxes(){
    config.listPlaceholder.change(function() {
      _loadCheckBoxes();
    });
  }

  function _initUnSelect(){
    config.unSelect.click(function() {
      _unselect();
    });
  }

  function _loadCheckBoxes(){

    var indexes = [];

    config.listPlaceholder.find("input:checked").each(function () {
      var key = $(this).attr("name");
      if (key){
        indexes.push(key);
      }
    });

    if(indexes.length<1){
      alert('Нельзя отключить все индексы, хотя бы один должен быть включён!');
      _setCheckBoxes();
      return;
    }

    _loadIndex(indexes);
  }

  function _setCheckBoxes(){
    config.listPlaceholder.find("input").removeAttr('checked', 'checked');
    for(var key in activeIndexes){
      config.listPlaceholder.find("input[name="+activeIndexes[key]+"]").attr('checked', 'checked');
    }
  }

  function _weekendAreas(axes) {
    var markings = [];
    var d = new Date(axes.xaxis.min);

    d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7));
    d.setUTCSeconds(0);
    d.setUTCMinutes(0);
    d.setUTCHours(0);
    var i = d.getTime();
    do {
      markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
      i += 7 * 24 * 60 * 60 * 1000;
    } while (i < axes.xaxis.max);

    return markings;
  }

  function _loadIndex(indexes){

    data = [];

    if(!indexes || indexes === undefined || indexes[0] === undefined){
      _redrawChart(false);
      _setSum(false,false);
      return;
    }
    
    indexes = _getDataFromCache(indexes);

    if(!indexes || indexes === undefined || indexes[0] === undefined){
      _redrawChart(false);
      _setSum(false,false);
      return;
    }

    $.ajax({
      type: "GET",
      url: config.dataUrl,
      dataType: "json",
      data: {indexes:indexes },
      complete: function(XMLHttpRequest){
        _setData($.parseJSON(XMLHttpRequest.responseText));
      },
      success: function(response) {
        if(response.error){
          alert('Server requst error: ' + response.error);
          $.error('Error on load index: ' + response.error);
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        alert('Error on load index: ' + errorThrown);
        $.error('Error on load index: ' + errorThrown);
      }
    });
  }

  function _setData(datasets){

    for(var key in datasets){
      data.push(datasets[key]);
      activeIndexes.push(key);
    }

    _addToCache(datasets);
    _redrawChart(false);
    _setSum(false,false);
  }

  function _addToCache(data){
    $.extend(cache, data);
  }

  function _getDataFromCache(indexes){

    data = [];
    activeIndexes = [];
    var needIndexes = [];

    for(var key in indexes){
      if(cache[indexes[key]]){
        data.push(cache[indexes[key]]);
        activeIndexes.push(indexes[key]);
      }else{
        needIndexes.push(indexes[key]);
      }
    }

    return needIndexes;
  }

  function _setOptions(options){
    config.flotOptions = $.extend(true, {}, config.flotOptions, options);
    _redrawChart(false);
     _setSum(false,false);
  }

  function _unselect(){
    _setOptions({xaxis: { min: null, max: null }});
    _setSum(false,false);
  }

  function _showTooltip(x, y, contents) {
    $('<div id="tooltip">' +contents + '</div>').css( {
      position: 'absolute',
      display: 'none',
      top: y + 5,
      left: x + 5,
      border: '1px solid #fdd',
      padding: '2px',
      'background-color': '#fee',
      opacity: 0.90
    }).appendTo("body").fadeIn(200);
   // setTimeout(function(){ $('#tooltip').remove();  },15000);     
  }

  function _redrawChart(notRedrawOverview){
    _setCheckBoxes();
    plot = $.plot(flotPlaceHolder, data, config.flotOptions);
    if(!notRedrawOverview){ _redrawOverview(); }
  }

  function _redrawOverview(){
    overview = $.plot(config.overview, data, config.overviewOptions);
  }

}

  $.fn.flotPlugin = function(options) {
     var flotPlugin = new FlotPlugin(this,options);

    return flotPlugin;
  };

})(jQuery);