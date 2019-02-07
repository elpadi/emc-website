var EMC_Home_Content = (function($) {

	let apiFetch = function(end, fn) {
		let base = wp.api.utils.getRootUrl() + 'wp-json/' + wp.api.versionString;
		return $.get(base + end, fn);
	};

	/**
	 * Add the content from the Our Project page below the home video on thin screen devices.
	 */
	return function initHomeContent() {
		let b = document.body;
		if (b.classList.contains('home') && window.innerWidth / window.innerHeight < 1.5) {
			apiFetch('pages?slug=our-project', p => {
				if (p.length == 0) return;
				let _p = p[0];

				b.classList.remove('no-content');
				b.classList.remove('body_hiding_content');
				$('.title-page').html(_p.title.rendered);
				$('.page_content').html(_p.content.rendered);
			});
		}
	};
})(jQuery);
