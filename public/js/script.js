(function($,sr){

  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
  	var timeout;

  	return function debounced () {
  		var obj = this, args = arguments;
  		function delayed () {
  			if (!execAsap)
  				func.apply(obj, args);
  			timeout = null;
  		};

  		if (timeout)
  			clearTimeout(timeout);
  		else if (execAsap)
  			func.apply(obj, args);

  		timeout = setTimeout(delayed, threshold || 100);
  	};
  }
  // smartresize 
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');







(function(){

	$wrapper = $('#wrapper');
	$drawerRight = $('#drawer-right');

	///////////////////////////////
	// Set Home Slideshow Height
	///////////////////////////////

	function setHomeBannerHeight() {
		var windowHeight = jQuery(window).height();	
		jQuery('#header').height(windowHeight);
	}

	///////////////////////////////
	// Center Home Slideshow Text
	///////////////////////////////

	function centerHomeBannerText() {
		var bannerText = jQuery('#header > .center');
		var bannerTextTop = (jQuery('#header').actual('height')/2) - (jQuery('#header > .center').actual('height')/2) - 40;		
		bannerText.css('padding-top', bannerTextTop+'px');		
		bannerText.show();
	}



	///////////////////////////////
	// SlideNav
	///////////////////////////////

	function setSlideNav(){
		jQuery(".toggleDrawer").click(function(e){
			e.preventDefault();

			if($wrapper.css('marginLeft')=='0px'){
				$drawerRight.animate({marginRight : 0},200);
				$wrapper.animate({marginLeft : -300},200);
			}
			else{
				$drawerRight.animate({marginRight : -300},200);
				$wrapper.animate({marginLeft : 0},200);
			}
			
		})
	}

	function setHeaderBackground() {		
		var scrollTop = jQuery(window).scrollTop(); // our current vertical position from the top	
		
		if (scrollTop > 300 || jQuery(window).width() < 700) { 
			jQuery('#header .top').addClass('solid');
		} else {
			jQuery('#header .top').removeClass('solid');		
		}
	}




	///////////////////////////////
	// Initialize
	///////////////////////////////

	jQuery.noConflict();
	setHomeBannerHeight();
	centerHomeBannerText();
	setSlideNav();
	setHeaderBackground();

	//Resize events
	jQuery(window).smartresize(function(){
		setHomeBannerHeight();
		centerHomeBannerText();
		setHeaderBackground();
	});


	//Set Down Arrow Button
	jQuery('#scrollToContent').click(function(e){
		e.preventDefault();
		if(window.location.pathname == "/") {
			jQuery.scrollTo("#portfolio", 700, { offset:-(jQuery('#header .top').height()), axis:'y' });
		} else if (window.location.pathname == "/search") {
			jQuery.scrollTo("#search-result", 700, { offset:-(jQuery('#header .top').height()), axis:'y' });
		}
	});

	jQuery('nav > ul > li > a').click(function(e){
		var href = jQuery(this).attr('href');
		if (window.location.pathname == "/") {
			e.preventDefault();
			window.location.hash = "";
			jQuery.scrollTo(href.substr(1), 500, { offset:-(jQuery('#header .top').height()), axis:'y' });
		}
	})

	jQuery(window).scroll( function() {
		setHeaderBackground();
	});

	if(window.location.hash) {
		jQuery.scrollTo(window.location.hash, 500, { offset:-(jQuery('#header .top').height()), axis:'y' });
	} else if(window.location.pathname == "/search") {
		jQuery.scrollTo("#search-result", 500, { offset:-(jQuery('#header .top').height()), axis:'y' });
	}

	jQuery( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: 50000000,
		step: 100000,
		values: [ 0, 50000000 ],
		slide: function( event, ui ) {
			jQuery( "#amount" ).html( "Rp " + ui.values[ 0 ] + " - Rp " + ui.values[ 1 ] );
			jQuery( "#min" ).val( ui.values[ 0 ] );
			jQuery( "#max" ).val( ui.values[ 1 ] );
		}
	});
	jQuery( "#amount" ).html( "Rp. " + jQuery( "#slider-range" ).slider( "values", 0 ) +
	" - Rp. " + jQuery( "#slider-range" ).slider( "values", 1 ) );
	jQuery( "#min" ).val( jQuery( "#slider-range" ).slider( "values", 0 ) );
	jQuery( "#max" ).val( jQuery( "#slider-range" ).slider( "values", 1 ) );
})();