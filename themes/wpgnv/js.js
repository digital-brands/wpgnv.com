// Add New Idea Handler - rfrankel
jQuery( document ).ready( function() {
	jQuery( '#add-idea' ).on( 'click', function() {
		jQuery( '.idea-form-row' ).fadeToggle();
	});
});

// Open/Close Handler - Also created cookie if it doesn't already exist - rfrankel
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

// Voting jQuery - rfrankel
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

/*! jQuery Cookie Plugin https://github.com/carhartl/jquery-cookie */
(function($){$.cookie=function(key,value,options){if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value === null || value === undefined)) {options = $.extend({}, options); if (value === null || value === undefined) { options.expires = -1;}if (typeof options.expires === 'number') {var days = options.expires, t = options.expires = new Date();t.setDate(t.getDate() + days);}value = String(value); return (document.cookie = [encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value), options.expires ? '; expires=' + options.expires.toUTCString() : '',options.path    ? '; path=' + options.path : '',options.domain  ? '; domain=' + options.domain : '', options.secure  ? '; secure' : '' ].join(''));} options = value || {}; var decode = options.raw ? function(s) { return s; } : decodeURIComponent; var pairs = document.cookie.split('; '); for (var i = 0, pair; pair = pairs[i] && pairs[i].split('='); i++) { if (decode(pair[0]) === key) return decode(pair[1] || ''); } return null;};})(jQuery);

/*! http://mths.be/placeholder v2.0.7 by @mathias */
;(function(f,h,$){var a='placeholder' in h.createElement('input'),d='placeholder' in h.createElement('textarea'),i=$.fn,c=$.valHooks,k,j;if(a&&d){j=i.placeholder=function(){return this};j.input=j.textarea=true}else{j=i.placeholder=function(){var l=this;l.filter((a?'textarea':':input')+'[placeholder]').not('.placeholder').bind({'focus.placeholder':b,'blur.placeholder':e}).data('placeholder-enabled',true).trigger('blur.placeholder');return l};j.input=a;j.textarea=d;k={get:function(m){var l=$(m);return l.data('placeholder-enabled')&&l.hasClass('placeholder')?'':m.value},set:function(m,n){var l=$(m);if(!l.data('placeholder-enabled')){return m.value=n}if(n==''){m.value=n;if(m!=h.activeElement){e.call(m)}}else{if(l.hasClass('placeholder')){b.call(m,true,n)||(m.value=n)}else{m.value=n}}return l}};a||(c.input=k);d||(c.textarea=k);$(function(){$(h).delegate('form','submit.placeholder',function(){var l=$('.placeholder',this).each(b);setTimeout(function(){l.each(e)},10)})});$(f).bind('beforeunload.placeholder',function(){$('.placeholder').each(function(){this.value=''})})}function g(m){var l={},n=/^jQuery\d+$/;$.each(m.attributes,function(p,o){if(o.specified&&!n.test(o.name)){l[o.name]=o.value}});return l}function b(m,n){var l=this,o=$(l);if(l.value==o.attr('placeholder')&&o.hasClass('placeholder')){if(o.data('placeholder-password')){o=o.hide().next().show().attr('id',o.removeAttr('id').data('placeholder-id'));if(m===true){return o[0].value=n}o.focus()}else{l.value='';o.removeClass('placeholder');l==h.activeElement&&l.select()}}}function e(){var q,l=this,p=$(l),m=p,o=this.id;if(l.value==''){if(l.type=='password'){if(!p.data('placeholder-textinput')){try{q=p.clone().attr({type:'text'})}catch(n){q=$('<input>').attr($.extend(g(this),{type:'text'}))}q.removeAttr('name').data({'placeholder-password':true,'placeholder-id':o}).bind('focus.placeholder',b);p.data({'placeholder-textinput':q,'placeholder-id':o}).before(q)}p=p.removeAttr('id').hide().prev().attr('id',o).show()}p.addClass('placeholder');p[0].value=p.attr('placeholder')}else{p.removeClass('placeholder')}}}(this,document,jQuery));jQuery( document ).ready(function(){jQuery('input, textarea').placeholder();});
