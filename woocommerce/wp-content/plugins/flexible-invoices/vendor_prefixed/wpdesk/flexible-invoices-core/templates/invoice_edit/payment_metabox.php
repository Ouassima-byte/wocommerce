<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
$params = isset($params) ? $params : [];
/**
 * @var \WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $invoice
 */
$invoice = $params['invoice'];
$payment_statuses = isset($params['payment_statuses']) ? $params['payment_statuses'] : '';
$payment_currencies = isset($params['payment_currencies']) ? $params['payment_currencies'] : '';
$payment_methods = isset($params['payment_methods']) ? $params['payment_methods'] : '';
$document_issuing = 'Manual Issuing Proforma and Invoices';
?>

<div class="form-wrap inspire-panel">
	<?php 
/**
 * Fires before payment meta box is rendered.
 *
 * @param Document          $document Document type.
 * @param array $params     Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/payment/before', $invoice, $params);
?>
	<div class="options-group">
		<div class="form-field form-required">
			<input  type="hidden" class="currency" name="number" value="<?php 
echo $invoice->get_number();
?>" />
			<input  type="hidden" class="medium medium-text currency" name="formatted_number" value="<?php 
echo $invoice->get_formatted_number();
?>" />
			<label for="inspire_invoices_total_price"><?php 
\_e('Total', 'flexible-invoices');
?></label>
			<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_total_price" type="text" class="currency hs-beacon-search" name="total_price" value="<?php 
echo $invoice->get_total_gross();
?>" readonly />
		</div>

		<div class="form-field form-required">
			<label for="inspire_invoices_total_paid"><?php 
\_e('Paid', 'flexible-invoices');
?></label>
			<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_total_paid" type="text" class="currency hs-beacon-search" name="total_paid" value="<?php 
echo $invoice->get_total_paid();
?>" />
		</div>

		 <div class="form-field form-required">
			<label for="inspire_invoices_payment_status"><?php 
\_e('Payment status', 'flexible-invoices');
?></label>
			<select data-beacon_search="<?php 
echo $document_issuing;
?>" class="hs-beacon-search" name="payment_status" id="inspire_invoices_payment_status">
				<?php 
foreach ($payment_statuses as $val => $name) {
    ?>
					<option value="<?php 
    echo $val;
    ?>" <?php 
    if ($invoice->get_payment_status() == $val) {
        ?>selected="selected"<?php 
    }
    ?>><?php 
    echo $name;
    ?></option>
				<?php 
}
?>
			</select>
		</div>

		<div class="form-field form-required">
			<label for="inspire_invoices_currency"><?php 
\_e('Currency', 'flexible-invoices');
?></label>
			<select data-beacon_search="<?php 
echo $document_issuing;
?>" class="hs-beacon-search" name="currency" id="inspire_invoices_currency">
				<?php 
foreach ($payment_currencies as $val => $name) {
    ?>
					<option value="<?php 
    echo $val;
    ?>" <?php 
    if ($invoice->get_currency() == $val) {
        ?>selected="selected"<?php 
    }
    ?>><?php 
    echo $name;
    ?></option>
				<?php 
}
?>
				<?php 
if ($invoice->get_currency() && empty($payment_currencies[$invoice->get_currency()])) {
    ?>
					<option value="<?php 
    echo $invoice->get_currency();
    ?>" selected="selected"><?php 
    echo $invoice->get_currency();
    ?></option>
				<?php 
}
?>
			</select>
		</div>

		<div class="form-field form-required">
			<label for="inspire_invoices_payment_method"><?php 
\_e('Payment method', 'flexible-invoices');
?></label>
			<select data-beacon_search="<?php 
echo $document_issuing;
?>" class="hs-beacon-search" name="payment_method" id="inspire_invoices_payment_method">
				<?php 
if (\sizeof($payment_methods['woocommerce'])) {
    ?>
					<optgroup label="<?php 
    \_e('WooCommerce', 'flexible-invoices');
    ?>">
					<?php 
    foreach ($payment_methods['woocommerce'] as $val => $name) {
        ?>
						<option value="<?php 
        echo $val;
        ?>" <?php 
        if ($invoice->get_payment_method() == $val) {
            ?>selected="selected"<?php 
        }
        ?>><?php 
        echo $name;
        ?></option>
					<?php 
    }
    ?>
					</optgroup>
				<?php 
}
?>

				<?php 
if (\sizeof($payment_methods['standard'])) {
    ?>
					<optgroup label="<?php 
    \_e('Basic', 'flexible-invoices');
    ?>">
					<?php 
    foreach ($payment_methods['standard'] as $val => $name) {
        ?>
						<option value="<?php 
        echo $val;
        ?>" <?php 
        if ($invoice->get_payment_method() == $val) {
            ?>selected="selected"<?php 
        }
        ?>><?php 
        echo $name;
        ?></option>
					<?php 
    }
    ?>
					</optgroup>
				<?php 
}
?>
			</select>
		</div>
	</div>

	<div class="options-group">
		<div class="form-field form-required">
			<label for="inspire_invoices_notes"><?php 
\_e('Notes', 'flexible-invoices');
?></label>
			<textarea data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_notes" class="fluid hs-beacon-search" name="notes"><?php 
echo $invoice->get_notes();
?></textarea>
		</div>
	</div>
    <?php 
global $post;
$is_order = (int) \get_post_meta($post->ID, '_wc_order_id', \true);
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active() && $is_order) {
    ?>
        <div class="form-field lonely">
            <label>
                <input data-beacon_search="<?php 
    echo $document_issuing;
    ?>" class="hs-beacon-search" type="checkbox" name="add_order_id" value="1" <?php 
    \checked($invoice->get_show_order_number(), 1, \true);
    ?> /> <?php 
    \esc_html_e('Add order number to an invoice', 'flexible-invoices');
    ?>
            </label>
        </div>
    <?php 
}
?>
	<?php 
/**
 * Fires after payment meta box is rendered.
 *
 * @param Document          $document Document type.
 * @param array $params     Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/payment/after', $invoice, $params);
?>
    <input type="hidden" name="wc_order_id" value="<?php 
echo \esc_attr($invoice->get_order_id());
?>" />
</div>
<?php 
