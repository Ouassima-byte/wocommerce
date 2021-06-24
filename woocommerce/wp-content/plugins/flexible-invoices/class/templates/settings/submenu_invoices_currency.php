<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php

	global $woocommerce;

	$locale_info = get_flexible_invoices_locale_info();

	$lang = explode('-', get_bloginfo('language'));

	if ( isset( $lang[1] ) ) {
		$country = $lang[1];
	}
	else {
		$country = 'US';
	}

	$field = 'flexible_invoices_currency_table';

	$count_currencies = 0;

	$options_currency = get_flexible_invoices_currencies();

	$key = 'inspire_invoices_currency[xxx][currency]';
	$field_args = array(
			'type' 		=> 'select',
			'options' 	=> $options_currency,
			'return' 	=> true,
	);
	$value = 'USD';
	if ( isset( $locale_info[$country] ) ) {
		$value = $locale_info[$country]['currency_code'];
	}
	$field_currency = woocommerce_form_field( $key, $field_args, $value );

	$field_currency_position_options = get_flexible_invoices_currency_position();

	$key = 'inspire_invoices_currency[xxx][currency_position]';
	$field_args = array(
			'type' 		=> 'select',
			'options' 	=> $field_currency_position_options,
			'return' 	=> true,
	);
	$value = 'left';
	if ( isset( $locale_info[$country] ) ) {
		$value = $locale_info[$country]['currency_pos'];
	}
	$field_currency_position = woocommerce_form_field( $key, $field_args, $value );

	$key = 'inspire_invoices_currency[xxx][thousand_separator]';
	$field_args = array(
			'type' 		 	=> 'text',
			'return' 	 	=> true,
	);
	$value = ',';
	if ( isset( $locale_info[$country] ) ) {
		$value = $locale_info[$country]['thousand_sep'];
	}
	$field_thousand_separator = woocommerce_form_field( $key, $field_args, $value );

	$key = 'inspire_invoices_currency[xxx][decimal_separator]';
	$field_args = array(
			'type' 		 	=> 'text',
			'return' 	 	=> true,
	);
	$value = '.';
	if ( isset( $locale_info[$country] ) ) {
		$value = $locale_info[$country]['decimal_sep'];
	}
	$field_decimal_separator = woocommerce_form_field( $key, $field_args, $value );

	$key = 'inspire_invoices_currency[xxx][num_decimals]';
	$field_args = array(
			'type' 		 	=> 'text',
			'return' 	 	=> true,
	);
	$value = '2';
	if ( isset( $locale_info[$country] ) ) {
		$value = $locale_info[$country]['num_decimals'];
	}
	$field_number_of_decimals = woocommerce_form_field( $key, $field_args, $value );

	$inspire_invoices_currency = get_option('inspire_invoices_currency', array() );

?>
<form action="" method="post">
	<?php settings_fields( 'inspire_invoices_settings' ); ?>
	<?php wp_nonce_field( 'save_settings', 'flexible_invoices_settings' ); ?>

 	<?php if (!empty($_POST['option_page']) && $_POST['option_page'] === 'inspire_invoices_settings'): ?>
		<div id="message" class="updated fade"><p><strong><?php _e( 'Settings saved.', 'flexible-invoices' ); ?></strong></p></div>
	<?php endif; ?>

	<h3><?php _e( 'Currency', 'flexible-invoices' ); ?></h3>

    <p><a href="<?php echo $args['docs_link']; ?>" target="_blank"><?php _e( 'Read user\'s manual →', 'flexible-invoices' ); ?></a></p>

	<table class="form-table">
		<tody>

			<tr valign="top">
			    <td colspan="2" style="padding:0;">
			        <table id="<?php echo esc_attr( $field ); ?>" class="flexible_invoices_currency wc_input_table sortable widefat">
			            <thead>
			            	<tr>
			            		<th class="sort">&nbsp;</th>
			            		<th class="currency">
			            		    <?php _e( 'Currency', 'flexible-invoices' ); ?>
			                    </th>
			            		<th class="currency_position">
			            			<?php _e( 'Currency position', 'flexible-invoices' ); ?>
			            		</th>
			            		<th class="thousand_separator">
			            			<?php _e( 'Thousand separator', 'flexible-invoices' ); ?>
			            		</th>
			            		<th class="decimal_separator">
			            			<?php _e( 'Decimal separator', 'flexible-invoices' ); ?>
			            		</th>
