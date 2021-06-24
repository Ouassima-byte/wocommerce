<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
	global $woocommerce;
?>
<form action="" method="post">
	<?php settings_fields( 'inspire_invoices_settings' ); ?>
	<?php wp_nonce_field( 'save_settings', 'flexible_invoices_settings' ); ?>

 	<?php if (!empty($_POST['option_page']) && $_POST['option_page'] === 'inspire_invoices_settings'): ?>
		<div id="message" class="updated fade"><p><strong><?php _e( 'Settings saved.', 'flexible-invoices' ); ?></strong></p></div>
	<?php endif; ?>

	<h3><?php _e( 'Correction Settings', 'flexible-invoices' ); ?></h3>

    <p>
	    <?php
	    printf(
		    '<a href="%s" target="_blank">%s</a>',
		    esc_url( get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/faktury-korygujace-woocommerce/?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=settings-docs-link': 'https://www.wpdesk.net/docs/flexible-invoices-woocommerce-corrections-docs/?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=settings-docs-link', array( 'https' )),
		    esc_html__( 'Check how to issue corrective invoices.', 'flexible-invoices' )
	    );
	    ?>
    </p>

	<table class="form-table">
		<tbody>
            <tr valign="top">
                <th class="titledesc" scope="row"><?php _e( 'Automatic Corrections', 'flexible-invoices' ); ?></th>

                <td class="forminp forminp-checkbox">
                    <label for="inspire_enable_corrections"> <input <?php if($this->getSettingValue('enable_corrections') == 'on'):  ?>checked="checked"<?php endif; ?> id="inspire_enable_corrections" name="inspire_invoices[enable_corrections]" type="checkbox" /> <?php _e( 'Enable automatic corrections generation for order refunds.', 'flexible-invoices' ); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="inspire_invoices_correction_number_reset_type"><?php _e( 'Correction Number Reset', 'flexible-invoices' ); ?></label>
                </th>

                <td class="forminp forminp-text">
                    <select id="inspire_invoices_correction_number_reset_type" name="inspire_invoices[correction_number_reset_type]">
                        <option value="year" <?php echo $this->getSettingValue('correction_number_reset_type', 'year') == 'year' ? 'selected' : '' ; ?>><?php _e( 'Yearly', 'flexible-invoices' ); ?></option>
                        <option value="month" <?php echo $this->getSettingValue('correction_number_reset_type', 'year') == 'month' ? 'selected' : '' ; ?>><?php _e( 'Monthly', 'flexible-invoices' ); ?></option>
                        <option value="none" <?php echo $this->getSettingValue('correction_number_reset_type', 'year') == 'none' ? 'selected' : '' ; ?>><?php _e( 'None', 'flexible-invoices' ); ?></option>
                    </select>
                    <br/>
                    <span class="description"><?php _e( 'Select when to reset the correction number to 1.', 'flexible-invoices' ); ?></span>
                    <!-- Last number date = <?php echo get_option( 'inspire_invoices_correction_start_invoice_number_timestamp', '' ) != '' ? date( 'd.m.Y', get_option( 'inspire_invoices_correction_start_invoice_number_timestamp' ) ) : ''; ?> -->
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="inspire_invoices_correction_start_number"><?php _e( 'Next Correction Number', 'flexible-invoices' ); ?></label>
                </th>

                <td class="forminp forminp-text">
                    <input value="<?php echo $this->getSettingValue('correction_start_invoice_number', 1); ?>" id="inspire_invoices_correction_start_number" name="inspire_invoices[correction_start_invoice_number]" type="text" />
        			<br />
                    <span class="description"><?php _e( 'Enter the next correction number. Default value is 1 and changes every time an correction is issued. Existing corrections won\'t be changed.', 'flexible-invoices' ); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="inspire_invoices_correction_prefix"><?php _e( 'Correction Prefix', 'flexible-invoices' ); ?></label>
                </th>

                <td class="forminp forminp-text">
	                <?php echo Flexible_Invoices_Translator::get_translated_input_field(
		                'inspire_invoices[correction_prefix]',
		                $this->getSettingValue('correction_prefix', __( 'Corrected invoice ', 'flexible-invoices' )),
		                'inspire_invoices_correction_prefix'
	                ); ?>
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="inspire_invoices_correction_suffix"><?php _e( 'Correction Suffix', 'flexible-invoices' ); ?></label>
                </th>

                <td class="forminp forminp-text">
	                <?php echo Flexible_Invoices_Translator::get_translated_input_field(
		                'inspire_invoices[correction_suffix]',
		                $this->getSettingValue('correction_suffix', __( '/{MM}/{YYYY}', 'flexible-invoices' ) ),
		                'inspire_invoices_correction_suffix'
	                ); ?>
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="inspire_invoices_correction_default_due_time"><?php _e( 'Correction Default Due Time', 'flexible-invoices' ); ?></label>
                </th>

                <td class="forminp forminp-text">
                    <input value="<?php echo $this->getSettingValue('correction_default_due_time', 0 ); ?>" id="inspire_invoices_correction_default_due_time" name="inspire_invoices[correction_default_due_time]" type="text" />
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="inspire_invoices_correction_reason"><?php _e( 'Correction Reason', 'flexible-invoices' ); ?></label>
                </th>

                <td class="forminp forminp-text">
	                <?php echo Flexible_Invoices_Translator::get_translated_input_field(
		                'inspire_invoices[correction_reason]',
		                $this->getSettingValue('correction_reason', __( 'Refund', 'flexible-invoices' ) ),
		                'inspire_invoices_correction_reason'
	                ); ?>
                </td>
            </tr>
		</tbody>
	</table>

	<?php do_action('inspire_invoices_after_display_tab_settings'); ?>

	<p class="submit"><input type="submit" value="<?php _e( 'Save changes', 'flexible-invoices' ); ?>" class="button button-primary" id="submit" name=""></p>
</form>
