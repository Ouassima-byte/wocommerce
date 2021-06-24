<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
	global $woocommerce;
?>
<form action="" method="post">
	<?php wp_nonce_field( 'save_settings', 'flexible_invoices_settings' ); ?>
	<?php settings_fields( 'inspire_invoices_settings' ); ?>

 	<?php if (!empty($_POST['option_page']) && $_POST['option_page'] === 'inspire_invoices_settings'): ?>
		<div id="message" class="updated fade"><p><strong><?php _e( 'Settings saved.', 'flexible-invoices' ); ?></strong></p></div>
	<?php endif; ?>

	<h3><?php _e( 'General Settings', 'flexible-invoices' ); ?></h3>

	<p><a href="<?php echo $args['docs_link']; ?>" target="_blank"><?php _e( 'Read user\'s manual →', 'flexible-invoices' ); ?></a></p>

	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_company_name"><?php _e( 'Company Name', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<input class="regular-text" value="<?php echo $this->getSettingValue('company_name'); ?>" id="inspire_invoices_company_name" name="inspire_invoices[company_name]" type="text" />
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_company_data"><?php _e( 'Company Address', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<textarea class="input-text wide-input" id="inspire_invoices_company_address" name="inspire_invoices[company_address]"><?php echo $this->getSettingValue('company_address'); ?></textarea>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_company_nip"><?php _e( 'VAT Number', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<input class="regular-text" value="<?php echo $this->getSettingValue('company_nip'); ?>" id="inspire_invoices_company_nip" name="inspire_invoices[company_nip]" type="text" />
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_company_logo"><?php _e( 'Logo', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<input class="regular-text" value="<?php echo $this->getSettingValue('company_logo'); ?>" id="inspire_invoices_company_logo" name="inspire_invoices[company_logo]" type="text" /> <br /><span class="description"><?php echo sprintf(__( 'Enter Logo URL which will be used on PDF invoices. Upload logo via <a href="%s" target="_blank">media uploader</a>.', 'flexible-invoices' ), admin_url('media-new.php')); ?></span>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_bank_name"><?php _e( 'Bank Name', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<input class="regular-text" value="<?php echo $this->getSettingValue('bank_name'); ?>" id="inspire_invoices_bank_name" name="inspire_invoices[bank_name]" type="text" />
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_account_number"><?php _e( 'Bank Account Number', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<input class="regular-text" value="<?php echo $this->getSettingValue('account_number'); ?>" id="inspire_invoices_account_number" name="inspire_invoices[account_number]" type="text" />
				</td>
			</tr>
		</tbody>
	</table>

	<h3><?php _e( 'Invoices Settings', 'flexible-invoices' ); ?></h3>

	<p><?php _e( 'For prefixes and suffixes use the following short tags: {DD} for day, {MM} for month, {YYYY} for year.', 'flexible-invoices' ); ?></p>

	<table class="form-table">
		<tbody>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="inspire_invoices_number_reset_type"><?php _e( 'Invoice Number Reset', 'flexible-invoices' ); ?></label>
                </th>

                <td class="forminp forminp-text">
                    <select id="inspire_invoices_number_reset_type" name="inspire_invoices[number_reset_type]">
                        <option value="year" <?php echo $this->getSettingValue('number_reset_type', 'year') == 'year' ? 'selected' : '' ; ?>><?php _e( 'Yearly', 'flexible-invoices' ); ?></option>
                        <option value="month" <?php echo $this->getSettingValue('number_reset_type', 'year') == 'month' ? 'selected' : '' ; ?>><?php _e( 'Monthly', 'flexible-invoices' ); ?></option>
                        <option value="none" <?php echo $this->getSettingValue('number_reset_type', 'year') == 'none' ? 'selected' : '' ; ?>><?php _e( 'None', 'flexible-invoices' ); ?></option>
                    </select>
                    <br/>
                    <span class="description"><?php _e( 'Select when to reset the invoice number to 1.', 'flexible-invoices' ); ?></span>
                    <!-- Last number date = <?php echo get_option( 'inspire_invoices_order_start_invoice_number_timestamp', '' ) != '' ? date( 'd.m.Y', get_option( 'inspire_invoices_order_start_invoice_number_timestamp' ) ) : ''; ?> -->
                </td>
            </tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_order_start_number"><?php _e( 'Next Invoice Number', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<input value="<?php echo $this->getSettingValue('order_start_invoice_number', 1); ?>" id="inspire_invoices_order_start_number" name="inspire_invoices[order_start_invoice_number]" type="text" /><br />
					<span class="description"><?php _e( 'Enter the next invoice number. Default value is 1 and changes every time an invoice is issued. Existing invoices won\'t be changed.', 'flexible-invoices' ); ?></span>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_order_number_prefix"><?php _e( 'Invoice Prefix', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<?php echo Flexible_Invoices_Translator::get_translated_input_field(
						'inspire_invoices[order_number_prefix]',
						$this->getSettingValue('order_number_prefix', __( 'Invoice ', 'flexible-invoices' ) ),
						'inspire_invoices_order_number_prefix'
					); ?>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_order_number_suffix"><?php _e( 'Invoice Suffix', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<?php echo Flexible_Invoices_Translator::get_translated_input_field(
						'inspire_invoices[order_number_suffix]',
						$this->getSettingValue('order_number_suffix', '/{MM}/{YYYY}'),
						'inspire_invoices_order_number_suffix'
					); ?>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_pay_date_days"><?php _e( 'Default Due Time', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<input value="<?php echo $this->getSettingValue('pay_date_days', '0'); ?>" id="inspire_invoices_pay_date_days" name="inspire_invoices[pay_date_days]" type="text" /> <?php echo __('days', 'flexible-invoices'); ?>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_date_of_sale"><?php _e( 'Label for Date of Sale', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<?php echo Flexible_Invoices_Translator::get_translated_input_field(
						'inspire_invoices[date_of_sale]',
						$this->getSettingValue('date_of_sale', __( 'Date of sale', 'flexible-invoices' ) ),
						'inspire_invoices_date_of_sale'
					); ?><br />
					<span class="description"><?php _e( 'Enter the label for &quot;date of sale&quot; visible on the PDF invoice, i.e. &quot;date of delivery&quot;. It will be used on all new and edited invoices.', 'flexible-invoices' ); ?></span>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="inspire_invoices_notice"><?php _e( 'Invoice Notes', 'flexible-invoices' ); ?></label>
				</th>

				<td class="forminp forminp-text">
					<?php echo Flexible_Invoices_Translator::get_translated_textarea_field(
						'inspire_invoices[invoices_notice]',
						$this->getSettingValue('invoices_notice'),
						'inspire_invoices_notice',
						'input-text wide-input'
					); ?>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row"><?php _e( 'Show Signatures', 'flexible-invoices' ); ?></th>

				<td class="forminp forminp-checkbox">
					<label for="inspire_invoices_show_signatures"> <input <?php if($this->getSettingValue('show_signatures') == 'on'):  ?>checked="checked"<?php endif; ?> id="inspire_invoices_show_signatures" name="inspire_invoices[show_signatures]" type="checkbox" /> <?php _e( 'Enable if you want to display place for signatures.', 'flexible-invoices' ); ?></label>
				</td>
			</tr>

            <tr valign="top" class="signature-user">
	            <th class="titledesc" scope="row"><?php _e( 'Seller signature', 'flexible-invoices' ); ?></th>
	            <td class="forminp forminp-checkbox">
		            <?php $signature_user_value = $this->getSettingValue( 'signature_user' ); ?>
		            <?php
		            wp_dropdown_users(
			            [
				            'role__in' =>
					            [
						            'administrator',
						            'shop_manager',
						            'editor'
					            ],
				            'name'     => 'inspire_invoices[signature_user]',
				            'selected' => $signature_user_value
			            ]
		            );
		            ?>
		            <br/><span class="description"><?php esc_html_e( 'Choose a user whose display name will be visible on the invoice in the signature section.', 'flexible-invoices' ); ?></span>
	            </td>
            </tr>

			<tr valign="top">
				<th class="titledesc" scope="row"><?php _e( 'Tax Cells on Invoices', 'flexible-invoices' ); ?></th>

				<td class="forminp forminp-checkbox">
					<label for="inspire_invoices_hide_vat"> <input <?php if($this->getSettingValue('hide_vat') == 'on'):  ?>checked="checked"<?php endif; ?> id="inspire_invoices_hide_vat" name="inspire_invoices[hide_vat]" type="checkbox" /> <?php _e( 'If tax is 0 hide all tax cells on PDF invoices.', 'flexible-invoices' ); ?></label>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row"><?php _e( 'Seller\'s VAT Number on Invoices', 'flexible-invoices' ); ?></th>

				<td class="forminp forminp-checkbox">
					<label for="inspire_invoices_hide_vat_number"> <input <?php if($this->getSettingValue('hide_vat_number') == 'on'):  ?>checked="checked"<?php endif; ?> id="inspire_invoices_hide_vat_number" name="inspire_invoices[hide_vat_number]" type="checkbox" /> <?php _e( 'If tax is 0 hide seller\'s VAT Number on PDF invoices.', 'flexible-invoices' ); ?></label>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row"><?php _e( 'Payment Methods', 'flexible-invoices' ); ?></th>

				<td class="forminp forminp-textarea">
					<label for="inspire_invoices_payment_methods">
					<textarea class="input-text wide-input" id="inspire_payment_methods" name="inspire_invoices[payment_methods]"><?php echo $this->getSettingValue('payment_methods',__('Bank transfer', 'flexible-invoices') . "\n" . __('Cash', 'flexible-invoices') . "\n" . __('Other', 'flexible-invoices') ); ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>

    <h3><?php _e( 'Advanced Settings', 'flexible-invoices' ); ?></h3>

    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th class="titledesc" scope="row">
                <label for="inspire_invoices_number_reset_type"><?php _e( 'Roles', 'flexible-invoices' ); ?></label>
            </th>
            <td class="forminp forminp-text">
                <?php $_roles = (array) $this->getSettingValue('roles', array( invoiceSettings::SHOPMANAGER_ROLE ) ); ?>
                <select id="inspire_invoices_roles"  name="inspire_invoices[roles][]" multiple="multiple">
                    <?php foreach( $this->settings->getRoles() as $role_slug => $role_name ): ?>
                        <option value="<?php echo $role_slug; ?>" <?php echo in_array( $role_slug, $_roles ) == $role_slug ? 'selected' : '' ; ?>><?php echo translate_user_role( $role_name ); ?></option>
                    <?php endforeach; ?>
                </select><br/>
                <span class="description"><?php _e( 'Select the User Roles that will be given permission to manage Invoices. The administrator has unlimited permissions.', 'flexible-invoices' ); ?></span>
            </td>
        </tr>
        </tbody>
    </table>

	<?php do_action('inspire_invoices_after_display_tab_settings'); ?>

	<p class="submit"><input type="submit" value="<?php _e( 'Save changes', 'flexible-invoices' ); ?>" class="button button-primary" id="submit" name=""></p>
</form>
