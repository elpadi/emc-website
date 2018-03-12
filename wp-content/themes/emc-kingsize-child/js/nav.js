var EMC_Nav = (function($) {
	return function initNav() {
		/* Responsive V5 */	
		/*	Code for generating mobile navigation from desktop one */	


		$( ".sub-menu > li" ).removeClass('mainNav'); //Fixed for empty child nav in mobile version removing class from child nav "li"	Done By Kumar 8/5/2014
		$( ".sub-menu > li" ).removeClass('no_desc'); //Fixed for empty child nav in mobile version removing class from child nav "li"	Done By Kumar 8/5/2014

		$( "body" ).removeClass('blog'); //Fixed for spacing issue in mobile version header area	Done By Kumar 8/5/2014

		$( "#mainNavigation > ul" ).clone().appendTo( "#cssmenu" );
		
		$('#cssmenu li.mainNav').each(function(i){
			// to set the menu active ----
			//if($(this).children('a').hasClass('current')){
			$(this).find('a.current').parents('li').addClass('active');
			$(this).find('li').each(function(j){
				$(this).find('a').css('margin-left',($(this).parents('li').length)*10);
			});
			//}
			$(this).removeClass('mainNav');
			$(this).children('li a').each(function(i){
				$(this).html('<span>'+$(this).children('h5').text()+'</span>');
			});
			
			
		});	
		// to add a li if the menu item having submenu has link to it----ajay 05112014
		$('#cssmenu li a').each(function(){
			if($(this).next('ul').length && $(this).attr('href')!="#" && $(this).attr('href')!= undefined){
				$(this).next('ul').each(function(){
					//console.log($(this).prev().clone());
					//var aClone = $(this).prev().clone();
					var aLink = $(this).prev().attr('href');
					var aText = $(this).prev().html();
					$(this).prepend('<li class="for-mobile-only"><a href="'+aLink+'" style="margin-left: 10px;">'+aText+'</a></li>');
				});
			}
		});
		//console.log('UL::-- '+mobileUL.html());
		//	$('div#cssmenu > ul').append(mobileUL.html());
		/*
			Mobile nav events handling code ----
		*/	
		$('#cssmenu > ul > li ul').each(function(index, e){
			var count = $(e).find('>li').length;
			var content = '<span class="cnt">' + count + '</span>';
			$(e).closest('li').children('a').append(content);
		});
		$('#cssmenu ul ul li:odd').addClass('odd');
		$('#cssmenu ul ul li:even').addClass('even');
		// to set the selected nav always active ----
		var activeEle = $('#cssmenu li.active');
		
		$('#cssmenu ul li > a').click(function() {
			//$('#cssmenu li').removeClass('active');	  
			$(this).closest('li').siblings().removeClass('active');	  
			$(this).closest('li').siblings().children('li').removeClass('active');
			activeEle.addClass('active');	  
			$(this).parent('li').addClass('active');	
			var checkElement = $(this).next();
			if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
			$(this).parent('li').removeClass('active');
			activeEle.addClass('active');
			checkElement.slideUp('normal');
			}
			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {		
			checkElement.closest('li').siblings().children('ul:visible').slideUp('normal');		
			checkElement.slideDown('normal');
			}
			if($(this).closest('li').find('ul').children().length == 0) {
			return true;
			} else {
			return false;	
			}		
		});
		
		/**
		 * EMC EDIT: Move main menu hide/show to css transitions.
		 */
		var toggleMainNav = function() {
			$('#navContainer').toggleClass('expanded');
			clearTimeout(initialShowTimeoutId);
		};
		$('#arrowLink a').on('click', function(e) {
			e.preventDefault();
			toggleMainNav();
		});
		// expand main menu on window load
		var initialShowTimeoutId = setTimeout(toggleMainNav, 5000);

		/* AUTO HIDE MENU @ KS */
		if ($('body').hasClass('body_hiding_menu'))
		{	
			$('#arrowLink a').click();
			$('.toggle-topbar a').click();
		}
		
		/* Menu hide on mobile @KS */
		$('.toggle-topbar a').click(function(e){
			
			if($('body').hasClass('body_show_content'))
			{
				 $('#mainContainer').fadeIn();	
			}
			
		});
	};
})(jQuery);
