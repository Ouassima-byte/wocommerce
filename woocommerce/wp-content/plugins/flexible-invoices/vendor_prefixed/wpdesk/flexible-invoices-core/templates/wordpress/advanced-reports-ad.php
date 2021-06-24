<?php

namespace WPDeskFIVendor;

/**
 * Scoper fix
 */
?>
<table>
	<tbody>
	<tr>
		<td width="70%">
			<p><strong><?php 
\_e('Buy Advanced Reports for Flexible Invoices to get:', 'flexible-invoices');
?></strong></p>

			<ul>
				<li><span class="dashicons dashicons-yes"></span> <?php 
\_e('Adjust columns displayed in the reports.', 'flexible-invoices');
?></li>
				<li><span class="dashicons dashicons-yes"></span> <?php 
\_e('Advanced filtering by: issue date, sale date, payment date, taxes, currencies.', 'flexible-invoices');
?></li>
				<li><span class="dashicons dashicons-yes"></span> <?php 
\_e('Include or exclude invoices for WooCommerce orders.', 'flexible-invoices');
?></li>
				<li><span class="dashicons dashicons-yes"></span> <?php 
\_e('Sorting by issue date, sale date, payment date.', 'flexible-invoices');
?></li>
			</ul>
		</td>
        <?php 
$pl = 'https://www.wpdesk.pl/sklep/faktury-zaawansowane-raporty/?utm_source=flexible-invoices&utm_campaign=flexible-invoices-reports&utm_medium=button';
$en = 'https://flexibleinvoices.com/products/advanced-reports-for-flexible-invoices/?utm_source=flexible-invoices&utm_medium=button&utm_campaign=flexible-invoices-reports';
$buy_url = \esc_url(\get_locale() === 'pl_PL' ? $pl : $en, array('https'));
?>
		<td>
			<a class="button button-primary button-hero" href="<?php 
echo $buy_url;
?>" target="_blank"><?php 
\_e('Buy Advanced Reports &rarr;', 'flexible-invoices');
?></a>
		</td>
	</tr>
	</tbody>
</table>
<?php 
