<?php
    if (!function_exists('getInvoice'))
    {
        /**
         * for the ide syntax
         *
         * @return InvoicePost
         */
        function getInvoice($args)
        {
            return $args['invoice'];
        }
    }
	$invoice = getInvoice($args);
	$owner = $invoice->getOwner();
?>

<div class="form-wrap">
	<div class="form-field form-required">
		<label for="inspire_invoices_owner_name"><?php _e( 'Company Name', 'flexible-invoices' ); ?></label>
		<input id="inspire_invoices_owner_name" type="text" name="owner[name]" value="<?php echo $owner['name']; ?>" />
	</div>

	<div class="form-field form-required">
		<label for="inspire_invoices_owner_logo"><?php _e( 'Logo', 'flexible-invoices' ); ?></label>
		<input id="inspire_invoices_owner_logo" type="text" name="owner[logo]" value="<?php echo $owner['logo']; ?>" />
	</div>

	 <div class="form-field form-required">
		<label for="inspire_invoices_owner_address"><?php _e( 'Company Address', 'flexible-invoices' ); ?></label>
		<textarea id="inspire_invoices_owner_address" name="owner[address]"><?php echo $owner['address']; ?></textarea>
	</div>

	 <div class="form-field form-required">
		<label for="inspire_invoices_owner_nip"><?php _e( 'VAT Number', 'flexible-invoices' ); ?></label>
		<input id="inspire_invoices_owner_nip" type="text" name="owner[nip]" value="<?php echo $owner['nip']; ?>" />
	</div>

	 <div class="form-field form-required">
		<label for="inspire_invoices_owner_bank_name"><?php _e( 'Bank Name', 'flexible-invoices' ); ?></label>
		<input id="inspire_invoices_owner_bank_name" type="text" name="owner[bank]" value="<?php echo $owner['bank']; ?>" />
	</div>

	 <div class="form-field form-required">
		<label for="inspire_invoices_owner_account_number"><?php _e( 'Bank Account Number', 'flexible-invoices' ); ?></label>
		<input id="inspire_invoices_owner_account_number" type="text" name="owner[account]" value="<?php echo $owner['account']; ?>" />
	</div>

	 <div class="form-field form-required">
		<label for="inspire_invoices_owner_account_number"><?php _e( 'Seller signature', 'flexible-invoices' ); ?></label>
		 <?php
		 $selected = ! empty( $owner['signature_user'] ) ? $owner['signature_user'] : $args['signature_user'];
		 wp_dropdown_users(
			 [
				 'role__in' =>
					 [
						 'administrator',
						 'shop_manager',
						 'editor'
					 ],
				 'name'     => 'owner[signature_user]',
				 'selected' => $selected
			 ]
		 );
		 ?>
	</div>

</div>
