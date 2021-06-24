<?php

namespace WPDeskFIVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var string $name_prefix
 * @var string $value
 */
?>
<table class="<?php 
echo \esc_attr($field->get_classes());
?> form-table sub-table field-settings-<?php 
echo \sanitize_key($field->get_name());
?>">
	<tbody>
<?php 
