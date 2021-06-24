<?php

namespace WPDeskFIVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var \WPDesk\View\Renderer\Renderer $renderer
 * @var string $name_prefix
 * @var string $value
 *
 * @var string $template_name Real field template.
 *
 */
?>
<input
	type="<?php 
echo \esc_attr($field->get_type());
?>"
	name="<?php 
echo \esc_attr($name_prefix) . '[' . \esc_attr($field->get_name()) . ']';
?>"
	id="<?php 
echo \esc_attr($field->get_id());
?>"

	<?php 
if ($field->has_classes()) {
    ?>
		class="<?php 
    echo \esc_attr($field->get_classes());
    ?>"
		<?php 
}
?>

	<?php 
if ($field->has_placeholder()) {
    ?>
		placeholder="<?php 
    echo \esc_html($field->get_placeholder());
    ?>"
		<?php 
}
?>

	<?php 
foreach ($field->get_attributes() as $key => $atr_val) {
    echo $key . '="' . \esc_attr($atr_val) . '"';
    ?>
		<?php 
}
?>

	<?php 
if ($field->is_required()) {
    ?>required="required"<?php 
}
?>
	<?php 
if ($field->is_disabled()) {
    ?>disabled="disabled"<?php 
}
?>
	<?php 
if ($field->is_readonly()) {
    ?>readonly="readonly"<?php 
}
?>
	value="<?php 
echo \esc_html($value);
?>"
/>
<?php 
