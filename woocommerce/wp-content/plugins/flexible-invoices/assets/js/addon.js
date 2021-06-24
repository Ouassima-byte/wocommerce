(function ($) {
	"use strict";
	let filters_html = jQuery('#invoice-filters-html-addon');
	if (filters_html.length) {
		jQuery('.subsubsub').before(filters_html.html());
	}

	let button = jQuery('.fiaf-container .notice-dismiss');
	if (button.length) {
		button.click(function () {
			jQuery.post(ajaxurl, {'action': 'close_addon_filters'});
			jQuery(this).closest('.fiaf-container').hide();
			return false;
		});
	}
})(jQuery);
