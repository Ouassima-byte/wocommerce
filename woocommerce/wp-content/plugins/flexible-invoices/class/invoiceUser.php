<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class invoiceUser //extends inspire_pluginDependant3
    {
        public function __construct($plugin)
        {

            // Add Vat field as user field
            add_action( 'show_user_profile', array( $this, 'addVatUserField') );
            add_action( 'edit_user_profile', array( $this, 'addVatUserField') );

            add_action( 'personal_options_update', array( $this, 'saveVatUserField') );
            add_action( 'edit_user_profile_update',  array( $this, 'saveVatUserField') );
        }

        public function addVatUserField($user)
        {
            ?>

			<table class="form-table">

				<tr>
					<th><label for="vatNumber"><?php echo __('VAT Number', 'flexible-invoices'); ?></label></th>

					<td>
						<input type="text" name="vat_number" id="vatNumber" value="<?php echo esc_attr( get_the_author_meta( 'vat_number', $user->ID ) ); ?>" class="regular-text" /><br />
						<span class="description"></span>
					</td>
				</tr>

			</table>

			<?php
		}

		public function saveVatUserField($user_id)
		{
		    if ( !current_user_can( 'edit_user', $user_id ) )
		        return false;

		    update_user_meta( $user_id, 'vat_number', $_POST['vat_number'] );
		}


    }