<?php /* ?>
			            		<th class="number_of_decimals">
			            			<?php _e( 'Number of decimals', 'flexible-invoices' ); ?>
			            		</th>
<?php */ ?>
			            	</tr>
			            </thead>

			            <tbody>
			            	<?php if ( isset( $inspire_invoices_currency ) && is_array( $inspire_invoices_currency ) ) : ?>
			            		<?php foreach ( $inspire_invoices_currency as $key => $currency ) : $count_currencies++; ?>
			            			<tr>
			            				<td class="sort"></td>
			            				<td class="currency">
			            					<?php
			            						$key = 'inspire_invoices_currency[' . $count_currencies . '][currency]';
			            						$field_args = array(
			            							'type' 		=> 'select',
			            							'options' 	=> $options_currency,
			            						);
			            						$value = '';
			            						if ( isset( $currency['currency'] ) ) {
			            							$value = $currency['currency'];
			            						}
			            						woocommerce_form_field( $key, $field_args, $value );
			            					?>
			            				</td>
			            				<td class="currency_position">
			            					<?php
			            						$currency_position_options = get_flexible_invoices_currency_position( $currency['currency'] );
			            						$key = 'inspire_invoices_currency[' . $count_currencies . '][currency_position]';
			            						$field_args = array(
			            							'type' 		=> 'select',
			            							'options' 	=> $currency_position_options,
			            						);
			            						$value = '';
			            						if ( isset( $currency['currency_position'] ) ) {
			            							$value = $currency['currency_position'];
			            						}
			            						woocommerce_form_field( $key, $field_args, $value );
			            					?>
			            				</td>
			            				<td class="thousand_separator">
			            					<?php
			            						$key = 'inspire_invoices_currency[' . $count_currencies . '][thousand_separator]';
			            						$field_args = array(
			            								'type' 			=> 'text',
			            						);
			            						$value = '';
			            						if ( isset( $currency['thousand_separator'] ) ) {
			            							$value = $currency['thousand_separator'];
			            						}
			            						woocommerce_form_field( $key, $field_args, $value );
			            					?>
			            				</td>
			            				<td class="decimal_separator">
			            					<?php
			            						$key = 'inspire_invoices_currency[' . $count_currencies . '][decimal_separator]';
			            						$field_args = array(
			            								'type' 			=> 'text',
			            						);
			            						$value = '';
			            						if ( isset( $currency['decimal_separator'] ) ) {
			            							$value = $currency['decimal_separator'];
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
			            			<a id="insert_currency" href="#" class="button plus insert"><?php _e( 'Insert currency', 'flexible-invoices' ); ?></a>
			            			<a id="remove_currency" href="#" class="button minus"><?php _e( 'Delete selected currency', 'flexible-invoices' ); ?></a>
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
    					<td class="currency">\
    						<?php echo str_replace( "'", '"', str_replace( "\n", "\\\n", $field_currency ) ); ?> \
    					</td>\
    					<td class="currency_position">\
    					    <?php echo str_replace( "'", '"', str_replace( "\n", "\\\n", $field_currency_position ) ); ?> \
    					</td>\
    					<td class="thousand_separator">\
    					    <?php echo str_replace( "'", '"', str_replace( "\n", "\\\n", $field_thousand_separator ) ); ?> \
    					</td>\
    					<td class="decimal_separator">\
    					   <?php echo str_replace( "'", '"', str_replace( "\n", "\\\n", $field_decimal_separator ) ); ?> \
    					</td>\<?php /* ?>
    					<td class="number_of_decimals">\
 					       <?php echo str_replace( "'", '"', str_replace( "\n", "\\\n", $field_number_of_decimals ) ); ?> \
 						</td>\<?php */ ?>
            		</tr>';
       	var code2 = code.replace(/xxx/g, id );
       	var $tbody = jQuery('#<?php echo esc_attr( $field ); ?>').find('tbody');
       	$tbody.append( code2 );
    }
    jQuery(document).ready(function() {
          	var tbody = jQuery('#<?php echo esc_attr( $field ); ?>').find('tbody');
           	var append_id = <?php echo $count_currencies ?>;
           	var size = tbody.find('tr').size();
           	if ( size == 0 ) {
           		append_id = append_id+1;
           		append_row(append_id);
           	}
           	jQuery('#insert_currency').click(function() {
           		append_id = append_id+1;
           		append_row(append_id);
           		jQuery('#rules_'+append_id+'_min').focus();
           		return false;
           	});
           	jQuery('#remove_currency').click(function() {
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
