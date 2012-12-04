/**
 * nmcDropDown plugin - v1.0.5
 * Author: Eli Van Zoeren
 * Copyright (c) 2009 New Media Campaigns
 * http://www.newmediacampaigns.com
 * ---------------------------------------------
 * Usage: $('#nav').nmcDropDown({[options]});
 * 
 * See below for configuration options. If you
 * don't pass in any options, the plugin will
 * Use reasonable defaults.
 *
 * Dependancy: jQuery 1.2.6+
 * Optional depenancy: hoverIntent plugin
 *   http://cherne.net/brian/resources/jquery.hoverIntent.html  
 **/
(function($) {

    $.fn.nmcDropDown = function(options) {
	
        // build main options before element iteration
        var opts = $.extend({}, $.fn.nmcDropDown.defaults, options);

        // iterate each matched element
        return this.each(function() {
            var menu = $(this);
            submenus = menu.children('li:has('+opts.submenu_selector+')');
            
            if (opts.fix_IE) {
                // Fix IE 6+7 z-index bug
                menu.css('z-index', 51)
                    .parents().each(function(i) {
                        if ($(this).css('position') == 'relative') {
                            $(this).css('z-index', (i + 52));
                        }
                    });
                submenus.children(opts.submenu_selector).css('z-index', 50);
            }
			
            // Function that is called to show the submenu
            over = function(e) {
                $(this)
                		.addClass(opts.active_class)
                    .children(opts.submenu_selector).animate(opts.show, opts.show_speed);
                return false;
            }
			
            // Function that is called to hide the submenu
            out = function(e) {
            		$(this)
            				.removeClass(opts.active_class)
                    .children(opts.submenu_selector).animate(opts.hide, opts.hide_speed);
                return false;
            }
			
            // Show and hide the sub-menus
            if (opts.trigger == 'click') {
                submenus
                    .click(function(event) {
                    	if ($(event.target).parent().get(0) == this) {
                    		event.preventDefault();
                    		$(this).hasClass(opts.active_class) ? out(this) : over(this);
                    	}
                    })
                    .children(opts.submenu_selector).hide();
            } else if ($().hoverIntent) {
                submenus
                    .hoverIntent({
                        interval: opts.show_delay, 
                        over: over, 
                        timeout: opts.hide_delay, 
                        out: out
                    }).children(opts.submenu_selector).hide();
            } else {
                submenus
                    .hover(over, out)
                    .children(opts.submenu_selector).hide();
            }
        });
    };

    // Default options
    $.fn.nmcDropDown.defaults = {
        trigger: 'hover',           // Event to show and hide sub-menu - hover or click
        active_class: 'open',       // Class to give open menu items
        submenu_selector: 'ul',     // The element immediately below the <li> containing the sub-menu
        show: {opacity: 'show'},    // Effect(s) to use when showing the sub-menu
        show_speed: 180,            // Speed of the show transition
        show_delay: 50,             // Delay before the sub-menu is show (requires HoverIntent)
        hide: {opacity: 'hide'},    // Effect(s) to use when hiding the sub-menu
        hide_speed: 300,            // Speed of the hide transition
        hide_delay: 250,            // Delay before the sub-menu is hidden (requires HoverIntent)
        fix_IE: true                // IE 6 and 7 have problems with z-indexes. This tries to fix them
    };

})(jQuery);

/**
* hoverIntent is similar to jQuery's built-in "hover" function except that
* instead of firing the onMouseOver event immediately, hoverIntent checks
* to see if the user's mouse has slowed down (beneath the sensitivity
* threshold) before firing the onMouseOver event.
*
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
*
* hoverIntent is currently available for use in all personal or commercial
* projects under both MIT and GPL licenses. This means that you can choose
* the license that best suits your project, and use it accordingly.
*
* // basic usage (just like .hover) receives onMouseOver and onMouseOut functions
* $("ul li").hoverIntent( showNav , hideNav );
*
* // advanced usage receives configuration object only
* $("ul li").hoverIntent({
*	sensitivity: 7, // number = sensitivity threshold (must be 1 or higher)
*	interval: 100,   // number = milliseconds of polling interval
*	over: showNav,  // function = onMouseOver callback (required)
*	timeout: 0,   // number = milliseconds delay before onMouseOut function call
*	out: hideNav    // function = onMouseOut callback (required)
* });
*
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($) {
	$.fn.hoverIntent = function(f,g) {
		// default configuration options
		var cfg = {
			sensitivity: 7,
			interval: 100,
			timeout: 0
		};
		// override configuration options with user supplied object
		cfg = $.extend(cfg, g ? { over: f, out: g } : f );

		// instantiate variables
		// cX, cY = current X and Y position of mouse, updated by mousemove event
		// pX, pY = previous X and Y position of mouse, set by mouseover and polling interval
		var cX, cY, pX, pY;

		// A private function for getting mouse position
		var track = function(ev) {
			cX = ev.pageX;
			cY = ev.pageY;
		};

		// A private function for comparing current and previous mouse position
		var compare = function(ev,ob) {
			ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
			// compare mouse positions to see if they've crossed the threshold
			if ( ( Math.abs(pX-cX) + Math.abs(pY-cY) ) < cfg.sensitivity ) {
				$(ob).unbind("mousemove",track);
				// set hoverIntent state to true (so mouseOut can be called)
				ob.hoverIntent_s = 1;
				return cfg.over.apply(ob,[ev]);
			} else {
				// set previous coordinates for next time
				pX = cX; pY = cY;
				// use self-calling timeout, guarantees intervals are spaced out properly (avoids JavaScript timer bugs)
				ob.hoverIntent_t = setTimeout( function(){compare(ev, ob);} , cfg.interval );
			}
		};

		// A private function for delaying the mouseOut function
		var delay = function(ev,ob) {
			ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
			ob.hoverIntent_s = 0;
			return cfg.out.apply(ob,[ev]);
		};

		// A private function for handling mouse 'hovering'
		var handleHover = function(e) {
			// copy objects to be passed into t (required for event object to be passed in IE)
			var ev = jQuery.extend({},e);
			var ob = this;

			// cancel hoverIntent timer if it exists
			if (ob.hoverIntent_t) { ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t); }

			// if e.type == "mouseenter"
			if (e.type == "mouseenter") {
				// set "previous" X and Y position based on initial entry point
				pX = ev.pageX; pY = ev.pageY;
				// update "current" X and Y position based on mousemove
				$(ob).bind("mousemove",track);
				// start polling interval (self-calling timeout) to compare mouse coordinates over time
				if (ob.hoverIntent_s != 1) { ob.hoverIntent_t = setTimeout( function(){compare(ev,ob);} , cfg.interval );}

			// else e.type == "mouseleave"
			} else {
				// unbind expensive mousemove event
				$(ob).unbind("mousemove",track);
				// if hoverIntent state is true, then call the mouseOut function after the specified delay
				if (ob.hoverIntent_s == 1) { ob.hoverIntent_t = setTimeout( function(){delay(ev,ob);} , cfg.timeout );}
			}
		};

		// bind the function to the two event listeners
		return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover);
	};
})(jQuery);