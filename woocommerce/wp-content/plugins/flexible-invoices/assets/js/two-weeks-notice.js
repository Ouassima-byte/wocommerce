'use strict';

(function ($) {
	$(document).on('click', '.sm-close-temporary-notice, #flexible_invoice_two_week_rate_notice .notice-dismiss', function (event) {
		event.preventDefault();
		let button = this;
		$.ajax(ajaxurl,
			{
				type: 'POST',
				data: {
					action: 'flexible_invoices_close_temporary',
				}
			}
		).done(function() {
			$(button).parents('.notice').fadeOut();
		});
	});
})(jQuery);
