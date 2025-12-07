/**
 * Lumière Gallery Filter
 * Ajax-powered portfolio filtering with smooth animations
 */

(function($) {
	'use strict';

	const GalleryFilter = {
		currentPage: 1,
		isLoading: false,
		hasMore: true,

		init: function() {
			this.bindEvents();
			this.initInfiniteScroll();
		},

		bindEvents: function() {
			const self = this;

			// Filter buttons click
			$('.gallery-filter-btn').on('click', function(e) {
				e.preventDefault();
				
				// Update active state
				$('.gallery-filter-btn').removeClass('active');
				$(this).addClass('active');

				// Get filter values
				const filterType = $(this).data('filter-type');
				const filterValue = $(this).data('filter-value');

				// Reset page and filter
				self.currentPage = 1;
				self.filterGalleries(filterType, filterValue);
			});

			// Layout switcher
			$('.layout-switcher button').on('click', function(e) {
				e.preventDefault();
				const layout = $(this).data('layout');
				self.switchLayout(layout);
				
				$('.layout-switcher button').removeClass('active');
				$(this).addClass('active');
			});

			// Search
			let searchTimeout;
			$('#gallery-search').on('input', function() {
				clearTimeout(searchTimeout);
				const query = $(this).val();
				
				searchTimeout = setTimeout(function() {
					if (query.length >= 3 || query.length === 0) {
						self.searchGalleries(query);
					}
				}, 500);
			});

			// Load more button
			$('.load-more-btn').on('click', function(e) {
				e.preventDefault();
				self.loadMore();
			});
		},

		filterGalleries: function(filterType, filterValue) {
			const self = this;

			if (self.isLoading) return;
			self.isLoading = true;

			const $container = $('.gallery-grid');
			$container.addClass('loading');

			const data = {
				action: 'lumiere_filter_galleries',
				nonce: lumiereAjax.filterNonce,
				paged: 1,
				layout: $('.layout-switcher button.active').data('layout') || 'grid'
			};

			// Add filter parameter
			if (filterType && filterValue !== 'all') {
				data[filterType] = filterValue;
			}

			$.ajax({
				url: lumiereAjax.ajaxUrl,
				type: 'POST',
				data: data,
				success: function(response) {
					if (response.success) {
						// Fade out old content
						$container.fadeOut(300, function() {
							// Replace content
							$(this).html(response.data.content);
							
							// Update state
							self.hasMore = response.data.current_page < response.data.max_pages;
							
							// Fade in new content
							$(this).fadeIn(300);
							
							// Update load more button
							if (self.hasMore) {
								$('.load-more-btn').show();
							} else {
								$('.load-more-btn').hide();
							}

							// Reinitialize masonry if needed
							if (typeof self.initMasonry === 'function') {
								self.initMasonry();
							}

							// Reinitialize lazy loading
							if (typeof self.initLazyLoad === 'function') {
								self.initLazyLoad();
							}
						});

						// Update results count
						if (response.data.found_posts !== undefined) {
							$('.results-count').text(response.data.found_posts + ' galerie(s) trouvée(s)');
						}
					}
				},
				error: function(xhr, status, error) {
					console.error('Filter error:', error);
					$container.html('<p class="error-message">Une erreur est survenue. Veuillez réessayer.</p>');
				},
				complete: function() {
					$container.removeClass('loading');
					self.isLoading = false;
				}
			});
		},

		loadMore: function() {
			const self = this;

			if (self.isLoading || !self.hasMore) return;
			self.isLoading = true;

			const $button = $('.load-more-btn');
			$button.addClass('loading').text('Chargement...');

			self.currentPage++;

			$.ajax({
				url: lumiereAjax.ajaxUrl,
				type: 'POST',
				data: {
					action: 'lumiere_load_more_galleries',
					nonce: lumiereAjax.loadMoreNonce,
					paged: self.currentPage,
					layout: $('.layout-switcher button.active').data('layout') || 'grid'
				},
				success: function(response) {
					if (response.success) {
						// Append new content
						const $newItems = $(response.data.content);
						$newItems.hide();
						$('.gallery-grid').append($newItems);
						$newItems.fadeIn(400);

						// Update state
						self.hasMore = response.data.has_more;

						if (!self.hasMore) {
							$button.hide();
						}

						// Reinitialize features
						if (typeof self.initMasonry === 'function') {
							self.initMasonry();
						}
						if (typeof self.initLazyLoad === 'function') {
							self.initLazyLoad();
						}
					}
				},
				error: function() {
					self.currentPage--;
				},
				complete: function() {
					$button.removeClass('loading').text('Charger plus');
					self.isLoading = false;
				}
			});
		},

		searchGalleries: function(query) {
			const self = this;

			if (self.isLoading) return;
			self.isLoading = true;

			const $container = $('.gallery-grid');
			$container.addClass('loading');

			$.ajax({
				url: lumiereAjax.ajaxUrl,
				type: 'POST',
				data: {
					action: 'lumiere_search_galleries',
					nonce: lumiereAjax.searchNonce,
					search: query
				},
				success: function(response) {
					if (response.success) {
						// Display search results
						self.displaySearchResults(response.data.results);
					}
				},
				complete: function() {
					$container.removeClass('loading');
					self.isLoading = false;
				}
			});
		},

		displaySearchResults: function(results) {
			const $container = $('.gallery-grid');
			$container.empty();

			if (results.length === 0) {
				$container.html('<p class="no-results">Aucun résultat trouvé.</p>');
				return;
			}

			$.each(results, function(index, item) {
				const html = `
					<div class="gallery-item">
						<a href="${item.url}">
							<img src="${item.thumbnail}" alt="${item.title}" loading="lazy">
							<h3>${item.title}</h3>
						</a>
					</div>
				`;
				$container.append(html);
			});
		},

		switchLayout: function(layout) {
			const $container = $('.gallery-grid');
			
			// Remove all layout classes
			$container.removeClass('layout-grid layout-masonry layout-justified layout-slider');
			
			// Add new layout class
			$container.addClass('layout-' + layout);

			// Reinitialize layout-specific features
			if (layout === 'masonry' && typeof this.initMasonry === 'function') {
				this.initMasonry();
			}
		},

		initInfiniteScroll: function() {
			const self = this;
			let throttleTimeout;

			$(window).on('scroll', function() {
				if (throttleTimeout) return;

				throttleTimeout = setTimeout(function() {
					throttleTimeout = null;

					const scrollTop = $(window).scrollTop();
					const windowHeight = $(window).height();
					const documentHeight = $(document).height();

					// Load more when near bottom (200px before)
					if (scrollTop + windowHeight > documentHeight - 200) {
						if (self.hasMore && !self.isLoading) {
							self.loadMore();
						}
					}
				}, 300);
			});
		},

		initMasonry: function() {
			// Placeholder for Masonry initialization if library is loaded
			if (typeof $.fn.masonry !== 'undefined') {
				$('.gallery-grid.layout-masonry').masonry({
					itemSelector: '.gallery-item',
					columnWidth: '.gallery-item',
					percentPosition: true,
					gutter: 20
				});
			}
		},

		initLazyLoad: function() {
			// Reinitialize lazy loading for new images
			const images = document.querySelectorAll('img[data-src]');
			const config = {
				rootMargin: '50px 0px',
				threshold: 0.01
			};

			const imageObserver = new IntersectionObserver(function(entries, self) {
				entries.forEach(entry => {
					if (entry.isIntersecting) {
						const img = entry.target;
						const src = img.getAttribute('data-src');
						if (src) {
							img.src = src;
							img.removeAttribute('data-src');
						}
						self.unobserve(img);
					}
				});
			}, config);

			images.forEach(image => imageObserver.observe(image));
		}
	};

	// Initialize on document ready
	$(document).ready(function() {
		GalleryFilter.init();
	});

})(jQuery);
