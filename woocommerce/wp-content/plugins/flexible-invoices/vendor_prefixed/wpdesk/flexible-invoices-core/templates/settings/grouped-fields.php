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
foreach ($field->get_grouped_fields() as $field) {
    echo $field->get_label();
    $value = $field->get_default_value();
    echo $renderer->render($field->get_template_name(), ['field' => $field, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $value]);
}
