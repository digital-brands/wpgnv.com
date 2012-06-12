// Add New Idea Handler
jQuery( document ).ready( function() {
	jQuery( '#add-idea' ).on( 'click', function() {
		jQuery( '.idea-form-row' ).fadeToggle();
	});
});

// Open/Close Handler - Also created cookie if it doesn't already exist
jQuery(document).ready( function() {
    var cookie = JSON.parse( jQuery.cookie( 'wpgnv' ) );
    if ( null == cookie ) {
        cookie = new Object();
        cookie.heroStatus = 'open';
		cookie.votesLeft = 3;
        jQuery.cookie( 'wpgnv', JSON.stringify( cookie ), { expires: 1 } );
    } else if ( 'closed' == cookie.heroStatus ) {
        jQuery( '.hero-section' ).hide();
		cookie.heroStatus = 'open';
		jQuery( '#open-close span' ).html( 'Open' );
    }

    jQuery( '#open-close' ).on( 'click', function( event ) {

        var cookie = JSON.parse( jQuery.cookie( 'wpgnv' ) );

        if ( 'open' == cookie.heroStatus) {
            cookie.heroStatus = 'closed';
        } else {
            cookie.heroStatus = 'open';
        }

        jQuery.cookie( 'wpgnv', JSON.stringify( cookie ), { expires: 1 } );

        jQuery( '.hero-section' ).slideToggle();
        var current = jQuery( '#open-close span' ).html();
        jQuery( '#open-close span' ).html( (  ( 'Close' == current ) ? 'Open' : 'Close') );
    });
});

// Voting jQuery
jQuery(document).ready(function() {

    jQuery( '#upvote, #downvote' ).on( 'click', function( event ) {

		// Get the number of Votes Left for this user.
		var cookie = JSON.parse( jQuery.cookie( 'wpgnv' ) );
		var votesLeft = cookie.votesLeft;
		if ( 0 >= votesLeft ) {
			alert("Sorry, you don't have any votes left for today.  Come back tomorrow if you would like to vote more.");
			return;
		}

		// Take a Vote off
		cookie.votesLeft = cookie.votesLeft - 1;
		jQuery.cookie( 'wpgnv', JSON.stringify( cookie ), { expires: 1 } );


		var postID = jQuery( event.target ).attr( 'postID' );
        var up_or_down = jQuery( event.target ).attr( 'id' );
    

        if ( up_or_down == 'upvote' ) {
            var value = 1;
        } else {
            var value = -1;
        }

        var data = {
            action: 'wpgnv_upvote',
            postID: postID,
            value: value
        };            
       
        jQuery.post( MyAjax.ajaxurl, data, function( response ) {
            var selector = '#total-' + postID;
            if ( response > 0 ) {
                var new_class='total positive';
            } else if ( response < 0 ) {
                var new_class='total negative';
            } else {
                var new_class='total neutral';
            }
            jQuery( selector ).fadeOut('fast', function() {
                jQuery( selector ).html( response ).fadeIn( 'fast' );
                jQuery( selector ).attr( 'class', new_class );
            });
            //alert( response );
            //jQuery( parent ).html( response );
        });
    });
});

/*!
 * jQuery Cookie Plugin
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function($) {
    $.cookie = function(key, value, options) {

        // key and at least value given, set cookie...
        if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value === null || value === undefined)) {
            options = $.extend({}, options);

            if (value === null || value === undefined) {
                options.expires = -1;
            }

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            value = String(value);

            return (document.cookie = [
                encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path    ? '; path=' + options.path : '',
                options.domain  ? '; domain=' + options.domain : '',
                options.secure  ? '; secure' : ''
            ].join(''));
        }

        // key and possibly options given, get cookie...
        options = value || {};
        var decode = options.raw ? function(s) { return s; } : decodeURIComponent;

        var pairs = document.cookie.split('; ');
        for (var i = 0, pair; pair = pairs[i] && pairs[i].split('='); i++) {
            if (decode(pair[0]) === key) return decode(pair[1] || ''); // IE saves cookies with empty string as "c; ", e.g. without "=" as opposed to EOMB, thus pair[1] may be undefined
        }
        return null;
    };
})(jQuery);

