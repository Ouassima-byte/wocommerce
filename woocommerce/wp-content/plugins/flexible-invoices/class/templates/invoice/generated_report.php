<?php if ( ! defined( 'ABSPATH' ) ) exit; ?><!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title><?php _e( 'Report', 'flexible-invoices' ); ?> <?php echo $_GET['start_date']; ?> - <?php echo $_GET['end_date']; ?></title>

	<?php do_action( 'flexible_invoices_head' ); ?>

</head>
<body>
	<div id="wrapper" class="report">
		<div id="header">
			<?php if ($this->isSettingValue('company_logo')): ?>
				<div id="logo">
					<img src="<?php echo $this->getSettingValue('company_logo'); ?>" />
				</div>
			<?php endif; ?>

			<div id="company">
				<p class="name"><?php echo $this->getSettingValue('company_name'); ?></p>

				<p class="details"><?php echo nl2br($this->getSettingValue('company_address')); ?></p>
			</div>

			<div class="fix"></div>
		</div>

		<p class="report-title"><?php _e('Report:', 'flexible-invoices'); ?> <?php echo $_GET['start_date']; ?> - <?php echo $_GET['end_date']; ?>, <?php _e( 'Currency:', 'flexible-invoices'); ?> <?php echo $_GET['currency']; ?></p>

		<table cellpadding="0" cellspacing="0">
		    <thead>
		    	<tr>
		    		<th><?php _e( 'Invoice', 'flexible-invoices' ); ?></th>
		    		<th><?php _e( 'Customer', 'flexible-invoices' ); ?></th>
		    		<th><?php _e( 'Net value', 'flexible-invoices' ); ?></th>
		    		<th><?php _e( 'Tax value', 'flexible-invoices' ); ?></th>
		    		<th><?php _e( 'Gross value', 'flexible-invoices' ); ?></th>
		    	</tr>
		    </thead>

		    <tbody>
		    	<?php
		    		//$decimal_places = get_option( 'woocommerce_price_num_decimals', 2 );
		    		$decimal_places = 2;
			    	function invoice_filter_where( $where = '' ) {
			    		$where .= " AND meta_value >= '" . strtotime(date('Y-m-d 00:00:00', strtotime($_GET['start_date']))) . "' AND meta_value <= '" . strtotime(date('Y-m-d 23:59:59', strtotime($_GET['end_date']))) . "'";
			    		return $where;
			    	}
			    	add_filter( 'posts_where', 'invoice_filter_where' );

		    		$raportQuery = new WP_Query( array(
		    			'post_type' => 'inspire_invoice',
		    			'orderby' => 'date',
		    			'order' => 'ASC',
		    			'post_status' => 'publish',
		    			'nopaging' => true,
	    		        'meta_key' => '_date_issue'
		    		) );

		    		$invoices = $raportQuery->get_posts();

		    		remove_filter( 'posts_where', 'filter_where' );

		    		$totalNet = 0;
		    		$totalTax = 0;
		    		$totalGoss = 0;
		    	?>

		    	<?php $currencySymbol = ''; ?>
		    	<?php foreach ($invoices as $item): ?>
		    		<?php
		    			$invoice = new InvoicePost($item->ID, Invoice::getInstance());
		    			//$order = $invoice->getOrder();

		    			if ( $invoice->getCurrency() != $_GET['currency'] ) {
		    				continue;
		    			}

		    			$totalNet += ($net = round( $invoice->getTotalNet(), $decimal_places ) );
			    		$totalTax += ($tax = round( $invoice->getTotalTax(), $decimal_places )  );
			    		$totalGoss += ($total = round( $invoice->getTotalPrice(), $decimal_places ) );

			    		$currencySymbol = $invoice->getCurrencySymbol();

			    		$client = $invoice->getClient();
		    		?>
			    	<tr>
			    		<td><?php echo $invoice->getFormattedInvoiceNumber(); ?></td>
			    		<td><?php echo @$client['name']; ?></td>
		    			<td class="number"><?php echo $invoice->stringAsMoney($net); ?></td>
		    			<td class="number"><?php echo $invoice->stringAsMoney($tax); ?></td>
			    		<td class="number"><?php echo  $invoice->stringAsMoney($total); ?></td>
			    	</tr>

		    	<?php endforeach; ?>
		    </tbody>

		    <tfoot>
		    	<tr class="total">
		    		<td class="empty">&nbsp;</td>
		    		<td class="sum-title"><?php _e('Total', 'flexible-invoices'); ?></td>
		    		<td class="number"><?php echo number_format( $totalNet, 2, $args['currency_decimal_separator'], ''); ?> <?php echo $currencySymbol; ?></td>
		    		<td class="number"><?php echo number_format( $totalTax, 2, $args['currency_decimal_separator'], ''); ?> <?php echo $currencySymbol; ?></td>
		    		<td class="number"><?php echo number_format( $totalGoss, 2, $args['currency_decimal_separator'], ''); ?> <?php echo $currencySymbol; ?></td>
		    	</tr>
		    </tfoot>
		</table>

		<?php if ( $this->getSettingValue( 'show_signatures' ) ): ?>
		<div id="signature">
			<?php if( $user_id = $this->getSettingValue('signature_user') ): ?>
			<p class="user">
				<?php
				$user = get_user_by( 'id', $user_id );
				if( isset( $user->data->display_name ) && ! empty( $user->data->display_name ) ) {
					echo $user->data->display_name;
				} else {
					echo $user->data->user_login;
				}
				?>
			</p>
			<?php endif; ?>
			<p>&nbsp;</p>
			<p>........................................</p>
		</div>
		<?php endif; ?>

		<div class="fix"></div>
	</div>

	<div class="no-page-break"></div>
</body>
</html>
