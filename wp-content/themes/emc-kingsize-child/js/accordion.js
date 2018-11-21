(function($) {
	$(document).ready(function() {
		$(document).on('click', '.accordion-toggler', function() {
			var a = $(this);
			a.add(a.parent('.accordion')).toggleClass('open');
		});
	});
})(jQuery);
