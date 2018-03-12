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
		
		/*
			To show / hide the desktop navigation on arrow button click ----		
		*/
		/**
		 * EMC EDIT: We'll move most of this to css transition.
		 *
		var isUp = false;
		var navHeight = $('#navContainer').height();
		var hideHeight = navHeight - 100; 

		$('#arrowLink a').click(function(e){
			e.preventDefault();
			navHeight = $('#navContainer').height();
			hideHeight = navHeight - 100; 
			
			$('.tooltip').remove();
			if(!isUp){			
				$(this).find('img').attr('src',template_directory+'/images/menu_hide_arrow_bottom.png');
				$(this).find('img').attr('title',showNav);
				$( "#navContainer" ).animate({			
					top: '-='+ hideHeight + 'px'
				}, 500, "swing", function() {
					isUp = true;
				});	
			}else{
				$(this).find('img').attr('src',template_directory+'/images/menu_hide_arrow_top.png');
				$(this).find('img').attr('title',hideNav);
				$( "#navContainer" ).animate({			
					top: "0"
				}, 500, "swing", function() {
					isUp = false;
				});				


				if($('body').hasClass('body_show_content'))
				{
					 $('#mainContainer').fadeIn();	
				}	
			}
		});
		*/
		var toggleMainNav = function() {
			$('#navContainer').toggleClass('expanded');
		};
		$('#arrowLink a').on('click', function(e) {
			e.preventDefault();
			toggleMainNav();
		});

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
