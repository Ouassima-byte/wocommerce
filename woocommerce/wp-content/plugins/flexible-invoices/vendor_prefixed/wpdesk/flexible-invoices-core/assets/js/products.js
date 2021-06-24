(function ($) {
	"use strict";

	var FlexibleInvoiceAdmin = {

		editProductName: function () {
			jQuery('.products_container').on('click', '.edit_item_name', function () {
				let is_input = $(this).closest('td').find('.item_input_name');
				let select_input = $(this).closest('td').find('select');
				let select = $(this).closest('td').find('.product_select_name');
				let select_products = $(this).closest('td').find('.select-product');

				if (!is_input.length) {
					select_input.attr( 'name', 'product_name' );
					select_products.hide();
					let value = select_input.find("option:first-child").val();
					select.append('<input type="text" class="item_input_name" name="product[name][]" value="' + value + '" /> ');
				} else {
					select_input.attr( 'name', 'product[name][]' );
					select_products.show();
					is_input.remove();
				}

				return false;
			});
		},

		/**
		 * Search products.
		 */
        initProductsSelect2: function (elem) {
            var select2_translations = {
                placeholder: inspire_invoice_params.select2_placeholder,
                language: {
                    inputTooShort: function (args) {
                        var remainingChars = args.minimum - args.input.length;
                        return inspire_invoice_params.select2_min_chars.replace('%', remainingChars);
                    },
                    loadingMore: function () {
                        return inspire_invoice_params.select2_loading_more;
                    },
                    noResults: function () {
                        return inspire_invoice_params.select2_no_results;
                    },
                    searching: function () {
                        return inspire_invoice_params.select2_searching;
                    },
                    errorLoading: function () {
                        return inspire_invoice_params.select2_error_loading;
                    },
                },
            };
            if (elem.length) {
                elem.select2({
                    ajax: {
                        url: ajaxurl,
                        dataType: 'json',
                        delay: 300,
                        type: 'POST',
                        data: function (params) {
                            return {
                                action: 'fiw_find_products',
                                name: params.term,
                                security: fiw_localize.nonce
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data.items
                            };
                        },
                        cache: true,
                    },
                    minimumInputLength: 3,
                    ...select2_translations,
                    width: '100%',
                });
            }
            elem.on('select2:select', function (e) {
                var data = e.params.data;
                var productHandle = $(this).parents('.product_row');
                productHandle[0].querySelector("input[name='product[net_price][]']").value = data.net_price;
                productHandle[0].querySelector("input[name='product[net_price_sum][]']").value = data.net_price;
                productHandle[0].querySelector("input[name='product[vat_sum][]']").value = data.tax_amount;
                productHandle[0].querySelector("input[name='product[sku][]']").value = data.sku;
                productHandle[0].querySelector("input[name='product[quantity][]']").value = 1;
                productHandle[0].querySelector("input[name='product[unit][]']").value = '';
                productHandle[0].querySelector("input[name='product[total_price][]']").value = data.gross_price;
            });
        },


    }


	jQuery('#products').on('click', '.add_product', function (e) {
		let lastelem = jQuery('.products_container').find('.refresh_product').last();
		lastelem.next().remove();
		FlexibleInvoiceAdmin.initProductsSelect2(lastelem);
	})

	if (jQuery('.products_metabox').length) {
		jQuery('.refresh_product').each(function () {
			FlexibleInvoiceAdmin.initProductsSelect2($(this));
		});
	}

	FlexibleInvoiceAdmin.editProductName();

})(jQuery);
