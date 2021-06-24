<?php

namespace WPDeskFIVendor;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField;
/**
 * @var \WPDesk\Forms\Form\FormWithFields $form
 */
?>
<form class="wrap woocommerce" method="<?php 
echo \esc_attr($form->get_method());
?>" action="<?php 
echo \esc_attr($form->get_action());
?>">
	<h2 style="display:none;"></h2>
	<ul class="subsubsub js-subsubsub-wrapper">
		<?php 
$active = 'current';
foreach ($form->get_fields() as $field) {
    if ($field instanceof \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField) {
        ?>
				<li>
					<a class="sub-tab-<?php 
        echo \sanitize_key($field->get_name());
        ?> <?php 
        echo $active;
        ?>" id="tab-anchor-<?php 
        echo \sanitize_key($field->get_name());
        ?>" href="#<?php 
        echo \sanitize_key($field->get_name());
        ?>"><?php 
        echo $field->get_label();
        ?></a>
					<span class="sep">|</span>
				</li>
				<?php 
        $active = '';
    }
}
?>
	</ul>
<?php 
