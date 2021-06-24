<?php

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Currency;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo;

/**
 * @var Document $invoice
 */
$invoice = isset( $params['invoice'] ) ? $params['invoice'] : false;

/**
 * @var Currency $helper ;
 */
$helper = isset( $params['currency_helper'] ) ? $params['currency_helper'] : false;

/**
 * @var LibraryInfo $plugin
 */
$library_info = isset( $params['library_info'] ) ? $params['library_info'] : false;

/**
 * @var  MetaPostContainer $meta
 */
$meta = isset( $params['meta'] ) ? $params['meta'] : false;

/**
 * @var Translator $translator
 */
$translator = isset( $params['translator'] ) ? $params['translator'] : false;

/**
 * @var Settings $settings
 */
$settings = isset( $params['settings'] ) ? $params['settings'] : false;

$client         = $invoice->get_customer();
$client_country = $client->get_country();

$owner    = $invoice->get_seller();
$products = $invoice->get_items();

$pkwiuEmpty = true;
if ( ! is_array( $products ) ) {
    $products = array();
}
foreach ( $products as $product ) {
    if ( ! empty( $product['sku'] ) ) {
        $pkwiuEmpty = false;
    }
}

$is_vat_exempt                  = $meta->get( '_is_vat_except' );
$is_self_declared               = $meta->get( '_vat_number_self_declared' );
$customer_self_declared_country = $meta->get( '_customer_self_declared_country' );

$has_vat       = ! $invoice->get_total_tax() || (float) $invoice->get_total_tax() !== 0.00;
$hideVat       = $settings->get( 'hide_vat' ) === 'yes' && ! $has_vat;
$hideVatNumber = $settings->get( 'hide_vat_number' ) === 'yes' && ! $has_vat;

$translator::switch_lang( $invoice->get_user_lang() );
$translator::set_translate_lang( $invoice->get_user_lang() );

?>
<!DOCTYPE HTML>
<html lang="<?php echo get_locale(); ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?php echo $invoice->get_formatted_number(); ?></title>
    <link href="<?php echo $library_info->get_assets_url(); ?>css/reset.css" rel="stylesheet" type="text/css"
          media="screen,print"/>
    <link href="<?php echo $library_info->get_assets_url(); ?>css/print.css" rel="stylesheet" type="text/css"
          media="print"/>
    <link href="<?php echo $library_info->get_assets_url(); ?>css/front.css" rel="stylesheet" type="text/css"
          media="screen,print"/>
    <?php do_action( 'fi/core/template/invoice' ); ?>
