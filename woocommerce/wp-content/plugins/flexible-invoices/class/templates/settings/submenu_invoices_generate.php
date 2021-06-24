<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="generate_form">
  <form id="download" action="<?php echo wp_nonce_url( admin_url('admin-ajax.php')); ?>" method="get" target="_blank">
		<input type="hidden" name="action" value="woocommerce-invoice-batch-download" />
	    <?php wp_nonce_field( 'download_invoices', 'batch_download' ); ?>

		<h3><?php echo __( 'Download Invoices', 'flexible-invoices' ); ?></h3>

		<p><?php echo __( 'If the download fails select smaller date range and try again.', 'flexible-invoices' ); ?></p>

		<p>
			<label for="from"><?php echo __( 'From:', 'flexible-invoices' ); ?></label>
			<input type="text" value="<?php echo date('Y-m-d'); ?>" id="from" name="start_date">

			<label for="to"><?php echo __( 'To:', 'flexible-invoices' ); ?></label>
			<input type="text" value="<?php echo date('Y-m-d'); ?>" id="to" name="end_date">
		</p>

		<p><input type="submit" value="<?php echo __( 'Download', 'flexible-invoices' ); ?>" class="button button-primary" id="download_submit" name="submit"></p>
	</form>
</div>
