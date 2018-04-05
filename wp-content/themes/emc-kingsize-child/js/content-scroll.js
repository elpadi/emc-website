var EMC_ContentScroll = (function($) {
	var Video = (function(videoContainer) {
		if (!videoContainer || !videoContainer.children.length) return;
		var iframe = videoContainer && videoContainer.children.length ? videoContainer.children[0] : null;
		var isPlaying,
			play = function() { isPlaying !== true && iframe && (new Vimeo.Player(iframe)).play(); isPlaying = true; },
			pause = function() { isPlaying !== false && iframe && (new Vimeo.Player(iframe)).pause(); isPlaying = false; };
		return { play: play, pause: pause };
	})(document.querySelector('.backgroundvimeo'));
	var Scroll = (function() {
		var wasScrolledTop;
		var onScrollDown = function() {
			Video && Video.pause();
			document.getElementById('navContainer').classList.add('expanded');
			document.body.classList.remove('scroll-top');
			wasScrolledTop = false;
		};
		var onScrollUp = function() {
			Video && Video.play();
			document.getElementById('navContainer').classList.remove('expanded');
			document.body.classList.add('scroll-top');
			wasScrolledTop = true;
		};
		var isScrolledTop = function() {
			return window.scrollY < window.innerHeight / 2;
		};
		var onScroll = function() {
			if (wasScrolledTop !== true && isScrolledTop()) onScrollUp();
			if (wasScrolledTop !== false && !isScrolledTop()) onScrollDown();
		};
		var scrollTo = function(y) {
			window[('smoothScrollTo' in window) ? 'smoothScrollTo' : 'scrollTo'](0, y);
		};
		setTimeout(function() {
			iframe.classList.add('visible');
			if (wasScrolledTop !== true && isScrolledTop()) {
				if (window.scrollY) scrollTo(0);
				Video && Video.play();
			}
			else {
				Video && Video.pause();
			}
			wasScrolledTop = isScrolledTop();
			return { onScroll: onScroll, isScrolledTop: isScrolledTop, scrollTo: scrollTo };
		}, 1500);
	})(document.querySelector('.backgroundvimeo'));
	return function initContentScroll() {
		$(window).load(function() {	
			$(document.body).toggleClass('scroll-top', window.scrollY == 0);
			document.getElementById('scroll-button').addEventListener('click', function(e) {
				Scroll.scrollTo(Scroll.isScrolledTop() ? window.innerHeight : 0);
			});
			window.addEventListener('scroll', Scroll.onScroll);
		});
	};
})(jQuery);
