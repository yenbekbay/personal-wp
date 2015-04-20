jQuery(document).ready(function($) {

	/*-------------------------------------------------------------------------*/
	/*	MENU
	/*-------------------------------------------------------------------------*/

	/* Mobile class */
	function mobileClass() {		
		if($(window).width() <= 640) {
			$('body').addClass('mobile');
		} else {
			$('body').removeClass('mobile');
		}
	}
	mobileClass();
	
	// Resized the screen
	$(window).resize(function() {
		reloadPixels();
		mobileClass();
		mobileMenu();
		pageHeader();
	});
	
	/* Dropdown menus */
	$('#primary-nav ul').supersubs({
	        minWidth: 12,
	        maxWidth: 27,
	        extraWidth: 0 // set to 1 if lines turn over
	    }).superfish({
    		delay: 200,
    		animation: {opacity:'show', height:'show'},
    		speed: 'fast',
    		autoArrows: false,
    		dropShadows: false
	});
	
	/* Mobile menu */
	var mobileMenuClone = $('#primary-menu').clone().attr('id', 'mobile-menu'),
		logoClone = $('#logo').clone().attr('id', 'mobile-logo');
	function mobileMenu() {
		if($('body').hasClass('mobile')) {
			// Show the mobile menu, hide the main menu
			if(!$('#menu-dropdown').length) {
				// Add our button and cloned menu if they don't already exist
				$('<a id="menu-dropdown" href="#mobile-menu" />').prependTo('#header');
				logoClone.prependTo('#header');
				mobileMenuClone.insertAfter('#menu-dropdown').wrap('<div id="mobile-menu-wrap" />');
				menu_listener();
			}
		} else {
			mobileMenuClone.hide();
		}
	}
	mobileMenu();

	// Fire the event listener
	function menu_listener() {
		$('#menu-dropdown').click(function(e) {
			if($('body').hasClass('ie8')) {
				var mobileMenu = $('#mobile-menu');
				if(mobileMenu.css('display') === 'block') {
					mobileMenu.hide();
				} else {
					mobileMenu.css({
						'display' : 'block',
						'height' : 'auto',
						'z-index' : 999,
						'position' : 'absolute' 
					});
				}
			} else {
				$('#mobile-menu').stop().slideToggle(300);
			}
			e.preventDefault();
		});
	}

	if(!$('body').hasClass('ie8')) {    
		window.addEventListener( "orientationchange", function() {
			$('#primary-nav > ul').removeAttr('style');
		}, false );
	}
    
	/*-------------------------------------------------------------------------*/
	/*	PORTFOLIO
	/*-------------------------------------------------------------------------*/
	
	// Portfolio sort
	var filters = $('#portfolio-filters');
	if('ontouchstart' in document.documentElement) {
		$('#portfolio-filters > a').click(function() {
			$(this).parent().find('ul').stop(true,true).slideToggle(600, 'easeOutExpo');
			if(filters.hasClass('active')) {
				filters.removeClass('active');
			} else {	
				filters.addClass('active');				
			}			
		});	
	} else {
		filters.addClass('inactive');		
		filters.hover(function() {
			$(this).find('ul').stop(true,true).slideToggle(600, 'easeOutExpo');
		});
	}

	// Portfolio items classes
	$('.item:not(.isotope-hidden)').addClass('isotope-active');
	function itemClasses() {
		$('.isotope-active').each(function(index) {
			if(index%4 == 3) {
				$(this).addClass('right-edge');
				$(this).addClass('mobile-right-edge');	
			} else if(index%4 == 1) {
				$(this).addClass('center');
				$(this).addClass('mobile-right-edge');	
			} else if(index%4 == 2) {
				$(this).addClass('center');
			} 
		});
	}
	itemClasses();

	$('#portfolio-filters ul li a').click(function() {
		$('.item').removeClass('right-edge'),
		$('.item').removeClass('mobile-right-edge'),
		$('.item').removeClass('center'),
		$('.item').removeClass('isotope-active'),
		$('.item:not(.isotope-hidden)').addClass('isotope-active');		
	    itemClasses();
    });
	
	// Portfolio sidebar follow	
	var sidebarFollow = $('.single-portfolio #sidebar').attr('data-follow-on-scroll');	
	if( $('body.single-portfolio').length > 0 && sidebarFollow == 1 && !$('body').hasClass('mobile') && parseInt($('#sidebar').height()) + 50 <= parseInt($('#post-area').height())) {		
		$('#sidebar').addClass('fixed-sidebar');		 
		var $footer = '#footer-outer';
		if( $('#call-to-action').length > 0 ) $footer = '#call-to-action';		 
		$('#sidebar').stickyMojo({footerID: $footer, contentID: '#post-area'});		 
	}	

	/* Add hover class to portfolio item link */
	$('.portfolio-items .work-item a').hover(function() {	
		$(this).parent().parent().children('.work-meta').children('a').addClass('hover');
	}, function() {	
		$('.work-meta a').removeClass('hover');
	});
	
	/*-------------------------------------------------------------------------*/
	/*	SHORTCODES
	/*-------------------------------------------------------------------------*/
		
	/* Tabbed */
	$('.tabbed ul li a').click(function(){
		var $id = $(this).attr('href');
		if(!$(this).hasClass('active-tab')){
			$('.tabbed ul li a').removeClass('active-tab');
			$(this).addClass('active-tab');
			
			$('.tabbed > div').hide();
			$('.tabbed > div'+$id).fadeIn(400);	
		}
		return false;
	});
	$('.tabbed ul li:first-child a').click();
	
	/* Toggle */
	$('.toggle h3 a').click(function(){
		$(this).parents('.toggle').find('> div').slideToggle(300);
		$(this).parents('.toggle').toggleClass('open');
		return false;
	});	


	/*-------------------------------------------------------------------------*/
	/*	PIXELATED SQUARES
	/*-------------------------------------------------------------------------*/

	function shadeColor(color, percent) {   
	    var f=parseInt(color.slice(1),16),t=percent<0?0:255,p=percent<0?percent*-1:percent,R=f>>16,G=f>>8&0x00FF,B=f&0x0000FF;
	    return "#"+(0x1000000+(Math.round((t-R)*p)+R)*0x10000+(Math.round((t-G)*p)+G)*0x100+(Math.round((t-B)*p)+B)).toString(16).slice(1);
	}

	function createShades(color) {
		var shades = new Array(50);
		for(var c = 0; c < shades.length; c++) {
			shades[c] = shadeColor(color, c/100);
		}
		return shades;
	}

	function shuffle(o) {
		for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
		return o;
	}

	function colorSquares(id) {
		var button_array = new Array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15),
			color_array = createShades($('#'+id).data('color'));
		for(var i = 0; i < 16; i++) {
			var	color = shuffle(color_array);
			$('#'+id).find('td').eq(button_array.shift()).stop().animate( {
					backgroundColor:'#'+color
				}, 500
			);
		}
	}

	$('.forty-forty').each(function() {
		colorSquares($(this).attr('id'));
	});

	var timer;
	function loopAnimation(id) {
		timer = setInterval(function() { 
			colorSquares(id);
		}, 100);
	}

	$('.forty-forty').hover(function() {
		loopAnimation($(this).attr('id'));
	}, function() {
		clearInterval(timer);
	});

	$('#menu-dropdown:not(.active)').live('click', function() {
		loopAnimation('mobile-logo');
		$(this).addClass('active');
	});

	$('#menu-dropdown.active').live('click', function() {
		clearInterval(timer);
		$(this).removeClass('active');
	});	

	function reloadPixels() {
		$('.forty-forty').each(function() {
			colorSquares($(this).attr('id'));
		});
	}
	
	/*-------------------------------------------------------------------------*/
	/*	MISC.
	/*-------------------------------------------------------------------------*/
	
	/* Checkmarks */
	$('ul.checks li').prepend('<span></span>');
		
	/* Page headers */
	var pageHeaderHeight = parseInt($('#page-header-bg').attr('data-height'));
	$('#page-header-bg').css('height', pageHeaderHeight);
	function pageHeader() {
		if(!$('body').hasClass('mobile')) {		
			$('#page-header-bg .container').css('top', 0);		
			var pageHeaderHeight = parseInt($('#page-header-bg').attr('data-height')),
				pageHeadingHeight = $('#page-header-bg .col').height(),
				filtersHeight = $('#page-header-bg #portfolio-filters').height();
			$('#page-header-bg .col').css('top', (pageHeaderHeight/2) - (pageHeadingHeight/2) - 5);		
			$('#page-header-bg #portfolio-filters').css('top', (pageHeaderHeight/2) - (filtersHeight/2));
		} else {
			$('#page-header-bg .col').css('top', 0);
			$('#page-header-bg #portfolio-filters').css('top', 0);
			var pageHeaderHeight = parseInt($('#page-header-bg').attr('data-height')),		
				pageHeadingHeight = $('#page-header-bg .col').height();
			if($('#page-header-bg #portfolio-filters').length > 0) {
				$('#page-header-bg .container').css('top', (pageHeaderHeight/2) - (pageHeadingHeight) - 50);
			} else {
				$('#page-header-bg .container').css('top', (pageHeaderHeight/2) - (pageHeadingHeight/2) - 45);	
			}
		}
		$('#page-header-bg').css('visibility', 'visible');
	}
	pageHeader();
	
	/* External embed */
	$('p iframe').wrap('<div class="iframe-embed"/>');

	/* Full screen mode on the iPhone */
	$("a[href*='http://']:not([href*='"+location.hostname+"']),[href*='https://']:not([href*='"+location.hostname+"']),[href*='//']:not([href*='"+location.hostname+"'])").addClass('external');
	var deviceAgent = navigator.userAgent.toLowerCase();
	if(deviceAgent.match(/(iphone|ipod|ipad)/)) {
		$('a:not(#menu-dropdown):not(#portfolio-filters a):not(.external)').click(function() {
		    window.location = $(this).attr('href');
		    return false;
		});
	}
});