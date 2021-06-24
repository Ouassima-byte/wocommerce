<?php

namespace WPDeskFIVendor;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Currency;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\WordPressIntegration;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo;
/**
 * @var Document[] $invoice
 */
$documents = isset($params['documents']) ? $params['documents'] : \false;
/**
 * @var array $post_data
 */
$post_data = isset($params['post_data']) ? (array) $params['post_data'] : [];
/**
 * @var LibraryInfo $library_info
 */
$library_info = isset($params['library_info']) ? $params['library_info'] : \false;
/**
 * @var Settings $settings
 */
$settings = isset($params['settings']) ? $params['settings'] : \false;
/**
 * @var Currency $helper;
 */
$helper = isset($params['currency_helper']) ? $params['currency_helper'] : \false;
$currency_helper = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Currency($post_data['currency']);
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php 
\_e('Report', 'flexible-invoices');
?> <?php 
echo $post_data['start_date'];
?> - <?php 
echo $post_data['end_date'];
?></title>
    <link href="<?php 
echo $library_info->get_assets_url();
?>css/reset.css" rel="stylesheet" type="text/css" media="screen,print"/>
    <link href="<?php 
echo $library_info->get_assets_url();
?>css/print.css" rel="stylesheet" type="text/css" media="print"/>
    <link href="<?php 
echo $library_info->get_assets_url();
?>css/front.css" rel="stylesheet" type="text/css" media="screen,print"/>
	<?php 
\do_action('fi/core/template/report');
?>
</head>
<body>
	<div id="wrapper" class="report">
		<div id="header">
			<?php 
if ($settings->has('company_logo')) {
    ?>
				<div id="logo">
					<img class="logo" alt="" src="<?php 
    echo $settings->get('company_logo');
    ?>" />
				</div>
			<?php 
}
?>

			<div id="company">
				<p class="name"><?php 
echo $settings->get('company_name');
?></p>

				<p class="details"><?php 
echo \nl2br($settings->get('company_address'));
?></p>
			</div>

			<div class="fix"></div>
		</div>

		<p class="report-title"><?php 
\_e('Report:', 'flexible-invoices');
?> <?php 
echo $post_data['start_date'];
?> - <?php 
echo $post_data['end_date'];
?>, <?php 
\_e('Currency:', 'flexible-invoices');
?> <?php 
echo $post_data['currency'];
?></p>

		<table cellpadding="0" cellspacing="0">
		    <thead>
		    	<tr>
		    		<th><?php 
\_e('Invoice', 'flexible-invoices');
?></th>
		    		<th><?php 
\_e('Customer', 'flexible-invoices');
?></th>
		    		<th><?php 
\_e('Net value', 'flexible-invoices');
?></th>
		    		<th><?php 
\_e('Tax value', 'flexible-invoices');
?></th>
		    		<th><?php 
\_e('Gross value', 'flexible-invoices');
?></th>
		    	</tr>
		    </thead>

		    <tbody>
		    	<?php 
$decimal_places = 2;
$total_net = 0;
$total_tax = 0;
$total_gross = 0;
?>

		    	<?php 
$currencySymbol = '';
?>
		    	<?php 
foreach ($documents as $document) {
    ?>
		    		<?php 
    /**
     * @var Document $document
     */
    if ($document->get_currency() !== $post_data['currency']) {
        continue;
    }
    $total_net += $net = $currency_helper->number_format($document->get_total_net());
    $total_tax += $tax = $currency_helper->number_format($document->get_total_tax());
    $total_gross += $total = $currency_helper->number_format($document->get_total_gross());
    $currencySymbol = $document->get_currency_symbol();
    $client = $document->get_customer();
    ?>
			    	<tr>
			    		<td><?php 
    echo $document->get_formatted_number();
    ?></td>
			    		<td><?php 
    echo $client->get_name();
    ?></td>
		    			<td class="number"><?php 
    echo $currency_helper->string_as_money($net);
    ?></td>
		    			<td class="number"><?php 
    echo $currency_helper->string_as_money($tax);
    ?></td>
			    		<td class="number"><?php 
    echo $currency_helper->string_as_money($total);
    ?></td>
			    	</tr>

		    	<?php 
}
?>
		    </tbody>

		    <tfoot>
		    	<tr class="total">
		    		<td class="empty">&nbsp;</td>
		    		<td class="sum-title"><?php 
\_e('Total', 'flexible-invoices');
?></td>
		    		<td class="number"><?php 
echo \number_format($total_net, 2, $params['currency_decimal_separator'], '');
?> <?php 
echo $currencySymbol;
?></td>
		    		<td class="number"><?php 
echo \number_format($total_tax, 2, $params['currency_decimal_separator'], '');
?> <?php 
echo $currencySymbol;
?></td>
		    		<td class="number"><?php 
echo \number_format($total_gross, 2, $params['currency_decimal_separator'], '');
?> <?php 
echo $currencySymbol;
?></td>
		    	</tr>
		    </tfoot>
		</table>

		<?php 
if ($settings->get('show_signatures') === 'yes') {
    ?>
		<div id="signature">
			<?php 
    if ($user_id = $settings->get('signature_user')) {
        ?>
			<p class="user">
				<?php 
        $user = \get_user_by('id', $user_id);
        if (isset($user->data->display_name) && !empty($user->data->display_name)) {
            echo $user->data->display_name;
        } else {
            echo $user->data->user_login;
        }
        ?>
			</p>
			<?php 
    }
    ?>
			<p>&nbsp;</p>
			<p>........................................</p>
		</div>
		<?php 
}
?>

		<div class="fix"></div>
	</div>

	<div class="no-page-break"></div>
</body>
</html>
<?php 
