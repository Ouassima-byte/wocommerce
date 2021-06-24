(function ($) {
    "use strict";

    var FISettings = {

        showDependentNipFields: function () {
            var field = $("#woocommerce_add_nip_field");
            if (field.length) {
                field.change(function () {
                    if ($(this).is(':checked')) {
                        $('.nip-additional-fields').closest('tr').show();
                    } else {
                        $('.nip-additional-fields').closest('tr').hide();
                    }
                });
                field.trigger('change');
            }
        },

        showDependentExchangeFields: function () {
            var field = $('#woocommerce_currency_exchange_enable');
            if (field.length) {
                field.change(function () {
                    if ($(this).is(':checked')) {
                        $('.exchange-table-fields').closest('tr').show();
                    } else {
                        $('.exchange-table-fields').closest('tr').hide();
                    }
                });
                field.trigger('change');
            }
        },

        showDependentMossFields: function () {
            let nip_field = $('#woocommerce_add_nip_field');
            var field = $('#woocommerce_eu_vat_vies_validate');
            if (field.length) {
                field.change(function () {
                    if ($(this).is(':checked')) {
                        $('.vies-validation-fields').closest('tr').show();
                    } else {
                        $('.vies-validation-fields').closest('tr').hide();
                    }
                });
                field.trigger('change');
            }
        },


        taxes: function () {

            var fixHelper = function (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            };

            $('#flexible_invoices_tax_table tbody').sortable({
                handle: 'td:first',
                helper: fixHelper,
            });

            let table = jQuery('#flexible_invoices_tax_table');
            if (table.length) {
                table.on('click', '#insert_tax', function () {
                    let last_row = $('#flexible_invoices_tax_table tbody tr').last().clone();
                    let index = parseInt($('.row-num', last_row).val());
                    let increaseIndex = parseInt(index) + 1;
                    last_row.find('input').attr('value', '');
                    $('.row-num', last_row).val(increaseIndex);
                    var inputname = new RegExp('\\[' + index + '\\]', 'gm');
                    let elem = last_row.html().replace(inputname, '[' + increaseIndex + ']');
                    $('#flexible_invoices_tax_table tbody').append('<tr>' + elem + '</tr>');
                    return false;
                });

                table.on('click', '.delete-item', function () {
                    let rows = $(this).closest('tbody').find('tr');
                    if (rows.length > 1) {
                        $(this).closest('tr').remove();
                    }

                    return false;
                });
            }
        },

        initSelect2: function () {
            var select = jQuery('.select2');
            if (select.length) {
                select.select2({
                    width: '400px'
                });
            }
        },

        subSettings: function() {
            let tab_cookie_name = 'general';
            let nav_active = jQuery( '.nav-tab-active' );
            if( nav_active.length ) {
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                tab_cookie_name = urlParams.get('tab');
            }
            let sub_table = jQuery( '.sub-table' );
            if( sub_table.length ) {
                jQuery( '.js-subsubsub-wrapper a' ).click( function () {
                    jQuery( this ).closest( 'ul' ).find( '.current' ).removeClass( 'current' );
                    jQuery( this ).addClass( 'current' );
                    jQuery( '.form-table' ).hide();
                    sub_table.hide();
                    jQuery( '.field-settings-' + jQuery( this ).attr( 'id' ).replace( 'tab-anchor-', '' ) ).show();
                    wpCookies.set( 'fi-settings-tab-' + tab_cookie_name, jQuery( this ).attr( 'id' ).replace( 'tab-anchor-', '' ) );
                } );


                var tab_cookie = wpCookies.get( 'fi-settings-tab-' + tab_cookie_name );
                if ( tab_cookie ) {
                    var tab_element = jQuery( '.sub-tab-' + tab_cookie );
                    tab_element.click();
                } else {
                    sub_table.hide();
                    sub_table.first().show()
                }
            }
        },

        proVersion: function() {
            if (typeof inspire_invoice_params !== 'undefined') {
                var pro_version_link = '<span class="pro-version"><a href="' + inspire_invoice_params.get_pro_version_url + '" target="_blank">' + inspire_invoice_params.get_pro_version_text + '</span>';
                let sub_table = jQuery( '.form-table' );
                if( sub_table.length ) {
                    let elements = sub_table.find( '.pro-version' );
                    elements.each( function( index ) {
                        $(this).attr( 'disabled', 'disabled' );
                        let parent = $(this).parent().prop('tagName');
                        if( parent === 'LABEL' ) {
                            $(this).parent().addClass( 'disable-for-pro' );
                            $(this).parent().append( pro_version_link );
                        } else {
                            $(this).after( pro_version_link );
                        }

                    })
                }

                let proforma_table = jQuery( 'table.pro-version' );
                if( proforma_table.length ) {
                    let elements = proforma_table.find( 'input,select,textarea' );
                    elements.each( function( index ) {
                        $(this).attr( 'disabled', 'disabled' );
                        if( parent === 'LABEL' ) {
                            $(this).parent().addClass( 'disable-for-pro' );
                        }
                    });

                    let headers = proforma_table.find( 'h2' );
                    headers.each( function( index ) {
                        //$(this).after( '<h4>' + pro_version_link + '</h4>' );
                    });

                }
            }


        },

        editDisabledFields: function () {
            let disabled_field = jQuery('.form-table .edit_disabled_field');
            if (disabled_field.length) {
                disabled_field.after('<a class="remove-disabled" href="#"><span class="dashicons dashicons-edit"></span></a>');
            }

            jQuery('.form-table').on('click', '.remove-disabled', function () {
                let input = $(this).prev();
                if (input.attr('disabled') === 'disabled') {
                    $(this).prev().removeAttr('disabled');
                } else {
                    $(this).prev().attr('disabled', 'disabled');
                }

                return false;
            });

        },

        datePicker: function () {
            var dates = $( "input.datepicker" ).datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true
            });
        },

    }

    FISettings.showDependentNipFields();
    FISettings.showDependentExchangeFields();
    FISettings.showDependentMossFields();
    FISettings.initSelect2();
    FISettings.editDisabledFields();
    FISettings.taxes();
    FISettings.subSettings();
    FISettings.proVersion();
    FISettings.datePicker();

})
(jQuery);
