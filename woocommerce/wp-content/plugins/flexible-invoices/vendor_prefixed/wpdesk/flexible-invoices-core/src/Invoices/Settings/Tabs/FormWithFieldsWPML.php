<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Forms\Form\FormWithFields;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Tab than can be rendered on settings page.
 * This abstraction should be used by tabs that want to use Form Fields to render its content.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings
 */
class FormWithFieldsWPML extends \WPDeskFIVendor\WPDesk\Forms\Form\FormWithFields
{
    /**
     * Renders only fields without form.
     *
     * @param Renderer $renderer
     *
     * @return string
     */
    public function render_fields(\WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer)
    {
        $content = '';
        $fields_data = $this->get_data();
        foreach ($this->get_fields() as $field) {
            $value = isset($fields_data[$field->get_name()]) ? $fields_data[$field->get_name()] : $field->get_default_value();
            global $sitepress;
            $textdomain = empty($textdomain) ? \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::$text_domain : $textdomain;
            if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::is_default_language() && \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::is_wpml_active()) {
                $current_lang = $sitepress->get_current_language();
                $name = 'inspire_invoices_' . $field->get_name();
                if (\is_string($value)) {
                    $value = \apply_filters('wpml_translate_single_string', $value, $textdomain, $name, $current_lang);
                }
            }
            $content .= $renderer->render($field->should_override_form_template() ? $field->get_template_name() : 'form-field', ['field' => $field, 'renderer' => $renderer, 'name_prefix' => $this->get_form_id(), 'value' => $value, 'template_name' => $field->get_template_name()]);
        }
        return $content;
    }
}
