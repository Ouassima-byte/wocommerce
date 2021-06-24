<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php

	$field = 'flexible_invoices_tax_table';

	$count_taxes = 0;

	$key = 'inspire_invoices_tax[xxx][name]';
	$field_args = array(
			'type' 			=> 'text',
			'return' 		=> true,
	);
	$value = '';
	$field_name = woocommerce_form_field( $key, $field_args, $value );

	$key = 'inspire_invoices_tax[xxx][rate]';
	$field_args = array(
			'type' 		 	=> 'text',
			'input_class'	=> array( 'wc_input_price' ),
			'return' 	 	=> true,
	);
	$value = '';
	$field_rate = woocommerce_form_field( $key, $field_args, $value );

	$inspire_invoices_tax = get_option('inspire_invoices_tax', array() );

?>
<form action="" method="post">
	<?php settings_fields( 'inspire_invoices_settings' ); ?>
	<?php wp_nonce_field( 'save_settings', 'flexible_invoices_settings' ); ?>

 	<?php if (!empty($_POST['option_page']) && $_POST['option_page'] === 'inspire_invoices_settings'): ?>
		<div id="message" class="updated fade"><p><strong><?php _e( 'Settings saved.', 'flexible-invoices' ); ?></strong></p></div>
	<?php endif; ?>

	<h3><?php _e( 'Tax rates', 'flexible-invoices' ); ?></h3>

    <p><a href="<?php echo $args['docs_link']; ?>" target="_blank"><?php _e( 'Read user\'s manual →', 'flexible-invoices' ); ?></a></p>

	<table class="form-table">
		<tody>

			<tr valign="top">
			    <td colspan="2" style="padding:0;">
			        <table id="<?php echo esc_attr( $field ); ?>" class="flexible_invoices_tax wc_input_table sortable widefat">
			            <thead>
			            	<tr>
			            		<th class="sort">&nbsp;</th>
			            		<th class="name">
			            		    <?php _e( 'Name', 'flexible-invoices' ); ?>
			                    </th>
			            		<th class="rate">
			            			<?php _e( 'Rate', 'flexible-invoices' ); ?>
			            		</th>
			            	</tr>
			            </thead>

			            <tbody>
			            	<?php if ( isset( $inspire_invoices_tax ) && is_array( $inspire_invoices_tax ) ) : ?>
			            		<?php foreach ( $inspire_invoices_tax as $key => $tax ) : $count_taxes++; ?>
			            			<tr>
			            				<td class="sort"></td>
			            				<td class="name">
			            					<?php
			            						$key = 'inspire_invoices_tax[' . $count_taxes . '][name]';
			            						$field_args = array(
			            							'type' 			=> 'text',
			            						);
			            						$value = '';
			            						if ( isset( $tax['name'] ) ) {
			            							$value = $tax['name'];
			            						}
			            						woocommerce_form_field( $key, $field_args, $value );
			            					?>
			            				</td>
			            				<td class="rate">
			            					<?php
			            						$key = 'inspire_invoices_tax[' . $count_taxes . '][rate]';
			            						$field_args = array(
			            							'type' 			=> 'text',
			            							'input_class'	=> array( 'wc_input_price' ),
			            						);
			            						$value = '';
			            						if ( isset( $tax['rate'] ) ) {
			            							$value = $tax['rate'];
			            						}
			            						woocommerce_form_field( $key, $field_args, $value );
			            					?>
			            				</td>
			            			</tr>
			            		<?php endforeach; ?>
			            	<?php endif; ?>
			            </tbody>

			            <tfoot>
			            	<tr>
			            		<th colspan="99">
			            			<a id="insert_tax" href="#" class="button plus insert"><?php _e( 'Insert rate', 'flexible-invoices' ); ?></a>
			            			<a id="remove_tax" href="#" class="button minus"><?php _e( 'Delete selected rate', 'flexible-invoices' ); ?></a>
			            		</th>
			            	</tr>
			            </tfoot>
			        </table>

			    </td>
			</tr>


		</tbody>
	</table>


	<?php /* do_action('inspire_invoices_after_display_tab_settings'); */ ?>

	<p class="submit"><input type="submit" value="<?php _e( 'Save changes', 'flexible-invoices' ); ?>" class="button button-primary" id="submit" name=""></p>
</form>

<script type="text/javascript">
    function append_row( id ) {
    	var code = '<tr class="new">\
    					<td class="sort"></td>\
    					<td class="name">\
    						<?php echo str_replace( "'", '"', str_replace( "\n", "\\\n", $field_name ) ); ?> \
    					</td>\
    					<td class="rate">\
    					    <?php echo str_replace( "'", '"', str_replace( "\n", "\\\n", $field_rate ) ); ?> \
    					</td>\
            		</tr>';
       	var code2 = code.replace(/xxx/g, id );
       	var $tbody = jQuery('#<?php echo esc_attr( $field ); ?>').find('tbody');
       	$tbody.append( code2 );
    }
    jQuery(document).ready(function() {
          	var tbody = jQuery('#<?php echo esc_attr( $field ); ?>').find('tbody');
           	var append_id = <?php echo $count_taxes ?>;
           	var size = tbody.find('tr').size();
           	if ( size == 0 ) {
           		append_id = append_id+1;
           		append_row(append_id);
           	}
           	jQuery('#insert_tax').click(function() {
           		append_id = append_id+1;
           		append_row(append_id);
           		jQuery('#rules_'+append_id+'_min').focus();
           		return false;
           	});
           	jQuery('#remove_tax').click(function() {
           		if ( current = tbody.children( '.current' ) ) {
           			current.each(function() {
           				jQuery(this).remove();
           			});
           		} else {
           			alert( '<?php _e( 'No rows selected.' , 'flexible-invoices' ); ?>' );
           		}
           		return false;
           	});
           	jQuery(document).on('click', '.delete_rule',  function() {
           		if (confirm('<?php _e( 'Are you sure?' , 'flexible-invoices' ); ?>')) {
           			jQuery(this).closest('tr').remove();
           		}
           		return false;
           	});
           	jQuery('#mainform').attr('action', '<?php echo remove_query_arg( 'added', add_query_arg( 'added', '1' ) ); ?>' );
    });
</script>
