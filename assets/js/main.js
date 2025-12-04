(function ($) {
	'use strict';

	// Simple lightbox
	function initLightbox() {
		var $overlay = $('<div class="lightbox-overlay" role="dialog" aria-modal="true"></div>');
		var $wrapper = $('<div class="lightbox-image-wrapper"></div>');
		var $img = $('<img alt="">');
		var $close = $('<button class="lightbox-close" aria-label="Fermer">×</button>');

		$wrapper.append($img);
		$overlay.append($wrapper).append($close);
		$('body').append($overlay);

		$(document).on('click', '.js-lightbox-trigger a', function (e) {
			e.preventDefault();
			var src = $(this).attr('href');
			$img.attr('src', src);
			$overlay.addClass('is-visible');
		});

		$overlay.on('click', function (e) {
			if (e.target === this || $(e.target).is('.lightbox-close')) {
				$overlay.removeClass('is-visible');
			}
		});

		$(document).on('keyup', function (e) {
			if (e.key === 'Escape') {
				$overlay.removeClass('is-visible');
			}
		});
	}

	// Scroll animations
	function initScrollAnimations() {
		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		}, {
			threshold: 0.2
		});

		$('.js-animate').each(function () {
			observer.observe(this);
		});
	}

	// Mobile menu
	function initMobileMenu() {
		var $toggle = $('.nav-toggle');
		var $nav = $('.primary-navigation');

		$toggle.on('click', function () {
			var expanded = $(this).attr('aria-expanded') === 'true' || false;
			$(this).attr('aria-expanded', !expanded);
			$nav.toggleClass('is-open');
		});
	}

	// Gallery filters (simple)
	function initGalleryFilters() {
		$(document).on('click', '.filter-btn', function () {
			var filter = $(this).data('filter');
			$('.filter-btn').removeClass('is-active');
			$(this).addClass('is-active');

			$('.gallery-item').each(function () {
				if (filter === '*' || $(this).is(filter)) {
					$(this).stop(true, true).fadeIn(250);
				} else {
					$(this).stop(true, true).fadeOut(250);
				}
			});
		});
	}

	// DOM Ready
	$(function () {
		initLightbox();
		initScrollAnimations();
		initMobileMenu();
		initGalleryFilters();
	});

})(jQuery);
