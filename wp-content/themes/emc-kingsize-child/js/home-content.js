var EMC_Home_Content = (function($) {

	let apiFetch = function(end, fn) {
		let base = wp.api.utils.getRootUrl() + 'wp-json/' + wp.api.versionString;
		return $.get(base + end, fn);
	};

	let showContent = function(b, p) {
		b.classList.remove('no-content');
		b.classList.remove('body_hiding_content');
		$('h2.title-page').html(p.title.rendered);
		$('.page_content').html(p.content.rendered);
		document.querySelector('video').currentTime = 2;
	};

	/**
	 * Add the content from the Our Project page below the home video on thin screen devices.
	 */
	return function initHomeContent() {
		let b = document.body;

		let isHome = b.classList.contains('home');
		let isShowingContent = isHome && (window.innerWidth / window.innerHeight < 1.5);

		isHome && apiFetch('pages?slug=our-project', p => {
			if (p.length == 0) return;
			let show = () => showContent(b, p[0]);

			if (isShowingContent) show();
			else $('video').on('ended', show);
		});

	};

})(jQuery);
