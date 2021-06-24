<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php if ( wpdesk_is_plugin_active( 'flexible-invoices-reports/flexible-invoices-reports.php' ) ): ?>

    <div class="notice notice-success">
        <p><?php _e( 'You have Flexible Invoices Advanced Reports active.', 'flexible-invoices' ); ?> <a href="<?php echo admin_url( 'edit.php?post_type=inspire_invoice&page=flexible-invoices-reports-settings' ); ?>"><?php _e( 'Go to Advanced Reports &rarr;', 'flexible-invoices' ); ?></a></p>
    </div>

<?php else: ?>

    <div class="notice notice-success">
        <table>
            <tbody>
                <tr>
                    <td width="70%">
                        <p><strong><?php _e( 'Buy Advanced Reports for Flexible Invoices to get:', 'flexible-invoices' ); ?></strong></p>

                        <ul>
                            <li><span class="dashicons dashicons-yes"></span> <?php _e( 'Adjust columns displayed in the reports.', 'flexible-invoices' ); ?></li>
                            <li><span class="dashicons dashicons-yes"></span> <?php _e( 'Advanced filtering by: issue date, sale date, payment date, taxes, currencies.', 'flexible-invoices' ); ?></li>
                            <li><span class="dashicons dashicons-yes"></span> <?php _e( 'Include or exclude invoices for WooCommerce orders.', 'flexible-invoices' ); ?></li>
                            <li><span class="dashicons dashicons-yes"></span> <?php _e( 'Sorting by issue date, sale date, payment date.', 'flexible-invoices' ); ?></li>
                        </ul>
                    </td>

                    <td>
                        <a class="button button-primary button-hero" href="<?php _e( 'https://www.wpdesk.net/products/flexible-invoices-advanced-reports/', 'flexible-invoices' ); ?>?utm_source=flexible-invoices&utm_campaign=flexible-invoices-reports&utm_medium=button" target="_blank"><?php _e( 'Buy Advanced Reports &rarr;', 'flexible-invoices' ); ?></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

<?php endif; ?>

<div id="generate_form">
	<form action="<?php echo wp_nonce_url( admin_url('admin-ajax.php')); ?>" method="get" target="_blank">
		<input type="hidden" name="action" value="woocommerce-invoice-generate-report" />
		<?php wp_nonce_field( 'download_report', 'report_download' ); ?>
        <h3><?php _e( 'Reports', 'flexible-invoices' ); ?></h3>

		<p>
			<label for="from"><?php _e( 'From:', 'flexible-invoices' ); ?></label>
			<input type="text" value="<?php echo date('Y-m-d'); ?>" id="from" name="start_date">

			<label for="to"><?php _e( 'To:', 'flexible-invoices' ); ?></label>
			<input type="text" value="<?php echo date('Y-m-d'); ?>" id="to" name="end_date">
		</p>

		<p>
			<label for="currency"><?php _e( 'Currency:', 'flexible-invoices' ); ?></label>
			<select name="currency" id="currency">
				<?php
					$currencies = get_option( 'inspire_invoices_currency', array() );
					foreach ( $currencies as $currency ) {
						?>
						<option value="<?php echo $currency['currency']; ?>"><?php echo $currency['currency']; ?></option>
						<?php
					}
				?>
			</select>
		</p>


		<p class="submit"><input type="submit" value="<?php _e( 'Generate', 'flexible-invoices' ); ?>" class="button button-primary" id="submit" name="submit"></p>

	</form>
</div>
