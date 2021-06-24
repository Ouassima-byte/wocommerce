<?php

/**
 * Invoice. Register custom post type.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Register custom post types.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class RegisterPostType implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const POST_TYPE_NAME = 'inspire_invoice';
    const POST_TYPE_MENU_URL = 'edit.php?post_type=' . self::POST_TYPE_NAME;
    /**
     * @var PostTypeCapabilities
     */
    private $capabilities;
    /**
     * @param PostTypeCapabilities $capabilities
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PostTypeCapabilities $capabilities)
    {
        $this->capabilities = $capabilities;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('init', array($this, 'register_post_type_action'));
    }
    /**
     * Get post type args.
     *
     * @return array
     */
    private function get_post_type_args()
    {
        global $menu;
        $menu_pos = 56.8673974;
        while (isset($menu[$menu_pos])) {
            $menu_pos++;
        }
        /**
         * Can export from Wordpress.
         *
         * @param false
         */
        $has_archive = \apply_filters('fi/core/register_post_type/has_archive', \false);
        return ['label' => 'inspire_invoice', 'description' => \__('Invoices', 'flexible-invoices'), 'labels' => array('name' => \__('Invoices', 'flexible-invoices'), 'singular_name' => \__('Invoice', 'flexible-invoices'), 'menu_name' => \__('Invoices', 'flexible-invoices'), 'parent_item_colon' => '', 'all_items' => \__('All Invoices', 'flexible-invoices'), 'view_item' => \__('View Invoice', 'flexible-invoices'), 'add_new_item' => \__('Add New Invoice', 'flexible-invoices'), 'add_new' => \__('Add New', 'flexible-invoices'), 'edit_item' => \__('Edit Invoice', 'flexible-invoices'), 'update_item' => \__('Save Invoice', 'flexible-invoices'), 'search_items' => \__('Search Invoices', 'flexible-invoices'), 'not_found' => \__('No invoices found.', 'flexible-invoices'), 'not_found_in_trash' => \__('No invoices found in Trash.', 'flexible-invoices')), 'supports' => array('title'), 'taxonomies' => array(), 'hierarchical' => \false, 'public' => \false, 'show_ui' => \true, 'show_in_menu' => \true, 'show_in_nav_menus' => \true, 'show_in_admin_bar' => \true, 'menu_position' => $menu_pos, 'menu_icon' => 'dashicons-media-spreadsheet', 'can_export' => \false, 'has_archive' => $has_archive, 'exclude_from_search' => \true, 'publicly_queryable' => \false, 'capability_type' => [\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PostTypeCapabilities::CAPABILITY_SINGULAR, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PostTypeCapabilities::CAPABILITY_PLURAL], 'map_meta_cap' => \false, 'cap' => $this->capabilities->get_post_capability_map_as_object()];
    }
    /**
     * @return void
     *
     * @internal You should not use this directly from another application
     */
    public function register_post_type_action()
    {
        \register_post_type(self::POST_TYPE_NAME, $this->get_post_type_args());
    }
}
