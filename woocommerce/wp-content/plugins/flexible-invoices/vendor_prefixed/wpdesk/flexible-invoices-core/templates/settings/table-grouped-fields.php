<?php

namespace WPDeskFIVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var \WPDesk\View\Renderer\Renderer $renderer
 * @var string $name_prefix
 * @var array  $value
 *
 * @var string $template_name Real field template.
 */
?>

<?php 
if (!$value) {
    $value = $field->get_empty_values();
}
$value = \array_values($value);
for ($i = 0; $i < \count($value); $i++) {
    echo '<tr><td class="sort"><input type="hidden" class="row-num" value="' . $i . '" /></td>';
    $items = $field->get_items();
    if (\is_array($items)) {
        foreach ($items as $item) {
            ?>
		<td class="forminp">
		<?php 
            $val = isset($value[$i][$item->get_name()]) ? \strval($value[$i][$item->get_name()]) : '';
            $item->set_attribute('id', $field->get_id() . '_' . $i);
            echo $renderer->render($item->get_template_name(), ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix . '[' . $field->get_name() . '][' . $i . ']', 'value' => $val, 'multiple' => $field->is_multiple()]);
            ?>
		</td>
		<?php 
        }
        echo '<td class="delete"><a href="#" class="delete-item"><span class="dashicons dashicons-no-alt"></span></a></td></tr>';
    }
    ?>

<?php 
}