</head>
<body>
<div id="wrapper" class="invoice">
    <div id="header">
        <table>
            <tbody>
            <tr>
                <td>
                    <?php if ( ! empty( $owner->get_logo() ) ): ?>
                        <div id="logo">
                            <img alt="" class="logo" src="<?php echo $owner->get_logo(); ?>"/>
                        </div>
                    <?php endif; ?>
                </td>

                <td id="dates">
                    <p><?php echo trim( $translator::translate_meta( 'inspire_invoices_invoice_date_of_sale_label', __( 'Date of sale', 'flexible-invoices' ) ) ); ?>: <strong><?php echo $invoice->get_date_of_sale(); ?></strong></p>
                    <p><?php echo __( 'Issue date', 'flexible-invoices' ); ?>: <strong><?php echo $invoice->get_date_of_issue(); ?></strong></p>
                    <?php if ( $invoice->get_date_of_pay() > 0 ): ?>
                        <p><?php echo __( 'Due date', 'flexible-invoices' ); ?>: <strong><?php echo $invoice->get_date_of_pay(); ?></strong></p>
                    <?php endif; ?>
                    <?php $paymentMethod = $invoice->get_payment_method_name(); ?>
                    <?php if ( ! empty( $paymentMethod ) ): ?>
                        <p><?php echo __( 'Payment method', 'flexible-invoices' ); ?>: <strong><?php echo $paymentMethod; ?></strong></p>
                    <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="fix"></div>

        <div id="companies">
            <div class="seller">
                <p class="title"><?php echo __( 'Seller', 'flexible-invoices' ); ?>:</p>

                <?php if ( ! empty( $owner->get_name() ) ): ?>
                    <p class="name"><?php echo $owner->get_name(); ?></p>
                <?php endif; ?>

                <p class="details"><?php echo nl2br( ! empty( $owner->get_address() ) ? $owner->get_address() : '' ); ?></p>

                <?php if ( ! empty( $owner->get_vat_number() ) && ! $hideVatNumber ): ?>
                    <p class="nip"><?php echo __( 'VAT Number', 'flexible-invoices' ); ?>
                        : <?php echo $owner->get_vat_number(); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $owner->get_bank_name() ) ): ?>
                    <p><?php echo __( 'Bank', 'flexible-invoices' ); ?>: <?php echo $owner->get_bank_name(); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $owner->get_bank_account_number() ) ): ?>
                    <p><?php echo __( 'Account number', 'flexible-invoices' ); ?>
                        : <?php echo $owner->get_bank_account_number(); ?></p>
                <?php endif; ?>

            </div>

            <div class="buyer">
                <p class="title"><?php echo __( 'Buyer', 'flexible-invoices' ); ?>:</p>

                <p>
                    <?php if ( ! empty( $client->get_name() ) ): ?>
                        <span><?php echo $client->get_name() ?></span><br/>
                    <?php endif; ?>
                    <?php if ( ! empty( $client->get_street() ) ): ?>
                        <span><?php echo $client->get_street() ?></span><br/>
                    <?php endif; ?>

                    <?php if ( ! empty( $client->get_postcode() ) ): ?>
                        <span><?php echo $client->get_postcode() ?></span>
                    <?php endif; ?>

                    <?php if ( ! empty( $client->get_city() ) ): ?>
                        <span><?php echo $client->get_city() ?>,</span>
                        <?php if ( ! empty( $client->get_country() ) ): ?>
                            <span><?php echo $client->get_country() ?></span><br/>
                        <?php endif; ?>
                    <?php elseif ( ! empty( $client->get_postcode() ) ): ?>
                        <span><?php echo $client->get_country() ?></span>
                        <br/>
                    <?php else: ?>
                        <span><?php echo ! empty( $client->get_country() ) ? $client->get_country() : ''; ?></span>
                    <?php endif; ?>
                </p>

                <?php if ( ! empty( $client->get_vat_number() ) ): ?>
                    <p><?php echo __( 'VAT Number', 'flexible-invoices' ); ?>
                        : <?php echo $client->get_vat_number(); ?></p>
                <?php endif; ?>
            </div>

            <div class="fix"></div>
        </div>
        <div class="fix"></div>
    </div>

    <p class="report-title"><?php echo $invoice->get_formatted_number(); ?></p>

    <table cellpadding="0" cellspacing="0">
        <thead>
        <?php
        $price_label  = $hideVat ? __( 'Price', 'flexible-invoices' ) : __( 'Net price', 'flexible-invoices' );
        $amount_label = $hideVat ? __( 'Amount', 'flexible-invoices' ) : __( 'Net amount', 'flexible-invoices' );
        ?>

        <tr>
            <th><?php echo __( '#', 'flexible-invoices' ); ?></th>
            <th width="30%"><?php echo __( 'Name', 'flexible-invoices' ); ?></th>
            <?php if ( ! $pkwiuEmpty ): ?>
                <th><?php echo __( 'SKU', 'flexible-invoices' ); ?></th>
            <?php endif; ?>
            <th><?php echo __( 'Quantity', 'flexible-invoices' ); ?></th>
            <th><?php echo __( 'Unit', 'flexible-invoices' ); ?></th>
            <th><?php echo $price_label; ?></th>
            <th><?php echo $amount_label; ?></th>
            <?php if ( ! $hideVat ): ?>
                <th><?php echo __( 'Tax rate', 'flexible-invoices' ); ?></th>
                <th><?php echo __( 'Tax amount', 'flexible-invoices' ); ?></th>
                <th><?php echo __( 'Gross amount', 'flexible-invoices' ); ?></th>
            <?php endif; ?>
        </tr>
        </thead>

        <tbody>
        <?php
        $index             = 0;
        $total_tax_amount  = 0;
        $total_net_price   = 0;
        $total_gross_price = 0;

        $total_tax_net_price   = array();
        $total_tax_tax_amount  = array();
        $total_tax_gross_price = array();
        ?>
        <?php foreach ( $products as $item ): ?>
            <?php
            $index ++;
            ?>
            <tr>
                <td class="center"><?php echo $index; ?></td>
                <td><?php echo $item['name']; ?></td>
                <?php if ( ! $pkwiuEmpty ): ?>
                    <td><?php echo wordwrap( $item['sku'], 6, "\n", true ); ?></td>
                <?php endif; ?>
                <td class="quantity number"><?php echo $item['quantity']; ?></td>
                <td class="unit center"><?php echo $item['unit']; ?></td>
                <td class="net-price number"><?php echo $helper->string_as_money( $item['net_price'] ); ?></td>

                <td class="total-net-price number"><?php echo $helper->string_as_money( $item['net_price_sum'] ); ?></td>
                <?php if ( ! $hideVat ): ?>
                    <td class="tax-rate number"><?php echo $item['vat_type_name']; ?></td>
                    <td class="tax-amount number"><?php echo $helper->string_as_money( $item['vat_sum'] ); ?></td>
                    <td class="total-gross-price number"><?php echo $helper->string_as_money( $item['total_price'] ); ?></td>
                <?php endif; ?>

                <?php
                $total_net_price   += $item['net_price_sum'];
                $total_tax_amount  += $item['vat_sum'];
                $total_gross_price += $item['total_price'];

                if ( ! empty( $item['vat_type_name'] ) ) {
                    $total_tax_net_price[ $item['vat_type_name'] ]   = @floatval( $total_tax_net_price[ $item['vat_type_name'] ] ) + $item['net_price_sum'];
                    $total_tax_tax_amount[ $item['vat_type_name'] ]  = @floatval( $total_tax_tax_amount[ $item['vat_type_name'] ] ) + $item['vat_sum'];
                    $total_tax_gross_price[ $item['vat_type_name'] ] = @floatval( $total_tax_gross_price[ $item['vat_type_name'] ] ) + $item['total_price'];
                }
                ?>
            </tr>
        <?php endforeach; ?>

        </tbody>

        <tfoot>
        <tr class="total">
            <td class="empty">&nbsp;</td>
            <td class="empty">&nbsp;</td>
            <td class="empty">&nbsp;</td>
            <td class="empty">&nbsp;</td>
            <?php if ( ! $pkwiuEmpty ): ?>
                <td class="empty">&nbsp;</td>
            <?php endif; ?>

            <td class="sum-title"><?php echo __( 'Total', 'flexible-invoices' ); ?></td>
            <td id="total_sum_net_price" class="number"><?php echo $helper->string_as_money( $total_net_price ); ?></td>
            <?php if ( ! $hideVat ): ?>
                <td class="number">X</td>
                <td id="total_sum_tax_price"
                    class="number"><?php echo $helper->string_as_money( $total_tax_amount ); ?></td>
                <td id="total_sum_gross_price"
                    class="number"><?php echo $helper->string_as_money( $total_gross_price ); ?></td>
            <?php endif; ?>
        </tr>

        <?php if ( ! $hideVat ): ?>
            <?php foreach ( $total_tax_net_price as $taxType => $price ): ?>
                <tr>
                    <td class="empty">&nbsp;</td>
                    <td class="empty">&nbsp;</td>
                    <td class="empty">&nbsp;</td>
                    <td class="empty">&nbsp;</td>
                    <?php if ( ! $pkwiuEmpty ): ?>
                        <td class="empty">&nbsp;</td>
                    <?php endif; ?>
                    <td class="sum-title"><?php echo __( 'Including', 'flexible-invoices' ); ?></td>
                    <td class="number"><?php echo $helper->string_as_money( $price ); ?></td>
                    <td class="number"><?php echo $taxType; ?></td>
                    <td class="number"><?php echo $helper->string_as_money( $total_tax_tax_amount[ $taxType ] ); ?></td>
                    <td class="number"><?php echo $helper->string_as_money( $total_tax_gross_price[ $taxType ] ); ?></td>
                </tr>
            <?php endforeach; ?>

        <?php endif; ?>

        </tfoot>
    </table>
    <?php do_action( 'flexible_invoices_before_total', $invoice, $products, $client ); ?>
    <?php
    $total_section = '<table class="totals">
    			<tbody>
    				<tr>
    					<td id="total_price" width="33.3%">' . esc_html__( 'Total', 'flexible-invoices' ) . ': <strong>' . $helper->string_as_money( $invoice->get_total_gross() ) . '</strong></td>
						<td id="paid_price" width="33.3%">' . esc_html__( 'Paid', 'flexible-invoices' ) . ': <strong>' . $helper->string_as_money( $invoice->get_total_paid() ) . '</strong></td>
						<td id="due_price" width="33.3%">' . esc_html__( 'Due', 'flexible-invoices' ) . ': <strong>' . $helper->string_as_money( CalculateTotals::calculate_due_price( $invoice->get_total_gross(), $invoice->get_total_paid() ) ) . '</strong></td>
    				</tr>
    			</tbody>
    		</table>';
    echo apply_filters( 'flexible_invoices_total', $total_section, $invoice, $products, $client );
    ?>
    <?php do_action( 'flexible_invoices_after_total', $invoice, $products, $client ); ?>

    <?php if ( $settings->get( 'show_signatures' ) === 'yes' ): ?>
        <div id="signatures">
            <table>
                <tr>
                    <td>
                        <p class="user"></p>
                        <p>&nbsp;</p>
                        <p>........................................</p>
                    </td>

                    <td width="15%"></td>

                    <td>
                        <?php if ( ! empty( $owner->get_signature_user() ) && ! empty( $owner->get_signature_user() ) ): ?>
                            <p class="user">
                                <?php
                                $user = get_user_by( 'id', $owner->get_signature_user() );
                                if ( isset( $user->data->display_name ) && ! empty( $user->data->display_name ) ) {
                                    echo $user->data->display_name;
                                } else {
                                    echo $user->data->user_login;
                                }
                                ?>
                            </p>
                        <?php endif; ?>
                        <p>&nbsp;</p>
                        <p>........................................</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p><?php echo __( 'Buyer signature', 'flexible-invoices' ); ?></p>
                    </td>

                    <td width="15%"></td>

                    <td>
                        <p><?php echo __( 'Seller signature', 'flexible-invoices' ); ?></p>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <?php $note = $invoice->get_notes(); ?>
    <?php if ( ! empty( $note ) ): ?>
        <div id="footer">
            <p><strong><?php echo __( 'Notes', 'flexible-invoices' ); ?></strong></p>
            <p><?php echo str_replace( PHP_EOL, '<br/>', $note ); ?></p>
        </div>
    <?php endif; ?>

    <?php do_action( 'flexible_invoices_after_notes', $client_country, $hideVat, $hideVatNumber, $invoice ); ?>
    <?php do_action( 'fi/core/template/invoice/after_notes', $invoice ); ?>

    <?php if ( $invoice->get_show_order_number() ): ?>
        <?php $order = $invoice->get_order_number(); ?>
        <p><?php echo __( 'Order number', 'flexible-invoices' ); ?>: <?php echo $invoice->get_order_number(); ?></p>
    <?php endif; ?>

    <div class="fix"></div>
</div>
<input type="hidden" name="document_id" value="<?php echo esc_attr( $invoice->get_id() ); ?>"/>
<input type="hidden" name="order_id" value="<?php echo esc_attr( $invoice->get_order_id() ); ?>"/>
<div class="no-page-break"></div>
</body>
</html>
