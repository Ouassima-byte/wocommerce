<?php
	if ( ! defined( 'ABSPATH' ) ) exit;
	
    if (!function_exists('getInvoice'))
    {
        /**
         * for the ide syntax
         *
         * @return InvoicePost
         */
        function getInvoice($args)
        {
            return $args['invoice'];
        }
    }
	$invoice = getInvoice($args);
	//$invoice->refreshTotals();
	global $woocommerce;
?>

<div class="form-wrap inspire-panel">
	<div class="options-group">
		<div class="form-field form-required">
			<label for="inspire_invoices_total_price"><?php _e( 'Total', 'flexible-invoices' ); ?></label>
			<input id="inspire_invoices_total_price" type="text" class="currency" name="total_price" value="<?php echo $invoice->getTotalPrice(); ?>" readonly />
		</div>

		<div class="form-field form-required">
			<label for="inspire_invoices_total_paid"><?php _e( 'Paid', 'flexible-invoices' ); ?></label>
			<input id="inspire_invoices_total_paid" type="text" class="currency" name="total_paid" value="<?php echo $invoice->getTotalPaid(); ?>" />
		</div>

		 <div class="form-field form-required">
			<label for="inspire_invoices_payment_status"><?php _e( 'Payment status', 'flexible-invoices' ); ?></label>
			<?php $paymentStatuses = $args['plugin']->invoicePostType->getPaymentStatuses(); ?>
			<select name="payment_status" id="inspire_invoices_payment_status">
				<?php foreach ($paymentStatuses as $val => $name): ?>
					<option value="<?php echo $val; ?>" <?php if ($invoice->getPaymentStatus() == $val): ?>selected="selected"<?php endif; ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="form-field form-required">
			<label for="inspire_invoices_currency"><?php _e( 'Currency', 'flexible-invoices' ); ?></label>
			<?php $paymentCurrencies = $args['plugin']->invoicePostType->getPaymentCurrencies(); ?>
			<select name="currency" id="inspire_invoices_currency">
				<?php foreach ($paymentCurrencies as $val => $name): ?>
					<option value="<?php echo $val; ?>" <?php if ($invoice->getCurrency() == $val): ?>selected="selected"<?php endif; ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
				<?php if ( $invoice->getCurrency() && empty( $paymentCurrencies[$invoice->getCurrency()] ) ) : ?>
					<option value="<?php echo $invoice->getCurrency(); ?>" selected="selected"><?php echo $invoice->getCurrency(); ?></option>
				<?php endif; ?>
			</select>
		</div>

		<div class="form-field form-required">
			<label for="inspire_invoices_payment_method"><?php _e( 'Payment method', 'flexible-invoices' ); ?></label>
			<?php $paymentMethods = $args['plugin']->invoicePostType->getPaymentMethods(); ?>
			<?php $paymentMethodsWoo = $args['plugin']->invoicePostType->getPaymentMethodsWoo(); ?>
			<?php $paymentMethods = $args['plugin']->invoicePostType->appendPaymentMethod( $paymentMethods, $paymentMethodsWoo, $invoice ); ?>
			<select name="payment_method" id="inspire_invoices_payment_method">
				<?php if ( sizeof( $paymentMethodsWoo) ) : ?>
					<optgroup label="<?php _e( 'WooCommerce', 'flexible-invoices' ); ?>">
				<?php endif; ?>
				<?php foreach ($paymentMethodsWoo as $val => $name): ?>
					<option value="<?php echo $val; ?>" <?php if ($invoice->getPaymentMethod() == $val): ?>selected="selected"<?php endif; ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
				<?php if ( sizeof( $paymentMethodsWoo) ) : ?>
					</optgroup>
				<?php endif; ?>
				<?php if ( sizeof( $paymentMethodsWoo) ) : ?>
					<optgroup label="<?php _e( 'Standard', 'flexible-invoices' ); ?>">
				<?php endif; ?>
				<?php foreach ($paymentMethods as $val => $name): ?>
					<option value="<?php echo $val; ?>" <?php if ($invoice->getPaymentMethod() == $val): ?>selected="selected"<?php endif; ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
				<?php if ( sizeof( $paymentMethodsWoo) ) : ?>
					</optgroup>
				<?php endif; ?>
			</select>
		</div>
	</div>

	<div class="options-group">
		<div class="form-field form-required">
			<label for="inspire_invoices_notes"><?php _e( 'Notes', 'flexible-invoices' ); ?></label>
			<textarea id="inspire_invoices_notes" type="text" class="fluid" name="notes"><?php echo $invoice->getNotes(); ?></textarea>
		</div>
	</div>

	<?php do_action('inspire_invoices_after_display_payment_metabox_actions', $invoice); ?>
</div>
