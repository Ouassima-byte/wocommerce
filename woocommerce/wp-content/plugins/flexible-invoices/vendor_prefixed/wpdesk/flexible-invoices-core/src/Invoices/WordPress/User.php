<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WP_User;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Add Vat field in user account.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WordPress
 */
class User implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Fires hooks
     */
    public function hooks()
    {
        \add_action('show_user_profile', array($this, 'add_vat_user_field'));
        \add_action('edit_user_profile', array($this, 'add_vat_user_field'));
        \add_action('personal_options_update', array($this, 'save_vat_user_field'));
        \add_action('edit_user_profile_update', array($this, 'save_vat_user_field'));
    }
    /**
     * @param WP_User $user
     *
     * @internal You should not use this directly from another application
     */
    public function add_vat_user_field(\WP_User $user)
    {
        ?>
		<table class="form-table">
			<tr>
				<th><label for="vatNumber"><?php 
        echo \__('VAT Number', 'flexible-invoices');
        ?></label>
				</th>

				<td>
					<input type="text" name="vat_number" id="vatNumber"
						   value="<?php 
        echo \esc_attr(\get_the_author_meta('vat_number', $user->ID));
        ?>"
						   class="regular-text"/><br/>
					<span class="description"></span>
				</td>
			</tr>
		</table>
		<?php 
    }
    /**
     * @param int $user_id
     *
     * @return false|void
     *
     * @internal You should not use this directly from another application
     */
    public function save_vat_user_field($user_id)
    {
        if (\current_user_can('edit_user', $user_id)) {
            \update_user_meta($user_id, 'vat_number', $_POST['vat_number']);
        }
    }
}
