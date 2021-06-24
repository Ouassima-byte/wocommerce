var invoice_ask_field = jQuery('#billing_invoice_ask');
var vat_number_field = jQuery('#billing_vat_number');
if (invoice_ask_field.length) {
    invoice_ask_field.change(function () {
        var vat_number_p = vat_number_field.closest('p');
        if (invoice_ask_field.is(':checked')) {
            vat_number_p.show();
        } else {
            vat_number_p.hide();
            vat_number_field.val('');
        }
    });
    jQuery(document).ready(function () {
        if (vat_number_field.val().length > 2) {
            invoice_ask_field.prop('checked', true);
        }
        invoice_ask_field.trigger('change');
    })
}

