<?php

namespace WPDeskFIVendor;

$params = isset($params) ? $params : '';
?>
<div class="address">
	<p class="form-field form-field-wide">
		<?php 
if ('yes' === $params['add_invoice_ask_field']) {
    ?>
   			<strong><?php 
    \_e('I want an invoice:', 'flexible-invoices');
    ?></strong> <?php 
    echo \__($params['billing_invoice_ask_display'], 'flexible-invoices');
    ?><br />
   		<?php 
}
?>
   		<?php 
if (!empty($params['billing_vat_number'])) {
    ?>
	   		<strong><?php 
    echo $params['nip_label'];
    ?>:</strong> <?php 
    echo $params['billing_vat_number'];
    ?>
   		<?php 
}
?>
	</p>
</div>

<div class="edit_address">
	<?php 
\woocommerce_wp_checkbox($params['billing_invoice_ask_field']);
\woocommerce_wp_text_input($params['billing_vat_number_field']);
?>
</div>
<?php 
