<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\SettingsTab;
use WPDeskFIVendor\WPDesk\Forms\Resolver\DefaultFormFieldResolver;
use WPDeskFIVendor\WPDesk\Notice\Notice;
use WPDeskFIVendor\WPDesk\Persistence\Adapter\WordPress\WordpressOptionsContainer;
use WPDeskFIVendor\WPDesk\Persistence\PersistentContainer;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver;
use WPDeskFIVendor\WPDesk\View\Resolver\DirResolver;
/**
 * Adds settings to the menu and manages how and what is shown on the settings page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings
 */
class SettingsForm implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var string
     */
    private static $settings_slug = 'invoices_settings';
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var string
     */
    private $template_dir;
    /**
     * @param SettingsStrategy $strategy
     * @param string           $template_dir
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, $template_dir)
    {
        $this->strategy = $strategy;
        $this->template_dir = $template_dir;
    }
    /**
     * Get URL to plugin settings, optionally to specific tab.
     *
     * @param string|null $tab_slug When null returns URL to general settings.
     *
     * @return string
     */
    public static function get_url($tab_slug = null)
    {
        $url = \admin_url(\add_query_arg(['page' => self::$settings_slug], \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_MENU_URL));
        if ($tab_slug !== null) {
            $url = \add_query_arg(['tab' => $tab_slug], $url);
        }
        return $url;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('admin_menu', function () {
            \add_submenu_page(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_MENU_URL, \__('Settings', 'flexible-invoices'), \__('Settings', 'flexible-invoices'), 'manage_options', self::$settings_slug, [$this, 'render_page_action'], 40);
        }, 999);
        \add_action('admin_init', [$this, 'save_settings_action'], 5);
        if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['page'] === self::$settings_slug) {
            \add_filter('admin_body_class', function ($classes) {
                return $classes . ' settings-' . $_GET['tab'];
            });
        }
    }
    /**
     * Save POST tab data. Before render.
     *
     * @return void
     */
    public function save_settings_action()
    {
        if (isset($_GET['page']) && $_GET['page'] !== self::$settings_slug) {
            return;
        }
        $tab = $this->get_active_tab();
        $data_container = self::get_settings_persistence();
        if (!empty($_POST) && isset($_POST[$tab::get_tab_slug()])) {
            $tab->handle_request($_POST[$tab::get_tab_slug()]);
            $this->save_tab_data($tab, $data_container);
            \do_action('fi/core/settings/tabs/saved', $tab, $data_container);
            new \WPDeskFIVendor\WPDesk\Notice\Notice(\__('Your settings have been saved.', 'flexible-invoices'), \WPDeskFIVendor\WPDesk\Notice\Notice::NOTICE_TYPE_SUCCESS);
        } else {
            $tab->set_data($data_container);
        }
        \do_action('fi/core/settings/ready');
    }
    /**
     * Render
     *
     * @return void
     */
    public function render_page_action()
    {
        $tab = $this->get_active_tab();
        $renderer = $this->get_renderer();
        echo $renderer->render('menu', ['base_url' => self::get_url(), 'menu_items' => $this->get_tabs_menu_items(), 'selected' => $this->get_active_tab()->get_tab_slug()]);
        echo $tab->render($renderer);
        echo $renderer->render('footer');
    }
    /**
     * @return SettingsTab
     */
    private function get_active_tab()
    {
        $selected_tab = isset($_GET['tab']) ? \sanitize_key($_GET['tab']) : null;
        $tabs = $this->get_settings_tabs();
        if (!empty($selected_tab) && isset($tabs[$selected_tab])) {
            return $tabs[$selected_tab];
        }
        return \reset($tabs);
    }
    /**
     * @return SettingsTab[]
     */
    private function get_settings_tabs()
    {
        static $tabs = [];
        if (empty($tabs)) {
            $tabs[\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\GeneralSettings::get_tab_slug()] = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\GeneralSettings();
            $tabs[\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\DocumentsSettings::get_tab_slug()] = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\DocumentsSettings($this->strategy);
            if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
                $tabs[\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\WooCommerceSettings::get_tab_slug()] = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\WooCommerceSettings($this->strategy);
            }
            $tabs[\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\CurrencySettings::get_tab_slug()] = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\CurrencySettings();
            $tabs[\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\TaxRatesSettings::get_tab_slug()] = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\TaxRatesSettings();
            /**
             * Filters settings tab.
             *
             * @param array $tabs .
             *
             * @since 3.0.0
             */
            $tabs = \apply_filters('fi/core/settings/tabs', $tabs);
        }
        return $tabs;
    }
    /**
     * Returns writable container with saved settings.
     *
     * @param string $tab_slug Unique slug of a settings tab.
     *
     * @return PersistentContainer
     */
    public static function get_settings_persistence()
    {
        return new \WPDeskFIVendor\WPDesk\Persistence\Adapter\WordPress\WordpressOptionsContainer('inspire_invoices_');
    }
    /**
     * Save data from tab to persistent container.
     *
     * @param SettingsTab         $tab
     * @param PersistentContainer $container
     */
    private function save_tab_data(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\SettingsTab $tab, \WPDeskFIVendor\WPDesk\Persistence\PersistentContainer $container)
    {
        $post_data = isset($_POST[$tab::get_tab_slug()]) ? $_POST[$tab::get_tab_slug()] : [];
        foreach ($post_data as $key => $value) {
            $container->set($key, $value);
        }
        if (!empty($_SERVER['REQUEST_URI'])) {
            \wp_redirect($_SERVER['REQUEST_URI'], 301);
            exit;
        }
    }
    /**
     * @return \WPDesk\View\Renderer\Renderer
     */
    private function get_renderer()
    {
        $chain = new \WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver();
        $resolver_list = (array) \apply_filters('fi/core/settings/settings_template_resolvers', [new \WPDeskFIVendor\WPDesk\View\Resolver\DirResolver($this->template_dir . 'settings'), new \WPDeskFIVendor\WPDesk\Forms\Resolver\DefaultFormFieldResolver()]);
        \array_unshift($resolver_list, new \WPDeskFIVendor\WPDesk\View\Resolver\DirResolver($this->template_dir . 'settings/' . $this->get_active_tab()->get_tab_slug()));
        foreach ($resolver_list as $resolver) {
            $chain->appendResolver($resolver);
        }
        return new \WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer($chain);
    }
    /**
     * @return string[]
     */
    private function get_tabs_menu_items()
    {
        $menu_items = [];
        foreach ($this->get_settings_tabs() as $tab) {
            if ($tab::is_active()) {
                $menu_items[$tab::get_tab_slug()] = $tab->get_tab_name();
            }
        }
        return $menu_items;
    }
}
