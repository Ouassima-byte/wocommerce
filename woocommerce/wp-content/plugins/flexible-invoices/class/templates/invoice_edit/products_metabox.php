<?php
if ( ! defined( 'ABSPATH' ) ) exit;

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
$products = @array_merge($invoice->getProducts(), $invoice->getShipping());
?>

<div class="form-wrap products_metabox">
	<table class="wp-list-table widefat fixed products">
		<thead>
		<tr>
			<th class="product-title"><?php _e('Product', 'flexible-invoices' ); ?></th>
			<th><?php _e('SKU', 'flexible-invoices' ); ?></th>
			<th><?php _e('Unit', 'flexible-invoices' ); ?></th>
			<th><?php _e('Quantity', 'flexible-invoices' ); ?></th>
			<th><?php _e('Net price', 'flexible-invoices' ); ?></th>
			<th><?php _e('Net amount', 'flexible-invoices' ); ?></th>
			<th><?php _e('Tax rate', 'flexible-invoices' ); ?></th>
			<th><?php _e('Tax amount', 'flexible-invoices' ); ?></th>
			<th><?php _e('Gross amount', 'flexible-invoices' ); ?></th>
			<th class="product-actions"></th>
		</tr>
		</thead>

		<tbody class="products_container">
		<tr style="display: none" class="product_prototype product_row">
			<td><input type="text" name="product[name][]" value="" disabled="disabled" /></td>
			<td><input type="text" name="product[sku][]" value="" disabled="disabled" /></td>
			<td><input type="text" name="product[unit][]" value="" disabled="disabled" /></td>
			<td><input type="text" name="product[quantity][]" value="" class="refresh_net_price_sum" disabled="disabled" /></td>
			<td><input type="text" name="product[net_price][]" value="" class="refresh_net_price_sum" disabled="disabled" /></td>
			<td><input type="text" name="product[net_price_sum][]" value="" class="refresh_vat_sum" disabled="disabled" /></td>
			<td>
				<?php $vatTypes = $args['plugin']->invoicePostType->getVatTypes(); ?>
				<select name="product[vat_type][]" class="refresh_vat_sum" disabled="disabled" >
					<?php foreach ($vatTypes as $index => $vatType): ?>
						<option value="<?php echo implode('|',  $vatType); ?>"><?php echo $vatType['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td><input type="text" name="product[vat_sum][]" value="" class="refresh_total_price" disabled="disabled" /></td>
			<td><input type="text" name="product[total_price][]" value="" class="refresh_total" disabled="disabled" /></td>

			<td><a class="remove_product" href="#" title="<?php _e('Delete product', 'flexible-invoices' ); ?>"><span class="dashicons dashicons-no"></span></a></td>
		</tr>

		<?php if (!empty($products)): ?>
			<?php foreach ($products as $index => $product): ?>
				<tr class="product_row">
					<td><input type="text" name="product[name][]" value="<?php echo esc_attr( @$product['name'] ); ?>" /></td>
					<td><input type="text" name="product[sku][]" value="<?php echo esc_attr( @$product['sku'] ); ?>" /></td>
					<td><input type="text" name="product[unit][]" value="<?php echo esc_attr( @$product['unit'] ); ?>" /></td>
					<td><input type="text" name="product[quantity][]" value="<?php echo esc_attr( @$product['quantity'] ); ?>" class="refresh_net_price_sum" /></td>
					<td><input type="text" name="product[net_price][]" value="<?php echo esc_attr( @$product['net_price'] ); ?>" class="refresh_net_price_sum" /></td>
					<td><input type="text" name="product[net_price_sum][]" value="<?php echo esc_attr( @$product['net_price_sum'] ); ?>" class="refresh_vat_sum" /></td>
					<td>
						<?php $vat_type_options = array(); ?>
						<?php $selected_key = false; ?>
						<?php /* tax with same name and rate? */ ?>
						<?php foreach ($vatTypes as $index => $vatType): ?>
							<?php $vat_type_options[implode('|',  $vatType)] = $vatType['name']; ?>
							<?php if ( !$selected_key && $vatType['name'] == $product['vat_type_name'] && floatval( $vatType['rate'] ) == floatval( $product['vat_type'] ) ) : ?>
								<?php $selected_key = implode('|',  $vatType);?>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php if ( !$selected_key ) :?>
							<?php $selected_key = '-1|' . $product['vat_type'] . '|' . $product['vat_type_name']; ?>
							<?php $vat_type_options[$selected_key] = $product['vat_type_name']; ?>
						<?php endif; ?>
						<select name="product[vat_type][]" class="refresh_vat_sum">
							<?php foreach ( $vat_type_options as $key => $vat_type_option ) : ?>
								<option value="<?php echo $key; ?>" <?php if ( $key == $selected_key ): ?>selected="selected"<?php endif; ?>><?php echo $vat_type_option; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td><input type="text" name="product[vat_sum][]" value="<?php echo esc_attr( $product['vat_sum'] ); ?>" class="refresh_total_price" /></td>
					<td><input type="text" name="product[total_price][]" value="<?php echo esc_attr( $product['total_price'] ); ?>" class="refresh_total" /></td>

					<td><a class="remove_product" href="#" title="<?php _e('Delete product', 'flexible-invoices' ); ?>"><span class="dashicons dashicons-no"></span></a></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>

		</tbody>
	</table>

	<button class="button add_product"><?php _e('Add product', 'flexible-invoices' ); ?></button>
</div>
