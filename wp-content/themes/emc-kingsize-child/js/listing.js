/**
 * Filter the video listing using tags imported
 * from Vimeo.
 */
var EMC_Listing = (function($) {
	var TAGS_HASH_DELIMETER = '_';
	var createTagButton = function(name) {
		let b = document.createElement('button');
		b.className = 'radio plain';
		b.innerHTML = name;
		return b;
	};
	var setup = function(container) {
		let f = container.querySelector('form');
		var allTags = {};
		container.selectedTags = {};
		container.allTags = {};
		getListingItems(container).forEach(function(item) {
			// put each item tags in an object
			item.tags = {};
			JSON.parse(item.dataset.tags).forEach(function(t) {
				item.tags[t] = true;
				allTags[t] = true;
			});
		});
		Object.keys(allTags).sort().forEach(function(t) {
			container.allTags[t] = true;
		});
		$(container).find('form')
			.append($(getListingTags(container).map(createTagButton)))
			.on('click', 'button', function(e) {
				var b = this;
				e.preventDefault();
				b.classList.toggle('selected');
				if (b.innerHTML in container.selectedTags) {
					delete container.selectedTags[b.innerHTML];
				}
				else {
					container.selectedTags[b.innerHTML] = true;
				}
				updateLocationHash(getEnabledTags(container));
		});
		f.addEventListener('submit', function(e) {
			e.preventDefault();
		});
		window.addEventListener("hashchange", function() { onHashChange(container); });
		onHashChange(container);
	};
	var getListingItems = function(container) {
		return Array.from(container.getElementsByTagName('article'));
	};
	var getListingTags = function(container) {
		return Object.keys(container.allTags);
	};
	var getTagButtons = function(container) {
		return Array.from(container.querySelectorAll('form button'));
	};
	var getEnabledTags = function(container) {
		return Object.keys(container.selectedTags);
	};
	var clearTags = function(container) {
		return getTagButtons(container).forEach(n => n.classList.remove('selected'));
	};
	var updateLocationHash = function(tags) {
		if (tags.length > 0) {
			location.hash = '#' + encodeURIComponent(tags.join(TAGS_HASH_DELIMETER));
		}
		else location.hash = '';
	};
	var onHashChange = function(container) {
		console.log('EMC_Listing.onHashChange', location.hash);
		container.selectedTags = {};
		if (location.hash != '') {
			var tags = decodeURIComponent(location.hash.substr(1)).split(TAGS_HASH_DELIMETER);
			console.log('EMC_Listing.onHashChange', tags);
			tags.map(function(t) {
				if (t in container.allTags) {
					container.selectedTags[t] = true;
				}
			});
		}
		updateTagFilter(container, getEnabledTags(container));
	};
	var updateTagFilter = function(container, tags) {
		console.log('EMC_Listing.updateTagFilter', tags);
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
