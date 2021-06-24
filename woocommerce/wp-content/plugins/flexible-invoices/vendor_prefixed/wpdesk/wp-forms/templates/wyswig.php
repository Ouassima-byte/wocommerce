<?php

namespace WPDeskFIVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var string $name_prefix
 * @var string $value
 */
\wp_print_styles('media-views');
?>
<script>
	window.SM_EditorInitialized = true;
</script>


<?php 
$id = \uniqid('wyswig_');
$editor_settings = array('textarea_name' => \esc_attr($name_prefix) . '[' . \esc_attr($field->get_name()) . ']');
\wp_editor(\wp_kses_post($value), $id, $editor_settings);
?>
<script type="text/javascript">
	(function () {
		ShopMagic.wyswig.init('<?php 
echo $id;
?>');
	}());
</script>
<?php 
