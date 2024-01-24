(function ($) {

	"use strict";
	
	// Preload
	$(window).on('load', function () { // makes sure the whole site is loaded
		$('[data-loader="circle-side"]').fadeOut(); // will first fade out the loading animation
		$('#preloader').addClass('loaded');
		$('.animate_hero').addClass('is-transitioned');
	})

	// Sticky Header
	$(window).on('scroll', function () {
		if ($(this).scrollTop() > 1) {
			$('.fixed_header').addClass("sticky");
		} else {
			$('.fixed_header').removeClass("sticky");
		}
	});
	$(window).scroll();

	// Scroll animation
	scrollCue.init({
	    percentage : 0.85
	});

	// Opacity mask
	$('.opacity-mask').each(function(){
		$(this).css('background-color', $(this).attr('data-opacity-mask'));
	});

	// Data Background
	$('.background-image').each(function(){
		$(this).css('background-image', $(this).attr('data-background'));
	});

	// Button scroll to
    $('a[href^="#"].btn_scrollto').on('click', function (e) {
			e.preventDefault();
			var target = this.hash;
			var $target = $(target);
			$('html, body').stop().animate({
				'scrollTop': $target.offset().top -60
			}, 300, 'swing', function () {
				window.location.hash = target;
		});
	});

	// Pinned content
	const pinnedImages = document.querySelectorAll('.pinned-image');
	pinnedImages.forEach(pinnedImage => {
	    const container = pinnedImage.querySelector('.pinned-image__container');
	    const image = container.querySelector('img');
	    const overlay = container.querySelector('.pinned-image__container-overlay');
	    const content = pinnedImage.querySelector('.pinned_over_content');
	    const tl = gsap.timeline({paused: true});
	    tl.to(container, {
	        scale: 1.05,
	    }, 0);
	    tl.from(content, {
	        autoAlpha: 0,
	    }, 0);
	    tl.from(overlay, {
	        autoAlpha: 0,
	    }, 0);
	    const trigger = ScrollTrigger.create({
	        animation: tl,
	        trigger: pinnedImage,
	        start: "top center",
	        markers: false,
	        pin: false,
	        scrub: false,
	    });
	});

	// Video Play on scroll
	var $win = $(window);
	var $sectionvideo = $('#section_video video');
    var elementTop, elementBottom, viewportTop, viewportBottom;

    function isScrolledIntoView(elem) {
      elementTop = $(elem).offset().top;
      elementBottom = elementTop + $(elem).outerHeight();
      viewportTop = $win.scrollTop();
      viewportBottom = viewportTop + $win.height();
      return (elementBottom > viewportTop && elementTop < viewportBottom);
    }
    
    if($sectionvideo.length){

      var loadVideo;

      $sectionvideo.each(function(){
        $(this).attr('webkit-playsinline', '');
        $(this).attr('playsinline', '');
        $(this).attr('muted', 'muted');

        $(this).attr('id','loadvideo');
        loadVideo = document.getElementById('loadvideo');
        loadVideo.load();
      });

      $win.scroll(function () { // video to play when is on viewport 
      
        $sectionvideo.each(function(){
          if (isScrolledIntoView(this) == true) {
              $(this)[0].play();
          } else {
              $(this)[0].pause();
          }
        });
      
      });
    }

	// Menu sidebar mobile
	$('.open_close_menu').on("click", function () {
		$('.main-menu').toggleClass('show');
		$('.layer').toggleClass('layer-is-visible');
	});

	// Sticky titles
	$('.fixed_title').theiaStickySidebar({
		minWidth: 991,
		additionalMarginTop: 120
	});

	// Carousel items 1
	$('.carousel_item_1').owlCarousel({
	    center: true,
	    items:1,
	    loop:false, 
	    addClassActive: true,
	    margin:0,
	    autoplay:false,
	    autoplayTimeout:3000,
		autoplayHoverPause:true,
		animateOut: 'fadeOut',
	    responsive:{
	    	0:{
	            dots:true
	        },
	        991:{
	            dots:true
	        }
	    }
	});

	// Carousel items centererd generals
	$('.carousel_item_centered').owlCarousel({    
	    loop:true,
	    margin:5,
	    nav:true,
	    dots:false,
	    center:true,
	    navText: ["<i class='bi bi-arrow-left-short'></i>","<i class='bi bi-arrow-right-short'></i>"],
	    responsive:{
	        0:{
	            items:1
	        },
	        600:{
	            items:2
	        },
	        1000:{
	            items:2
	        }
	    }
	});

	// Carousel items 3
	$('.carousel_item_3').owlCarousel({    
	    loop:false,
	    margin:15,
	    nav:true,
	    dots:false,
	    center:false,
	    navText: ["<i class='bi bi-arrow-left-short'></i>","<i class='bi bi-arrow-right-short'></i>"],
	    responsive:{
	        0:{
	            items:1
	        },
	        600:{
	            items:2
	        },
	        1000:{
	            items:3
	        }
	    }
	});

	// Carousel testimonials
	$('.carousel_testimonials').owlCarousel({
	 	items:1,
	    loop:true,
		autoplay:false,
	    animateIn: 'flipInX',
		margin:40,
    	stagePadding:30,
	    smartSpeed:300,
	    autoHeight:true,
	    dots:true,
		responsiveClass:true,
	    responsive:{
	        600:{
	            items:1
	        },
			 1000:{
	            items:1,
				nav:false
	        }
	    }
	});

	// Jquery select
	$('.custom_select select').niceSelect();

})(jQuery);

