<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
$params = isset($params) ? $params : [];
/**
 * @var \WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $invoice
 */
$invoice = $params['invoice'];
$items = $invoice->get_items();
$document_issuing = 'Manual Issuing Proforma and Invoices';
?>
<div class="form-wrap products_metabox">
	<table class="wp-list-table widefat fixed products">
		<thead>
			<tr>
				<th class="product-title"><?php 
\_e('Product', 'flexible-invoices');
?></th>
				<th><?php 
\_e('SKU', 'flexible-invoices');
?></th>
				<th><?php 
\_e('Unit', 'flexible-invoices');
?></th>
				<th><?php 
\_e('Quantity', 'flexible-invoices');
?></th>
				<th><?php 
\_e('Net price', 'flexible-invoices');
?></th>
				<th><?php 
\_e('Net amount', 'flexible-invoices');
?></th>
				<th><?php 
\_e('Tax rate', 'flexible-invoices');
?></th>
				<th><?php 
\_e('Tax amount', 'flexible-invoices');
?></th>
				<th><?php 
\_e('Gross amount', 'flexible-invoices');
?></th>
				<th class="product-actions"></th>
			</tr>
		</thead>
		<?php 
$vatTypes = $params['vat_types'];
?>
		<tbody class="products_container">
			<tr style="display: none" class="product_prototype product_row">
				<td>
					<div class="product_select_name" style="width: 90%; float: left;">
						<div class="select-product">
							<select name="product[name][]" class="refresh_product wide-input" disabled="disabled">
								<option value=""></option>
							</select>
						</div>
					</div>
					<div style="float:right; margin-top: 5px;"><a href="#" class="edit_item_name" title="<?php 
\_e('Click this icon to enter item name manually', 'flexible-invoices');
?>"><span class="dashicons dashicons-edit"></span></a></div>
				</td>
				<td><input data-beacon_search="<?php 
echo $document_issuing;
?>" class="hs-beacon-search" type="text" name="product[sku][]" value="" disabled="disabled" /></td>
				<td><input data-beacon_search="<?php 
echo $document_issuing;
?>" class="hs-beacon-search" type="text" name="product[unit][]" value="" disabled="disabled" /></td>
				<td><input data-beacon_search="<?php 
echo $document_issuing;
?>" name="product[quantity][]" value="" class="refresh_net_price_sum hs-beacon-search" disabled="disabled" /></td>
				<td><input data-beacon_search="<?php 
echo $document_issuing;
?>" type="text" name="product[net_price][]" value="" class="hs-beacon-search refresh_net_price_sum" disabled="disabled" /></td>
				<td><input data-beacon_search="<?php 
echo $document_issuing;
?>" type="text" name="product[net_price_sum][]" value="" class="hs-beacon-search refresh_vat_sum" disabled="disabled" /></td>
				<td>
					<select name="product[vat_type][]" class="refresh_vat_sum" data-beacon_search="<?php 
echo $document_issuing;
?>" class="hs-beacon-search" disabled="disabled" >
						<?php 
foreach ($vatTypes as $index => $vatType) {
    ?>
							<option value="<?php 
    echo \implode('|', $vatType);
    ?>"><?php 
    echo $vatType['name'];
    ?></option>
						<?php 
}
?>
					</select>
				</td>
				<td><input data-beacon_search="<?php 
echo $document_issuing;
?>" type="text" name="product[vat_sum][]" value="" class="hs-beacon-search refresh_total_price" disabled="disabled" /></td>
				<td><input data-beacon_search="<?php 
echo $document_issuing;
?>" type="text" name="product[total_price][]" value="" class="hs-beacon-search refresh_total" disabled="disabled" /></td>

				<td><a class="remove_product" href="#" title="<?php 
\_e('Delete product', 'flexible-invoices');
?>"><span class="dashicons dashicons-no"></span></a></td>
			</tr>

			<?php 
