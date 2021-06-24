<?php

namespace WPDeskFIVendor;

/**
 * Email z fakturą (plain text)
 */
/**
 * @var $order WC_Order
 */
if (!\defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
echo $email_heading . "\n\n";
echo "****************************************************\n\n";
if (isset($download_url)) {
    \printf(\__('Download Invoice: %s', 'flexible-invoices'), $download_url) . "\n\n";
}
echo \sprintf(\__('Order number: %s', 'woocommerce'), $order->get_order_number()) . "\n";
echo \sprintf(\__('Order date: %s', 'woocommerce'), \date_i18n(\wc_date_format(), \strtotime($order->get_date_created()))) . "\n";
\do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);
echo "\n";
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\BaseEmail::get_email_order_items($order, \true);
echo "----------\n\n";
if ($totals = $order->get_order_item_totals()) {
    foreach ($totals as $total) {
        echo $total['label'] . "\t " . $total['value'] . "\n";
    }
}
echo "\n****************************************************\n\n";
\do_action('woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email);
echo \__('Your details', 'woocommerce') . "\n\n";
if ($order->get_billing_email()) {
    echo \__('Email', 'woocommerce');
}
?>: <?php 
echo $order->get_billing_email() . "\n";
if ($order->get_billing_phone()) {
    echo \__('Phone', 'woocommerce');
}
?>: <?php 
echo $order->get_billing_phone() . "\n";
\wc_get_template('emails/plain/email-addresses.php', array('order' => $order));
echo "\n****************************************************\n\n";
echo \apply_filters('woocommerce_email_footer_text', \get_option('woocommerce_email_footer_text'));
