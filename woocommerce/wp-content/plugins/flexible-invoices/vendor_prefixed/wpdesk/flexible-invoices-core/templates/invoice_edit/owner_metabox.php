<?php

namespace WPDeskFIVendor;

/**
* @var array $params
*/
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
$params = isset($params) ? $params : [];
/**
 * @var Document $invoice
 */
$invoice = $params['invoice'];
/**
 * @var Seller $owner
 */
$owner = $params['owner'];
$document_issuing = 'Manual Issuing Proforma and Invoices';
?>
<div class="form-wrap inspire-panel">
	<?php 
/**
 * Fires before owner meta box is rendered.
 *
 * @param Document $invoice Document type.
 * @param array    $params  Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/owner/before', $invoice, $params);
?>

	<div class="form-field form-required">
		<label for="inspire_invoices_owner_name"><?php 
\_e('Company Name', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_owner_name" type="text" name="owner[name]" class="medium hs-beacon-search" value="<?php 
echo $owner->get_name();
?>" />
	</div>

	<div class="form-field form-required">
		<label for="inspire_invoices_owner_logo"><?php 
\_e('Logo', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_owner_logo" type="text" name="owner[logo]" class="medium hs-beacon-search" value="<?php 
echo $owner->get_logo();
?>" />
	</div>

	 <div class="form-field form-required">
		<label for="inspire_invoices_owner_address"><?php 
\_e('Company Address', 'flexible-invoices');
?></label>
		<textarea data-beacon_search="<?php 
echo $document_issuing;
?>" class="hs-beacon-search" id="inspire_invoices_owner_address" name="owner[address]"><?php 
echo $owner->get_address();
?></textarea>
	</div>

	 <div class="form-field form-required">
		<label for="inspire_invoices_owner_nip"><?php 
\_e('VAT Number', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_owner_nip" type="text" name="owner[nip]" class="medium hs-beacon-search" value="<?php 
echo $owner->get_vat_number();
?>" />
	</div>

	 <div class="form-field form-required">
		<label for="inspire_invoices_owner_bank_name"><?php 
\_e('Bank Name', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_owner_bank_name" type="text" name="owner[bank]" class="medium hs-beacon-search" value="<?php 
echo $owner->get_bank_name();
?>" />
	</div>

	 <div class="form-field form-required">
		<label for="inspire_invoices_owner_account_number"><?php 
\_e('Bank Account Number', 'flexible-invoices');
?></label>
		<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_owner_account_number" type="text" name="owner[account]" class="medium hs-beacon-search" value="<?php 
echo $owner->get_bank_account_number();
?>" />
	</div>

	 <div class="form-field form-required">
		<label for="inspire_invoices_owner_account_number"><?php 
\_e('Seller signature', 'flexible-invoices');
?></label>
		 <?php 
$selected = !empty($owner->get_signature_user()) ? $owner->get_signature_user() : '';
\wp_dropdown_users(['role__in' => ['administrator', 'shop_manager', 'editor'], 'name' => 'owner[signature_user]', 'selected' => $selected]);
?>
	</div>
	<?php 
/**
 * Fires after owner meta box is rendered.
 *
 * @param Document $invoice Document type.
 * @param array    $params  Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/owner/after', $invoice, $params);
?>

</div>
<?php 
