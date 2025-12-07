/**
 * Lumière Lightbox - Modern Image Viewer
 * 
 * Features:
 * - Touch/swipe gestures for mobile
 * - Keyboard navigation
 * - Smooth animations
 * - Zoom functionality
 * - Image preloading
 */

(function($) {
	'use strict';

	const LumiereLightbox = {
		currentIndex: 0,
		images: [],
		isOpen: false,
		touchStartX: 0,
		touchEndX: 0,

		init: function() {
			this.bindEvents();
			this.createLightboxHTML();
		},

		createLightboxHTML: function() {
			const html = `
				<div id="lumiere-lightbox" class="lumiere-lightbox" style="display: none;">
					<div class="lightbox-overlay"></div>
					<div class="lightbox-container">
						<button class="lightbox-close" aria-label="Fermer">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<line x1="18" y1="6" x2="6" y2="18"></line>
								<line x1="6" y1="6" x2="18" y2="18"></line>
							</svg>
						</button>
						<button class="lightbox-prev" aria-label="Précédent">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="15 18 9 12 15 6"></polyline>
							</svg>
						</button>
						<button class="lightbox-next" aria-label="Suivant">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="9 18 15 12 9 6"></polyline>
							</svg>
						</button>
						<div class="lightbox-content">
							<img src="" alt="" class="lightbox-image">
							<div class="lightbox-caption"></div>
							<div class="lightbox-counter"></div>
						</div>
						<div class="lightbox-loader">
							<div class="spinner"></div>
						</div>
					</div>
				</div>
			`;
			$('body').append(html);
		},

		bindEvents: function() {
			const self = this;

			// Click on gallery images
			$(document).on('click', '[data-lightbox]', function(e) {
				e.preventDefault();
				const galleryId = $(this).data('lightbox');
				const index = $(this).data('index') || 0;
				self.open(galleryId, index);
			});

			// Close button
			$(document).on('click', '.lightbox-close, .lightbox-overlay', function() {
				self.close();
			});

			// Navigation buttons
			$(document).on('click', '.lightbox-prev', function(e) {
				e.stopPropagation();
				self.prev();
			});

			$(document).on('click', '.lightbox-next', function(e) {
				e.stopPropagation();
				self.next();
			});

			// Keyboard navigation
			$(document).on('keydown', function(e) {
				if (!self.isOpen) return;

				switch(e.key) {
					case 'Escape':
						self.close();
						break;
					case 'ArrowLeft':
						self.prev();
						break;
					case 'ArrowRight':
						self.next();
						break;
				}
			});

			// Touch/swipe support
			const lightbox = document.getElementById('lumiere-lightbox');
			if (lightbox) {
				lightbox.addEventListener('touchstart', function(e) {
					self.touchStartX = e.changedTouches[0].screenX;
				}, false);

				lightbox.addEventListener('touchend', function(e) {
					self.touchEndX = e.changedTouches[0].screenX;
					self.handleSwipe();
				}, false);
			}
		},

		handleSwipe: function() {
			const swipeThreshold = 50;
			const diff = this.touchStartX - this.touchEndX;

			if (Math.abs(diff) > swipeThreshold) {
				if (diff > 0) {
					this.next(); // Swipe left
				} else {
					this.prev(); // Swipe right
				}
			}
		},

		open: function(galleryId, index) {
			const self = this;
			this.currentIndex = parseInt(index);

			// Get all images in gallery
			this.images = [];
			$('[data-lightbox="' + galleryId + '"]').each(function() {
				self.images.push({
					src: $(this).attr('href') || $(this).data('src'),
					caption: $(this).data('caption') || $(this).attr('title') || '',
					alt: $(this).find('img').attr('alt') || ''
				});
			});

			if (this.images.length === 0) return;

			this.isOpen = true;
			$('#lumiere-lightbox').fadeIn(300);
			$('body').addClass('lightbox-open');
			this.loadImage(this.currentIndex);
		},

		close: function() {
			this.isOpen = false;
			$('#lumiere-lightbox').fadeOut(300);
			$('body').removeClass('lightbox-open');
			this.images = [];
		},

		loadImage: function(index) {
			const self = this;
			const image = this.images[index];

			if (!image) return;

			// Show loader
			$('.lightbox-loader').show();
			$('.lightbox-image').css('opacity', 0);

			// Preload image
			const img = new Image();
			img.onload = function() {
				$('.lightbox-image').attr('src', image.src).attr('alt', image.alt);
				$('.lightbox-caption').text(image.caption);
				$('.lightbox-counter').text((index + 1) + ' / ' + self.images.length);
				$('.lightbox-loader').hide();
				$('.lightbox-image').css('opacity', 1);

				// Preload next and previous images
				self.preloadAdjacentImages(index);
			};
			img.src = image.src;

			// Update navigation buttons
			$('.lightbox-prev').toggle(index > 0);
			$('.lightbox-next').toggle(index < this.images.length - 1);
		},

		preloadAdjacentImages: function(index) {
			// Preload next image
			if (index + 1 < this.images.length) {
				const nextImg = new Image();
				nextImg.src = this.images[index + 1].src;
			}

			// Preload previous image
			if (index - 1 >= 0) {
				const prevImg = new Image();
				prevImg.src = this.images[index - 1].src;
			}
		},

		next: function() {
			if (this.currentIndex < this.images.length - 1) {
				this.currentIndex++;
				this.loadImage(this.currentIndex);
			}
		},

		prev: function() {
			if (this.currentIndex > 0) {
				this.currentIndex--;
				this.loadImage(this.currentIndex);
			}
		}
	};

	// Initialize on document ready
	$(document).ready(function() {
		LumiereLightbox.init();
	});

})(jQuery);
