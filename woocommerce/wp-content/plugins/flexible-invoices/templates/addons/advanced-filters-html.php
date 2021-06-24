<?php

global $post_type;

$params           = isset( $params ) ? (array) $params : [];
$currencies       = [];
$countries        = [];
$payment_methods  = [];
$taxes            = [];
$document_types   = [];
$price_types      = [];
$date_types       = [];
$payment_statuses = [];

$filter_price_type      = 'gross';
$filter_price_from      = '';
$filter_price_to        = '';
$filter_date_type       = '';
$filter_date_from       = '';
$filter_date_to         = '';
$filter_client_name     = '';
$filter_client_country  = [];
$filter_payment_status  = [];
$filter_currency        = [];
$filter_tax_values      = '';
$filter_payment_methods = [];
$filter_document_types  = [];
$filter_vat_number      = 0;

$woo_countries = WC()->countries->get_countries();
?>
<script id="invoice-filters-html-addon" type="text/template">
		<div class="container-fluid fiaf-container">
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Ukryj komunikat.</span></button>
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-4 col-lg-2">
					<div class="field-row">
						<h4><?php esc_html_e( 'Price', 'flexible-invoices' ); ?></h4>
						<select class="select2-single" name="filter_price_type">
							<option value=""><?php esc_html_e( 'Gross price', 'flexible-invoices' ); ?></option>
						</select>
					</div>
					<div class="field-row field-half">
						<input name="filter_price_from" type="text" value="<?php echo $filter_price_from; ?>" placeholder="<?php esc_html_e( 'From', 'flexible-invoices' ); ?>" />
						&ndash;
						<input name="filter_price_to" type="text" value="<?php echo $filter_price_to; ?>" placeholder="<?php esc_html_e( 'To', 'flexible-invoices' ); ?>" />
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-4 col-lg-2">
					<div class="field-row">
						<h4><?php esc_html_e( 'Dates', 'flexible-invoices' ); ?></h4>
						<select class="select2-single" name="filter_date_type">
							<option value=""><?php esc_html_e( 'Issue date', 'flexible-invoices' ); ?></option>
						</select>
					</div>
					<div class="field-row field-half">
						<input name="filter_date_from" type="date" value="<?php echo $filter_date_from; ?>" placeholder="<?php esc_html_e( 'From', 'flexible-invoices' ); ?>" />
						&ndash;
						<input name="filter_date_to" type="date" value="<?php echo $filter_date_to; ?>" placeholder="<?php esc_html_e( 'To', 'flexible-invoices' ); ?>" />
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-4 col-lg-2">
					<div class="field-row">
						<h4><?php esc_html_e( 'Client', 'flexible-invoices' ); ?></h4>
						<select class="select2-ajax" name="filter_client_name" data-allow-clear="true" data-placeholder="<?php esc_html_e( 'Client name', 'flexible-invoices' ); ?>">
							<option value=""><?php esc_html_e( '---', 'flexible-invoices' ); ?></option>
						</select>
					</div>
					<div class="field-row">
						<select class="select2-multiple" name="filter_client_country[]" data-allow-clear="true">
							<option value=""><?php esc_html_e( 'Country', 'flexible-invoices' ); ?></option>
						</select>
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-4 col-lg-2">
					<div class="field-row">
						<h4><?php esc_html_e( 'Payment', 'flexible-invoices' ); ?></h4>
						<select class="select2-multiple" name="filter_payment_status[]" data-allow-clear="true" data-placeholder="<?php esc_html_e( 'Payment status', 'flexible-invoices' ); ?>">
							<option value=""><?php esc_html_e( 'Paid', 'flexible-invoices' ); ?></option>
						</select>
					</div>
					<div class="field-row">
						<select class="select2-multiple" name="filter_currency[]" data-allow-clear="true" data-placeholder="<?php esc_html_e( 'Currency', 'flexible-invoices' ); ?>">
							<option value=""><?php echo get_option( 'woocommerce_currency', 'USD' ); ?></option>
						</select>
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-4 col-lg-2">
					<h4 style="visibility: hidden"><?php esc_html_e( 'Payment', 'flexible-invoices' ); ?></h4>
					<div class="field-row">
						<select class="select2-single" name="filter_tax_value">
							<option value=""><?php esc_html_e( 'All taxes', 'flexible-invoices' ); ?></option>
						</select>
					</div>
					<div class="field-row">
						<select class="select2-multiple" name="filter_payment_method[]" data-allow-clear="true" data-placeholder="<?php esc_html_e( 'Payment method', 'flexible-invoices' ); ?>">
							<option value=""><?php esc_html_e( 'Bacs', 'flexible-invoices' ); ?></option>
						</select>
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-4 col-lg-2">
					<div class="field-row">
						<h4><?php esc_html_e( 'Document Type', 'flexible-invoices' ); ?></h4>
						<select class="select2-multiple" name="filter_document_type[]" data-allow-clear="true" data-placeholder="<?php esc_html_e( 'Document', 'flexible-invoices' ); ?>">
							<option value=""><?php esc_html_e( 'Invoice', 'flexible-invoices' ); ?></option>
						</select>
					</div>
					<div class="field-row">
						<label><input name="filter_vat_number" type="checkbox" value="1" /> <?php esc_html_e( 'Show only with vat number', 'flexible-invoices' ); ?> </label>
					</div>
				</div>
			</div>
			<div class="row submit-filters">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<?php
					$bundle_link = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/pakiet-faktury/?utm_source=wp-admin-plugins&utm_medium=button&utm_campaign=flexible-invoices-bundle' : 'https://flexibleinvoices.com/?utm_source=wp-admin-plugins&utm_medium=button&utm_campaign=flexible-invoices-bundle';
					?>
					<p><a target="_blank" class="button button-primary" href="<?php echo $bundle_link; ?>"><?php esc_html_e( 'Buy PRO bundle including all addons &rarr;', 'flexible-invoices' ); ?></a></p>
				</div>
			</div>
		</div>
</script>