if (!empty($items)) {
    ?>
				<?php 
    foreach ($items as $index => $product) {
        ?>
					<tr class="product_row">
						<td>
							<div class="product_select_name" style="width: 90%; float: left;">
								<div class="select-product">
									<select name="product[name][]" class="refresh_product wide-input">
										<option value="<?php 
        echo \esc_attr($product['name']);
        ?>"><?php 
        echo isset($product['name']) ? \esc_html($product['name']) : '';
        ?></option>
									</select>
								</div>
							</div>
							<div style="float:right; margin-top: 5px;"><a href="#" class="edit_item_name" title="<?php 
        \_e('Click this icon to enter item name manually', 'flexible-invoices');
        ?>"><span class="dashicons dashicons-edit"></span></a></div>
						</td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[sku][]" class="hs-beacon-search" value="<?php 
        echo isset($product['sku']) ? \esc_attr($product['sku']) : '';
        ?>" /></td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[unit][]" class="hs-beacon-search" value="<?php 
        echo isset($product['unit']) ? \esc_attr($product['unit']) : '';
        ?>" /></td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[quantity][]" value="<?php 
        echo isset($product['quantity']) ? \esc_attr($product['quantity']) : '';
        ?>" class="hs-beacon-search refresh_net_price_sum" /></td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[net_price][]" value="<?php 
        echo isset($product['net_price']) ? \esc_attr($product['net_price']) : '';
        ?>" class="hs-beacon-search refresh_net_price_sum" /></td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[net_price_sum][]" value="<?php 
        echo isset($product['net_price_sum']) ? \esc_attr($product['net_price_sum']) : '';
        ?>" class="hs-beacon-search refresh_vat_sum" /></td>
						<td>
							<?php 
        $vat_type_options = array();
        ?>
							<?php 
        $selected_key = \false;
        ?>
							<?php 
        /* tax with same name and rate? */
        ?>
							<?php 
        foreach ($vatTypes as $index => $vatType) {
            ?>
								<?php 
            $vat_type_options[\implode('|', $vatType)] = $vatType['name'];
            ?>
								<?php 
            if (!$selected_key && $vatType['name'] == $product['vat_type_name'] && \floatval($vatType['rate']) == \floatval($product['vat_type'])) {
                ?>
									<?php 
                $selected_key = \implode('|', $vatType);
                ?>
								<?php 
            }
            ?>
							<?php 
        }
        ?>
							<?php 
        if (!$selected_key) {
            ?>
								<?php 
            $selected_key = '-1|' . $product['vat_type'] . '|' . $product['vat_type_name'];
            ?>
								<?php 
            $vat_type_options[$selected_key] = $product['vat_type_name'];
            ?>
							<?php 
        }
        ?>
							<select name="product[vat_type][]" class="refresh_vat_sum">
								<?php 
        foreach ($vat_type_options as $key => $vat_type_option) {
            ?>
									<option value="<?php 
            echo $key;
            ?>" <?php 
            if ($key == $selected_key) {
                ?>selected="selected"<?php 
            }
            ?>><?php 
            echo $vat_type_option;
            ?></option>
								<?php 
        }
        ?>
							</select>
						</td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[vat_sum][]" value="<?php 
        echo isset($product['vat_sum']) ? \esc_attr($product['vat_sum']) : '';
        ?>" class="hs-beacon-search refresh_total_price" /></td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[total_price][]" value="<?php 
        echo isset($product['total_price']) ? \esc_attr($product['total_price']) : '';
        ?>" class="hs-beacon-search refresh_total" /></td>

						<td><a class="remove_product" href="#" title="<?php 
        \_e('Delete product', 'flexible-invoices');
        ?>"><span class="dashicons dashicons-no"></span></a></td>
					</tr>
				<?php 
    }
    ?>
			<?php 
}
?>

		</tbody>
	</table>

	<button class="button add_product"><?php 
\_e('Add product', 'flexible-invoices');
?></button>
</div>
<?php 
