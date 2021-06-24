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
	$invoice->setDefaultValuesIfNumberEmpty();

?>

<input type="hidden" name="number" value="<?php echo $invoice->getNumber(); ?>" />

<div class="form-wrap inspire-panel">
	<div class="form-field form-required">
		<label for="inspire_invoices_date_issue"><?php _e( 'Issue date', 'flexible-invoices' ); ?></label>
		<input id="inspire_invoices_date_issue" type="text" class="datepicker" name="date_issue" value="<?php echo $invoice->getDateOfIssue(); ?>" />
	</div>

	<div class="form-field form-required">
		<label for="inspire_invoices_date_sale"><?php _e( 'Date of sale', 'flexible-invoices' ); ?></label>
		<input id="inspire_invoices_date_sale" type="text" class="datepicker" name="date_sale" value="<?php echo $invoice->getDateOfSale(); ?>" />
	</div>

	<div class="form-field form-required">
		<label for="inspire_invoices_date_pay"><?php _e( 'Due date', 'flexible-invoices' ); ?></label>
		<input id="inspire_invoices_date_pay" type="text" class="datepicker" name="date_pay" value="<?php echo $invoice->getDateOfPay(); ?>" />
	</div>

	<div class="metabox-actions">
		<button data-id="<?php echo $invoice->getId(); ?>" data-hash="<?php echo md5(NONCE_SALT . $invoice->getId()); ?>" class="button button-large download_invoice"><?php _e('Download Invoice', 'flexible-invoices'); ?></button>
		<?php do_action('inspire_invoices_after_display_options_metabox_actions', $invoice); ?>
	</div>
</div>
