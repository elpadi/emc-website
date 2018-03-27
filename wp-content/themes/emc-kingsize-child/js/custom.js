(function($) {
	var init = {
		gallery: function() {
			//GALLERY IMAGES HOVER SCRIPT        
			//add span that will be shown on hover on gallery item
			$(".gallery li a.image, .columns a.image, .lightbox_blog").append('<span class="image_hover"></span>'); //add span to images
			$(".gallery  li a.video, .columns a.video").append('<span class="video_hover"></span>'); //add span to videos

			$('.gallery  li a span, .columns a span').css('opacity', '0').css('display', 'block');
			$('.eventon_list_event a span').css('opacity', '1').css('display', 'block');
			$('.products span').css('opacity', '1').css('display', 'inline-block');
			$('.woocommerce-review-link span').css('opacity', '1').css('display', 'inline-block');
			$('.vc_tta.vc_general .vc_tta-tab span').css('opacity', '1').css('display', 'inline-block');
			$('.vc_tta-title-text').css('opacity', '1').css('display', 'inline-block');
			$('.next-post span, .prev-post span').css('opacity', '1').css('display', 'inline-block');
			$('.edd-add-to-cart span.edd-add-to-cart-label').css('opacity', '1').css('display', 'inline-block');
			$('.edd-page h2 span').css('opacity', '1').css('display', 'inline-block');

			// show / hide span on hover
			$(".gallery li a, .columns a, .lightbox_blog").hover(
				function () {
					$(this).find('.image_hover, .video_hover').stop().fadeTo('slow', .7); }, 
				function () {
					$('.image_hover, .video_hover').stop().fadeOut('slow', 0);
				});	
		},
		blog: function() {
			//Remove the lightbox from Blog
			$(".lightbox_not").hover( function(){
				$('.image_hover').stop().fadeTo('fast', 0); 
				$('.image_hover').css('display', 'none');
			});
		},
		general: function() {
			('lazyload' in $.fn) && $("img.lazy").lazyload({
				effect: "fadeIn"
			});
			// FOOTER TOOLTIPS
			('tipsy' in $.fn) && $('.tooltip_link').tipsy({gravity: 's', fade: 'true' });	

			//SHORTCODES & ELEMENTS

			//tabs
			$(".tab_content").hide();
			$(".tabs_wrap ul.tabs").each(function() {
				$(this).find('li:first').addClass("active");
				$(this).next('.tab_container').find('.tab_content:first').show();
			});

			$(".tabs_wrap ul.tabs li a").click(function() {
				var cTab = $(this).closest('li');
				cTab.siblings('li').removeClass("active");
				cTab.addClass("active");
				cTab.closest('ul.tabs').nextAll('.tab_container:first').find('.tab_content').hide();

				var activeTab = $(this).attr("href"); //Find the href attribute value to identify the active tab + content
				$(activeTab).fadeIn(); //Fade in the active ID content
				return false;
			});

			// accordion

			$('.accordion div.accordion_content').hide();

			$('.accordion div.active_acc').next().show();

			$('.accordion div.accordion_head a').click(function(){
				//console.log($(this).parent().next().height());
				if ($(this).parent().hasClass('active_acc')){		
					$(this).parent().removeClass('active_acc').next().slideUp('1000');
				}else {
					$(this).closest('.accordion').find('.active_acc').removeClass('active_acc');
					$(this).closest('.accordion').find('.accordion_content').slideUp(); 
					$(this).parent().addClass('active_acc');		
					$(this).parent().next().slideDown('1000');
				}
				return false;
			});

			//toggls
			$(".hide").hide();

			$(".toggle").click(function(){

				$(this).closest(".toggle_box").find(".hide").toggle("fast");

				$(this).toggleClass('active');

				return false;
			}); 

			//changing class name of comment child class 8/1/2014 Added KS
			$("ul.children").attr('class', 'children no-bullet blog_comments');

			// editing padding of parent for grid columns gallery ----
			$('.columns.grid_columns').parent('.pV0H10').css('padding','0 14px');

			$(window).load(function() {	
				if(jQuery('body').hasClass('body_about')){
					jQuery('body').append('<div class="grid"></div>');		
				}	
				// pause background video on page scroll
				(function(iframe) {
					if (!iframe) return;
					var timeoutId,
						isPlaying = true,
						play = function() { (new Vimeo.Player(iframe)).play(); clearTimeout(timeoutId); isPlaying = true; },
						pause = function() { timeoutId = setTimeout(() => (new Vimeo.Player(iframe)).pause(), 1000); isPlaying = false; };
					window.addEventListener('scroll', function() {
						if (isPlaying && window.scrollY) pause();
						if (!isPlaying && !window.scrollY) play();
					});
			})(document.querySelector('.backgroundvimeo iframe'));

			//hide tooltip
			/*
			function hideTips(event) {  
					var saveAlt = $(this).attr('alt');
					var saveTitle = $(this).attr('title');
					if (event.type == 'mouseenter') {
							$(this).attr('title','');
							$(this).attr('alt','');
					} else {
							if (event.type == 'mouseleave'){
									$(this).attr('alt',saveAlt);
									$(this).attr('title',saveTitle);
							}
				 }
			}
			*/

			// Tooltip only Text
			$('.masterTooltip').hover(function(){
				// Hover over code
				var title = $(this).attr('title');
				$(this).data('tipText', title).removeAttr('title');
				$('<p class="tooltip"></p>')
					.text(title)
					.appendTo('body')
					.fadeIn('slow');
			}, function() {
				// Hover out code
				var title = $(this).attr('title');
				if (typeof title !== 'undefined' && title !== false) {
					$(this).attr('title', title);	
				}else{
					$(this).attr('title', $(this).data('tipText'));
				}
				$('.tooltip').remove();
			}).mousemove(function(e) {
				var mousex = e.pageX + 20; //Get X coordinates
				var mousey = e.pageY + 0; //Get Y coordinates
				$('.tooltip').css({ top: mousey, left: mousex })
			});		


			// to set the content column width 100% in case the content is too short for the screen--
			//var height = $('#mainContainer>.container').height();
			//$(document).bind('DOMSubtreeModified', function() {		
			/*setInterval(function(){
			if($('#mainContainer>.container').height() != height) {
				resizeCustom();
			}
		},500);*/
			//});

			resizeCustom();
			// to make the 50% columns clear left---

			$('div.clearColumn').each(function(i){
				if(i % 2 == 0){
					$(this).css('clear', 'left');	
				}else{
					$(this).css('clear', 'none');	
				}
			});


			//--To add wmode in iframes having youtube videos---
			function playerReady(){
				if(navigator.appName.indexOf("Internet Explorer")!=-1){     //yeah, he's using IE
					setTimeout(function(){
						$("#container iframe").each(function(){
							var ifr_source = $(this).attr('src');
							var wmode = "wmode=opaque";
							if(ifr_source.indexOf('?') != -1) {
								var getQString = ifr_source.split('?');
								var oldString = getQString[1];
								var newString = getQString[0];
								$(this).attr('src',newString+'?'+wmode+'&'+oldString);
							}
							else $(this).attr('src',ifr_source+'?'+wmode);
						});
					},2000);
				}
			}
			//############# Thumbnail Mouseover Error / HTML FIX ############# //
			$('a.image, .gallery_colorbox a, .gallery_fancybox a, .gallery_prettyphoto a, .assorted a').hover(function() {

				var title = $(this).prop('title'); //getting title attribute  

				var myrRegexp = /<p>(.*?)<\/p>/i,  //regular exp to get between tags text
					match = myrRegexp.exec(title); //executing the march from title

				var regex = /(<([^>]+)>)/ig; //regular exp
				var result = title.replace(regex, ""); //removing the html tags

				if(match)
				{
					result = result.replace(match[1], ""); //removing the text of p tag (description text)
				}

				$(this).data('orig-title', title).prop('title', result);
			}, function() {  
				$(this).prop('title', $(this).data('orig-title'));
			});

			$("a.image, .gallery_colorbox a, .gallery_fancybox a, .gallery_prettyphoto a, .assorted a").click(function() {
				$(this).prop('title', $(this).data('orig-title'));
			});

			jQuery(window).resize(resizeCustom);
			function resizeCustom(){
				/*if($('#mainContainer>.container').height() <= window.innerHeight){
			$('#mainContainer').height('100%');	
		}else{
			$('#mainContainer').height('auto');	
		}*/
				$('#mainContainer>.container').css('min-height', window.innerHeight);
			}

			/*
			//video aspect ratio 31/3/2015

			//https://css-tricks.com/NetMag/FluidWidthVideo/Article-FluidWidthVideo.php
			// Find all YouTube videos
			var $allVideos = $("iframe[src^='//player.vimeo.com'], iframe[src^='//www.youtube.com'], object, embed"),


				// The element that is fluid width
				$fluidEl = $("body");

			// Figure out and save aspect ratio for each video
			$allVideos.each(function() {

				$(this)
					.data('aspectRatio', this.height / this.width)

				// and remove the hard coded width/height
					.removeAttr('height')
					.removeAttr('width');

			});

			// When the window is resized
			$(window).resize(function() {

				var newWidth = $fluidEl.width();

				// Resize all videos according to their own aspect ratio
				$allVideos.each(function() {

					var $el = $(this);
					$el
						.width(newWidth)
						.height(newWidth * $el.data('aspectRatio'));

				});

				// Kick off one resize to fix all videos on page load
			}).resize();
			*/
			(function(iframe) {
				if (!iframe) return;
				(new Vimeo.Player(iframe)).on('play', function() {
					setTimeout(function() { iframe.classList.add('visible') }, 1000);
				});
			})(document.querySelector('.backgroundvimeo iframe'));
		},
		forms: EMC_Forms,
		nav: EMC_Nav,
		contact: function() {	
			//CONTACT PAGE MAP - CHANGE OPACITY ON HOVER
			$('img.map').css('opacity', '.5');
			
			$('img.map').hover(function(){
				$(this).fadeTo('fast', 1);	
			},
			function(){
				$(this).fadeTo('fast', .5);	
			});
		}
	}; // END init object
	$(document).ready(function($){
		for (let initFn in init) init[initFn].call(this);
	});
})(jQuery);
