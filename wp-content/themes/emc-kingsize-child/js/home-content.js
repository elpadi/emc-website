var EMC_Home_Content = (function($) {

	let apiFetch = function(end, fn) {
		let base = wp.api.utils.getRootUrl() + 'wp-json/' + wp.api.versionString;
		return $.get(base + end, fn);
	};

	let scrollContent = function() {
		var y = window.innerHeight * 0.9;
		('smoothScrollTo' in window) ? smoothScrollTo(0, y) : window.scroll({
			top: y, left: 0, behavior: 'smooth'
		});
	};

	let showContent = function() {
		let b = document.body;
		b.classList.remove('no-content');
		b.classList.remove('body_hiding_content');
	};

	/**
	 * Add the content from the Our Project page below the home video on thin screen devices.
	 */
	return function initHomeContent() {
		let b = document.body;

		let isHome = b.classList.contains('home');
		let isShowingContent = isHome && document.querySelector('.backgroundvimeo').childElementCount == 0;

		if (isShowingContent) {
			showContent();
			if (document.getElementById('mainContainer').offsetTop == window.innerHeight) setTimeout(scrollContent, 2000);
		}
		else $('video').on('ended', showContent);

		$('video').on('ended', function() {
			document.querySelector('.backgroundvimeo').classList.add('show-bg');
			if (!isShowingContent) setTimeout(scrollContent, 3000);
		});

	};

})(jQuery);
