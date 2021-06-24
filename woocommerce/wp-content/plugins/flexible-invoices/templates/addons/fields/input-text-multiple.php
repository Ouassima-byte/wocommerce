<?php
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
<?php
if( empty( $value ) || is_string( $value ) ) {
	$input_values[] = '';
} else {
	$input_values = $value;
}
?>
<div class="clone-element-container">
<?php foreach( $input_values as $text_value ): ?>
<?php if (!\in_array($field->get_type(), ['number', 'text', 'hidden'])): ?>
	<input type="hidden" name="<?php echo $name_prefix.'['.$field->get_name().']'; ?>" value="no"/>
<?php endif; ?>

<?php if ($field->get_type() === 'checkbox' && $field->has_sublabel()): ?><label><?php endif; ?>
	<div class="clone-wrapper">
	<input
		type="<?php echo \esc_attr($field->get_type()); ?>"
		name="<?php echo \esc_attr($name_prefix).'['.\esc_attr($field->get_name()).'][]'; ?>"
		id="<?php echo \esc_attr($field->get_id()); ?>"

		<?php if ($field->has_classes()): ?>
			class="<?php echo \esc_attr($field->get_classes()); ?>"
		<?php endif; ?>

		<?php if ($field->get_type() === 'text' && $field->has_placeholder()):?>
			placeholder="<?php echo \esc_html($field->get_placeholder());?>"
		<?php endif; ?>

		<?php foreach ($field->get_attributes() as $key => $atr_val):
			echo $key.'="'.\esc_attr($atr_val).'"'; ?>
		<?php endforeach; ?>

		<?php if ($field->is_required()): ?>required="required"<?php endif; ?>
		<?php if ($field->is_disabled()): ?>disabled="disabled"<?php endif; ?>
		<?php if ($field->is_readonly()): ?>readonly="readonly"<?php endif; ?>
		<?php if (\in_array($field->get_type(), ['number', 'text', 'hidden'])): ?>
			value="<?php echo \esc_html($text_value); ?>"
		<?php else: ?>
			value="yes"
			<?php if ($value === 'yes'): ?>
				checked="checked"
			<?php endif; ?>
		<?php endif; ?>
	/>
		<span class="add-field" style="display: inline-block; margin-top: 3px; "><span class="dashicons dashicons-plus-alt"></span></span>
	</div>

	<?php if ($field->get_type() === 'checkbox' && $field->has_sublabel()): ?>
	<?php echo \esc_html($field->get_sublabel()); ?></label>
<?php endif; ?>
<?php endforeach; ?>
</div>
