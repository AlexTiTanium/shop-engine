(function ($){

  var methods = {

    select: function (){

      var me = this;
      var qs = $.QS();
      var name = me.attr('name');

      me.change(function (){
        qs.add(name, me.val());
        document.location.search = qs.toString();
      });
    },

    checkboxFilter: function (){

      var me = this;
      var qs = $.QS();

      me.change(function (e){

        var filtersOn = me.filter(':checked');
        qs.setFilter(filtersOn);

        document.location.search = qs.toString();
      });
    }
  };

  $.fn.inputsCollection = function (method){
    return methods[method].apply(this);
  }

  $.QS = function (){

    function QS(){
      this.qs = {};
      var s = location.search.replace(/^\?|#.*$/g, '');
      if(s) {
        var qsParts = s.split('&');
        var i, nv;
        for(i = 0; i < qsParts.length; i++) {
          nv = qsParts[i].split('=');
          this.qs[nv[0]] = nv[1];
        }
      }
    }

    QS.prototype.getParam = function(key){
      return this.qs[key];
    }

    QS.prototype.setFilter = function (filters){

      var me = this;
      var filterArray = [];
      var filterBuilder = [];

      console.log(filters.length);
      if(!filters.length){
        console.log('empty');
        me.remove('filters');
        return;
      }

      filters.each(function(index, element){

        var filter = $(element).attr('name');
        filter = me.explode('::', filter);
        var filterKey = filter.shift();

        if(filterArray[filterKey]){
          filterArray[filterKey].push(filter);
        }else{
          filterArray[filterKey] = filter;
        }

      });

      for(var name in filterArray){
        var filterItem = name + '::' + me.implode('::', filterArray[name]);
        filterBuilder.push(filterItem);
      }

      me.add('filters', me.implode(';', filterBuilder));
    }

    QS.prototype.add = function (name, value){
      if(arguments.length == 1 && arguments[0].constructor == Object) {
        this.addMany(arguments[0]);
        return;
      }
      this.qs[name] = value;
    }


    QS.prototype.implode = function(glue, pieces ) {
    	return ( ( pieces instanceof Array ) ? pieces.join ( glue ) : pieces );
    }


    QS.prototype.explode = function( delimiter, string ) {

    	var emptyArray = { 0: '' };

    	if ( arguments.length != 2
    		|| typeof arguments[0] == 'undefined'
    		|| typeof arguments[1] == 'undefined' )
    	{
    		return null;
    	}

    	if ( delimiter === ''
    		|| delimiter === false
    		|| delimiter === null )
    	{
    		return false;
    	}

    	if ( typeof delimiter == 'function'
    		|| typeof delimiter == 'object'
    		|| typeof string == 'function'
    		|| typeof string == 'object' )
    	{
    		return emptyArray;
    	}

    	if ( delimiter === true ) {
    		delimiter = '1';
    	}

    	return string.toString().split ( delimiter.toString() );
    }

    QS.prototype.addMany = function (newValues){
      for(nv in newValues) {
        this.qs[nv] = newValues[nv];
      }
    }

    QS.prototype.remove = function (name){
      if(arguments.length == 1 && arguments[0].constructor == Array) {
        this.removeMany(arguments[0]);
        return;
      }
      delete this.qs[name];
    }

    QS.prototype.removeMany = function (deleteNames){
      var i;
      for(i = 0; i < deleteNames.length; i++) {
        delete this.qs[deleteNames[i]];
      }
    }

    QS.prototype.getQueryString = function (){
      var nv, q = [];
      for(nv in this.qs) {
        q[q.length] = nv + '=' + this.qs[nv];
      }
      return q.join('&');
    }

    QS.prototype.toString = QS.prototype.getQueryString;

    return new QS;
  }

})(jQuery);
