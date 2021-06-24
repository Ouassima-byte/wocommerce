<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm;
use WPDeskFIVendor\WPDesk\Forms\Field;
use WPDeskFIVendor\WPDesk\Forms\Form\FormWithFields;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Tab than can be rendered on settings page.
 * This abstraction should be used by tabs that want to use Form Fields to render its content.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings
 */
abstract class FieldSettingsTab implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\SettingsTab
{
    /**
     * @var FormWithFields
     */
    private $form;
    /**
     * @return Field[]
     */
    protected abstract function get_fields();
    /**
     * @return bool
     */
    public static function is_active()
    {
        return \true;
    }
    /**
     * @return FormWithFields
     */
    protected function get_form()
    {
        if ($this->form === null) {
            $fields = $this->get_fields();
            $this->form = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FormWithFieldsWPML($fields, static::get_tab_slug());
        }
        return $this->form;
    }
    /**
     * @param Renderer $renderer
     *
     * @return string
     */
    public function render(\WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer)
    {
        return $this->get_form()->render_form($renderer);
    }
    /**
     * @param array|\Psr\Container\ContainerInterface $data
     */
    public function set_data($data)
    {
        $this->get_form()->set_data($data);
    }
    /**
     * @param array $request
     */
    public function handle_request($request)
    {
        $this->get_form()->handle_request($request);
    }
    /**
     * @return array|null
     */
    public function get_data()
    {
        return $this->get_form()->get_data();
    }
    /**
     * Simple access to settings. Just like WordPress style get_option.
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function get_option($key, $default = \false)
    {
        $persistence = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm::get_settings_persistence();
        if ($persistence->has($key)) {
            return $persistence->get($key);
        }
        return $default;
    }
}
