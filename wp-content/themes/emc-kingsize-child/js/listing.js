/**
 * Filter the video listing using tags imported
 * from Vimeo.
 */
var EMC_Listing = (function($) {
	var createTagButton = function(name) {
		let b = document.createElement('button');
		b.className = 'radio plain';
		b.innerHTML = name;
		return b;
	};
	var setup = function(container) {
		let f = container.querySelector('form');
		getListingTags(container).map(createTagButton).forEach(b => f.appendChild(b));
		f.addEventListener('click', function(e) {
			if (e.target.nodeName === 'BUTTON') {
				e.preventDefault();
				e.target.classList.toggle('selected');
				filterByTags(container, getEnabledTags(container));
			}
		});
		f.addEventListener('submit', function(e) {
			e.preventDefault();
		});
	};
	var getListingItems = function(container) {
		return Array.from(container.getElementsByTagName('article'));
	};
	var getListingTags = function(container) {
		let tags = [].concat.apply([], getListingItems(container).map(n => JSON.parse(n.dataset.tags)));
		tags.sort();
		return tags.filter((t, i, all) => all.indexOf(t) == i);
	};
	var getTagButtons = function(container) {
		return Array.from(container.querySelectorAll('form button'));
	};
	var getEnabledTags = function(container) {
		return getTagButtons(container).filter(n => n.classList.contains('selected')).map(n => n.innerHTML);
	};
	var clearTags = function(container) {
		return getTagButtons(container).forEach(n => n.classList.remove('selected'));
	};
	var filterByTags = function(container, tags) {
		getListingItems(container).forEach(function(n) {
			if (tags.length == 0) return n.classList.remove('disabled');
			let tagsInItem = tags.filter(t => JSON.parse(n.dataset.tags).includes(t));
			n.classList[tagsInItem.length ? 'remove' : 'add']('disabled');
		});
	};
	return function initVideoListing() {
		Array.from(document.getElementsByClassName('video-listing')).forEach(setup);
	};
})(jQuery);
