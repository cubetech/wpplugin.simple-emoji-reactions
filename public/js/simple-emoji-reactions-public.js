(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(function() {
		$('.simple-emoji-reactions-img').on('click', function(e) {
			e.preventDefault();
			var data = {
				action: 'upvote',
				postID: Ajax.postid,
				nonce: Ajax.nonce,
				emoji: $(this).data('emoji')
			};

			$.ajax({
				method: 'POST',
				context: this,
				url: Ajax.url,
				data: data,
				cache: false,
				beforeSend: function() {
				},
				success: function(data) {
					$(this).parent().parent().find('.simple-emoji-reactions-count').fadeOut();
					$(this).parent().parent().find('.simple-emoji-reactions-count').promise().done( function() {
						$(this).html( data );
						$(this).fadeIn();
					});
				},
				error: function() {
					$(this).parent().parent().find('.simple-emoji-reactions-count').html('?');
				}
			});

		});
	});

})( jQuery );
