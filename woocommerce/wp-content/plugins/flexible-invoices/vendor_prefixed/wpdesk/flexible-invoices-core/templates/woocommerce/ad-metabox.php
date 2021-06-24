<?php

namespace WPDeskFIVendor;

/**
 * Scoper fix
 */
?>
<div class="stuffbox">
	<h3 class="hndle"><?php 
\_e('Get more WP Desk Plugins!', 'flexible-invoices');
?></h3>
	<?php 
$ad_links = [['link' => \esc_attr__('https://www.wpdesk.net/products/woocommerce-print-orders-address-labels/?utm_source=flexible-invoices-settings&amp;utm_medium=link&amp;utm_campaign=woocommerce-print-orders-address-labels', 'flexible-invoices'), 'label' => \esc_html__('Print Orders and Address Labels', 'flexible-invoices'), 'descr' => \esc_html__('- Speed up the fulfillment process, packing and shipping by printing address labels and order details.', 'flexible-invoices')], ['link' => \esc_attr__('https://www.wpdesk.net/products/active-payments-woocommerce/?utm_source=flexible-invoices-settings&amp;utm_medium=link&amp;utm_campaign=active-payments-plugin', 'flexible-invoices'), 'label' => \esc_html__('Active Payments', 'flexible-invoices'), 'descr' => \esc_html__('- Conditionally hide payment methods for cash on delivery shipping options. Add fees to payment methods.', 'flexible-invoices')], ['link' => \esc_attr__('https://www.wpdesk.net/products/flexible-pricing-woocommerce/?utm_source=flexible-invoices-settings&amp;utm_medium=link&amp;utm_campaign=flexible-pricing-woocommerce', 'flexible-invoices'), 'label' => \esc_html__('Flexible Pricing', 'flexible-invoices'), 'descr' => \esc_html__('- Create promotions like Buy One Get One Free to get more sales in your store.', 'flexible-invoices')]];
?>
	<div class="inside">
		<div class="main">
			<div class="main">
				<?php 
foreach ($ad_links as $ad_link) {
    ?>
					<?php 
    echo \sprintf('<p><a href="%s" target="_blank">%s</a> %s</p>', $ad_link['link'], $ad_link['label'], $ad_link['descr']);
    ?>
				<?php 
}
?>
			</div>
		</div>
	</div>
</div>
<?php 
