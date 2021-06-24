function parseFloatLocal(num) {
	return parseFloat(num.replace(",", "."));
}

jQuery.noConflict();
(function($) {
	$(function() {

		var dates = $( "input.datepicker" ).datepicker({
			dateFormat: "yy-mm-dd",
		    changeMonth: true,
		    changeYear: true,
			showButtonPanel: true
		});

		var show_signatures = $('#inspire_invoices_show_signatures');
		var signature_row = $('tr.signature-user');

		if( show_signatures.length ) {
			if( show_signatures.prop('checked') ) {
				signature_row.show();
			}

			show_signatures.click(function() {
				if( $(this).prop('checked') ) {
					signature_row.show();
				} else {
					signature_row.hide();
				}
			});
		}

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

        var roles_input = jQuery('#inspire_invoices_roles');
        if (roles_input.length) {
            roles_input.select2({
                width: '400px',
                ...select2_translations,
            });
        }

        var _invoice_country_user_select = $('.country-select2');
        if( _invoice_country_user_select.length ) {
            _invoice_country_user_select.select2({
                ...select2_translations,
            })
        }

		var _invoice_users_select = $('#inspire_invoice_client_select');
		if( _invoice_users_select.length ) {
			options = {
				width: '200px',
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					delay: 300,
					type: 'POST',
					data: function (params) {
						return {
							action: 'woocommerce-invoice-user-select',
							name: params.term,
							security: inspire_invoice_params.ajax_nonce
						};
					},
					processResults: function(data) {
						return {
							results: data.items
						};
					},
					cache: true,
				},
				minimumInputLength: 3,
                ...select2_translations
			};

			if ( $.fn.selectWoo ) {
				_invoice_users_select.selectWoo( options );
			} else if( $.fn.select2 ) {
				_invoice_users_select.select2( options );
			}

		}

        // Hide Owner Metabox Content in Invoice Edit
        $( '#owner.postbox' ).addClass( 'closed' );

        $('.order_actions.column-order_actions a.button.tips, .generate-invoice').each(function(index, item) {
            if ($(item).attr('href').indexOf('woocommere-generate-document') !== -1 || $(item).attr('href').indexOf('woocommerce-invoice-generate-bill') !== -1)
            {
                $(item).click(function(e) {
                    e.preventDefault();
                    let current = $(this);
                    if( current.hasClass( 'disabled' ) ) {
                        return false;
                    } else {
                        current.hide();
                        current.closest( '#the-list').find( '.wc_actions .generate-invoice' ).addClass( 'disabled' );
                        $.post($(item).attr('href'), '', function(result) {
                            if (result.data.result === 'OK')
                            {
                                current.after( result.data.html );
                                current.closest( '#the-list').find( '.wc_actions .generate-invoice' ).removeClass( 'disabled' );
                            } else {
                                alert( result.data.result );
                            }
                        });
                    }

                });

            }
        });

		function moneyMultiply(a, b)
		{
			if (a === 0 || b === 0)
			{
				return 0;
			}
		    var log_10 = function (c) { return Math.log(c) / Math.log(10); },
		        ten_e  = function (d) { return Math.pow(10, d); },
		        pow_10 = -Math.floor(Math.min(log_10(a), log_10(b))) + 1;
		    var mul = ((a * ten_e(pow_10)) * (b * ten_e(pow_10))) / ten_e(pow_10 * 2);

		    if (isNaN(mul) || !isFinite(mul))
	    	{
		    	return 0;
	    	} else {
	    		return mul;
	    	}
		}

		function getVatRateFromField(field)
		{
			return parseInt( field.val().split('|')[1], 10);
		}


		function invoiceRefreshProductNetPriceSum($productHandle)
		{
			$('[name=product\\[net_price_sum\\]\\[\\]]', $productHandle).val(
				moneyMultiply(
					parseFloatLocal( $('[name=product\\[net_price\\]\\[\\]]', $productHandle).val() ),
					parseFloatLocal( $('[name=product\\[quantity\\]\\[\\]]', $productHandle).val() )
				).toFixed(2)
			);
			invoiceRefreshProductVatRate($productHandle);
		}

		function invoiceRefreshProductVatRate($productHandle)
		{
			var vatType = getVatRateFromField($('[name=product\\[vat_type\\]\\[\\]]', $productHandle));

			$('[name=product\\[vat_sum\\]\\[\\]]', $productHandle).val(
				moneyMultiply(
					parseFloatLocal( $('[name=product\\[net_price_sum\\]\\[\\]]', $productHandle).val() ),
					(isNaN(vatType)? 0: vatType) / 100
				).toFixed(2)
			);
			invoiceRefreshProductTotal($productHandle);
		}

		function invoiceRefreshProductTotal($productHandle)
		{
			var total = parseFloatLocal( $('[name=product\\[vat_sum\\]\\[\\]]', $productHandle).val() ) +
			parseFloatLocal( $('[name=product\\[net_price_sum\\]\\[\\]]', $productHandle).val() );
			$('[name=product\\[total_price\\]\\[\\]]', $productHandle).val(
				(
					(isNaN(total)? 0: total).toFixed(2)
				)
			);
			invoiceRefreshTotal();
		}

		function invoiceRefreshTotal()
		{
			var price = 0.0;
			$('.product_row [name=product\\[total_price\\]\\[\\]]').each(function(index, item) {
				var val = parseFloatLocal( $(item).val() );
				price += isNaN(val)? 0: val;
			});

			$('[name=total_price]').val( price.toFixed(2) );
		}

		$('body.post-type-inspire_invoice .products_metabox')
			.on('click', '.remove_product', function(e) {
				e.preventDefault();

				$(this).parents('.product_row').remove();
				invoiceRefreshTotal();
			})
			.on('click', '.add_product', function(e) {
				e.preventDefault();

				var $container = $('.products_container');
				$('.product_prototype').clone()
					.find('input, select').prop('disabled', false).end()
					.removeClass('product_prototype')
					.appendTo($container)
					.show();
			})
			.on('change', '.refresh_net_price_sum', function(e) {
				var productHandle = $(this).parents('.product_row');
				invoiceRefreshProductNetPriceSum(productHandle);
			})
			.on('change', '.refresh_product', function(e) {
				var productHandle = $(this).parents('.product_row');
				var price = this.options[this.selectedIndex].dataset.price;

				productHandle[0].querySelector("input[name='product[net_price][]").value = price;

				invoiceRefreshProductNetPriceSum(productHandle);
			})
			.on('change', '.refresh_vat_sum', function(e) {
				var productHandle = $(this).parents('.product_row');
				invoiceRefreshProductVatRate(productHandle);
			})
			.on('change', '.refresh_total_price', function(e) {
				var productHandle = $(this).parents('.product_row');
				invoiceRefreshProductTotal(productHandle);
			})
			.on('change', '.refresh_total', function(e) {
				invoiceRefreshTotal();
			});

		$('body.post-type-inspire_invoice .get_user_data').click(function(e) {
			e.preventDefault();
			var $this = $(this);

			$.post(ajaxurl, {
					action: 'invoice-get-client-data',
					security: inspire_invoice_params.ajax_nonce,
					client: $this.parents('.form-field').find('select').val()
				}, function(result) {
					if (result.code === 100)
					{
						for (i in result.userdata)
						{
							$('[name=client\\[' + i + '\\]]').val(result.userdata[i]);
						}
					}
				}
			);
		});

		$('body.post-type-inspire_invoice .print_invoice').click(function(e) {
			e.preventDefault();
			var $this = $(this);

			function doAction()
			{
				//location.href = ajaxurl + '?action=invoice-get-pdf-invoice&id=' + $this.data('id') + '&hash=' + $this.data('hash');
				var url = ajaxurl + '?action=invoice-get-pdf-invoice&id=' + $this.data('id') + '&hash=' + $this.data('hash');
				window.open(url, '_blank');
			}

			if ($('body').hasClass('invoice-changed'))
			{
				if ( confirm(inspire_invoice_params.message_confirm) == true ) {
					doAction();
				}
			} else {
				doAction();
			}
		});

		$('body.post-type-inspire_invoice .download_invoice').click(function(e) {
			e.preventDefault();
			var $this = $(this);

			function doAction()
			{
				//location.href = ajaxurl + '?action=invoice-get-pdf-invoice&id=' + $this.data('id') + '&hash=' + $this.data('hash');
				var url = ajaxurl + '?action=invoice-get-pdf-invoice&id=' + $this.data('id') + '&hash=' + $this.data('hash') + '&save_file=1';
				window.open(url, '_blank');
			}

			if ($('body').hasClass('invoice-changed'))
			{
				if (confirm(inspire_invoice_params.message_confirm) === true) {
					doAction();
				}
			} else {
				doAction();
			}
		});

		$('body.post-type-inspire_invoice, body.post-type-inspire_invoice').on('change', 'select, input', function(e) {
			$('body').addClass('invoice-changed');
		});

        $('#payment').on('change', '#inspire_invoices_payment_status', function (e) {
            let field_to_change = $('#inspire_invoices_total_paid');
            if ($(this).val() === 'paid') {
                field_to_change.val($('#inspire_invoices_total_price').val());
            } else {
                field_to_change.val(0.0);
            }
        });

		$('body.post-type-inspire_invoice .send_invoice').click(function (e) {
			e.preventDefault();
			var $this = $(this);

			function doAction() {
				$.post(ajaxurl, {
						action: 'invoice-send-by-email',
						id: $this.data('id'),
						email_class: $this.data('type'),
						hash: $this.data('hash'),
						email: $('#inspire_invoices_client_email').val()
					}, function (result) {
						console.log(result);
						if (result.data.code === 100) {
							alert(inspire_invoice_params.message_invoice_sent + result.data.email);
						} else {
							if (result.data.code === 102) {
								alert(inspire_invoice_params.message_invoice_not_sent_woo);
							} else {
								alert(inspire_invoice_params.message_not_sent);
							}

						}
					}
				);
			}

			if ($('body').hasClass('invoice-changed')) {
				if (confirm(inspire_invoice_params.message_not_saved_changes) == true) {
					doAction();
				}
			} else {
				doAction();
			}
		});

		FiwG = {
			refreshList: function( productHandle ) {
				invoiceRefreshProductNetPriceSum(productHandle);
			},
		}

	});
})(jQuery);


