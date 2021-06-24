<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;
$params = isset($params) ? $params : [];
/**
 * @var Document $invoice
 */
$invoice = $params['invoice'];
/**
 * @var Customer $client
 */
$client = $params['client'];
?>

<div class="form-wrap inspire-panel">
	<?php 
/**
 * Fires before client meta box is rendered.
 *
 * @param Document $invoice Document type.
 * @param array    $params  Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/client/before', $invoice, $params);
$document_issuing = 'Manual Issuing Proforma and Invoices';
?>

	<div class="form-field">
		<label><?php 
\_e('Customer', 'flexible-invoices');
?></label>
		<div id="inspire_invoice_client_select_wrap">
			<select data-beacon_search="<?php 
echo $document_issuing;
?>" class="hs-beacon-search" id="inspire_invoice_client_select"></select>
		</div>
		<button class="button get_user_data"><?php 
\_e('Fetch client data', 'flexible-invoices');
?></button>
	</div>

	<div class="form-field">
		<label for="inspire_invoices_client_name"><?php 
\_e('Client Name', 'flexible-invoices');
?></label>
        <input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_name" type="text" class="medium hs-beacon-search" name="client[name]" value="<?php 
echo \esc_attr($client->get_name());
?>" />
	</div>

	 <div class="form-field">
		<label for="inspire_invoices_client_street"><?php 
\_e('Address line 1', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_street" type="text" class="medium hs-beacon-search" name="client[street]" value="<?php 
echo \esc_attr($client->get_street());
?>" />
	</div>

    <div class="form-field">
        <label for="inspire_invoices_client_street2"><?php 
\_e('Address line 2', 'flexible-invoices');
?></label>
        <input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_street2" type="text" class="medium hs-beacon-search" name="client[street2]" value="<?php 
echo \esc_attr($client->get_street2());
?>" />
    </div>

	<div class="form-field">
		<label for="inspire_invoices_client_postcode"><?php 
\_e('Zip code', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_postcode" type="text" class="medium hs-beacon-search" name="client[postcode]" value="<?php 
echo \esc_attr($client->get_postcode());
?>" />
	</div>

	<div class="form-field">
		<label for="inspire_invoices_client_city"><?php 
\_e('City', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_city" type="text" class="medium hs-beacon-search" name="client[city]" value="<?php 
echo \esc_attr($client->get_city());
?>" />
	</div>

	 <div class="form-field">
		<label for="inspire_invoices_client_nip"><?php 
\_e('VAT Number', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_nip" type="text" class="medium hs-beacon-search" name="client[nip]" value="<?php 
echo \esc_attr($client->get_vat_number());
?>" />
	</div>

    <?php 
$fake_option = '';
$countries = [];
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    $countries = \WC()->countries->get_countries();
}
$client_country = $client->get_country();
if (!isset($countries[$client_country]) && !empty($client_country)) {
    $fake_option = '<option selected="selected" value="' . $client_country . '">' . $client_country . '</option>';
}
if (empty($client_country)) {
    $client_country = \get_option('woocommerce_default_country');
}
?>

	<div class="form-field">
		<label for="inspire_invoices_client_country"><?php 
\_e('Country', 'flexible-invoices');
?></label>
        <?php 
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    ?>
            <select id="inspire_invoices_client_country" name="client[country]" class="country-select2 medium hs-beacon-search">
                <?php 
    echo $fake_option;
    ?>
                <?php 
    foreach ($countries as $country_code => $country_name) {
        ?>
                    <option <?php 
        \selected($country_code, $client_country);
        ?> value="<?php 
        echo $country_code;
        ?>"><?php 
        echo $country_name;
        ?></option>
                <?php 
    }
    ?>
            </select>
        <?php 
} else {
    ?>
		    <input id="inspire_invoices_client_country" type="text" class="medium" name="client[country]" value="<?php 
    echo \esc_attr($client_country);
    ?>" />
	    <?php 
}
?>
    </div>

	<div class="form-field">
		<label for="inspire_invoices_client_phone"><?php 
\_e('Phone', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_phone" type="text" class="medium hs-beacon-search" name="client[phone]" value="<?php 
echo \esc_attr($client->get_phone());
?>" />
	</div>

	<div class="form-field">
		<label for="inspire_invoices_client_email"><?php 
\_e('Email', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_email" type="text" class="medium hs-beacon-search" name="client[email]" value="<?php 
echo \esc_attr($client->get_email());
?>" />
	</div>

	<?php 
/**
 * Fires after client meta box is rendered.
 *
 * @param Document $invoice Document type.
 * @param array    $params  Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/client/after', $invoice, $params);
?>
</div>
<?php 
