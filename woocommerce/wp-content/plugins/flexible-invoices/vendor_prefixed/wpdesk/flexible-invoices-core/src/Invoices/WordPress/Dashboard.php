<?php

/**
 * Integration. Register custom post type.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentNumber;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Dashboard important hooks.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class Dashboard implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var PostTypeCapabilities
     */
    private $capabilities;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * Dashboard constructor.
     *
     * @param DocumentFactory      $document_factory
     * @param SettingsStrategy     $strategy
     * @param PostTypeCapabilities $capabilities
     * @param Renderer             $renderer
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PostTypeCapabilities $capabilities, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->document_factory = $document_factory;
        $this->strategy = $strategy;
        $this->renderer = $renderer;
        $this->capabilities = $capabilities;
        $this->settings = $settings;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        if (\is_admin()) {
            \add_filter('default_title', array($this, 'new_invoice_default_title'), 80, 2);
            //add_action( 'admin_init', array( $this, 'set_default_layout_action' ) );
            \add_action('admin_init', array($this->capabilities, 'assign_basic_roles_capabilities_action'));
            \add_action('restrict_manage_posts', array($this, 'add_invoice_bulk_selects'));
            \add_filter('months_dropdown_results', array($this, 'modify_invoice_listing_months_filter'), 80, 2);
            \add_filter('parse_query', array($this, 'filter_invoices'));
            \add_filter('views_edit-inspire_invoice', array($this, 'add_duplicated_filter'));
            \add_action('fi/core/settings/metabox', [$this, 'settings_advertising_block']);
        }
    }
    /**
     * @param string   $post_title
     * @param \WP_Post $post
     *
     * @return string
     *
     * @throws \Exception
     * @internal You should not use this directly from another application
     */
    public function new_invoice_default_title($post_title, $post)
    {
        if (empty($post_title) && $post->post_type == \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME) {
            $creator = $this->document_factory->get_document_creator($post->ID);
            $document = $creator->get_document();
            $numbering = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentNumber($this->settings, $document, $creator->get_name());
            return $numbering->get_formatted_number();
        } else {
            return $post_title;
        }
    }
    /**
     * Set layout action
     *
     * @internal You should not use this directly from another application
     */
    public function set_default_layout_action()
    {
        $user = \wp_get_current_user();
        $columns = \get_user_meta($user->ID, 'screen_layout_inspire_invoice', \true);
        if (empty($columns)) {
            \update_user_meta($user->ID, 'screen_layout_inspire_invoice', 1);
        }
        $hidden = \get_user_meta($user->ID, 'manageedit-inspire_invoicecolumnshidden', \true);
        if ($hidden === '') {
            $hidden = array('sale', 'currency', 'paymethod');
            \update_user_meta($user->ID, 'manageedit-inspire_invoicecolumnshidden', $hidden);
        }
    }
    /**
     * Add user select to bulk actions
     *
     * @internal You should not use this directly from another application
     */
    public function add_invoice_bulk_selects()
    {
        global $typenow;
        if ($typenow == 'inspire_invoice') {
            $selected = $this->get_selected_user();
            echo $this->renderer->render('wordpress/bulk-status-select', ['selected' => $selected, 'statuses' => $this->strategy->get_payment_statuses()]);
        }
    }
    /**
     * Get selected user from list
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function get_selected_user()
    {
        $user_data = array();
        if (isset($_GET['user'])) {
            $user_id = (int) $_GET['user'];
            $user = \get_userdata($user_id);
            if ($user) {
                $user_data = array('id' => $user_id, 'text' => $this->prepare_option_text($user));
            }
        }
        return $user_data;
    }
    /**
     * Process data from user object for select
     *
     * @param $user
     *
     * @return string
     *
     * @internal You should not use this directly from another application
     */
    public function prepare_option_text($user)
    {
        $name = '';
        $user_meta = \get_user_meta($user->ID);
        if (isset($user_meta['billing_company'][0])) {
            $company = $user_meta['billing_company'][0];
            if (!empty($company)) {
                $name .= $company . ', ';
            }
        }
        if (isset($user_meta['billing_first_name'][0])) {
            $billing_first_name = $user_meta['billing_first_name'][0];
            if (!empty($billing_first_name)) {
                $name .= $user_meta['billing_first_name'][0] . ' ';
            }
        }
        if (isset($user_meta['billing_last_name'][0])) {
            $billing_last_name = $user_meta['billing_last_name'][0];
            if (!empty($billing_last_name)) {
                $name .= $user_meta['billing_last_name'][0] . ', ';
            }
        }
        $name .= $user->first_name . ' ';
        return $name . $user->last_name . ' (' . $user->user_login . ')';
    }
    /**
     * @param string $months
     * @param string $post_type
     *
     * @return array|object|null
     *
     * @internal You should not use this directly from another application
     */
    public function modify_invoice_listing_months_filter($months, $post_type)
    {
        if ($post_type == 'inspire_invoice') {
            global $wpdb;
            $months = $wpdb->get_results($wpdb->prepare("\n\t                SELECT DISTINCT YEAR( FROM_UNIXTIME( pm.meta_value ) ) AS year, MONTH( FROM_UNIXTIME ( pm.meta_value ) ) AS month\n\t                FROM\n\t                   {$wpdb->posts} p,\n\t                   {$wpdb->postmeta} pm\n\t                WHERE\n\t                   pm.post_id = p.id AND\n\t                   p.post_type = %s AND\n\t                   pm.meta_key = '_date_issue'\n\t                ORDER BY\n\t                   pm.meta_value DESC\n\t                ", $post_type));
        }
        return $months;
    }
    /**
     * @param \WP_Query $query
     *
     * @return \WP_Query
     *
     * @internal You should not use this directly from another application
     */
    public function filter_invoices($query)
    {
        global $pagenow;
        $qv =& $query->query_vars;
        if ($pagenow == 'edit.php' && isset($qv['post_type']) && $qv['post_type'] == 'inspire_invoice') {
            $meta_query = array();
            if (isset($_GET['filter']) && 'show_duplicated' === $_GET['filter']) {
                $qv['post__in'] = $this->get_duplicated_posts_ids();
            }
            if (!empty($_GET['paystatus'])) {
                if ($_GET['paystatus'] === 'exceeded') {
                    $meta_query[] = array('key' => '_payment_status', 'value' => 'topay', 'compare' => 'LIKE');
                    $meta_query[] = array('key' => '_date_pay', 'value' => \strtotime(\date('Y-m-d 00:00:00')), 'compare' => '<');
                } else {
                    $meta_query[] = array('key' => '_payment_status', 'value' => $_GET['paystatus'], 'compare' => 'LIKE');
                }
            }
            if (!empty($_GET['user'])) {
                $user = new \WP_User((int) $_GET['user']);
                if (empty($user->billing_company)) {
                    $name = $user->billing_first_name . ' ' . $user->billing_last_name;
                } else {
                    $name = $user->billing_company;
                }
                $meta_query[] = array('key' => '_client_filter_field', 'value' => $name, 'compare' => 'LIKE');
            }
            if (!empty($_GET['m'])) {
                unset($qv['m']);
                $m = \strtotime(\substr($_GET['m'], 0, 4) . '-' . \substr($_GET['m'], 4, 2) . '-01 00:00:00');
                $meta_query[] = array('key' => '_date_issue', 'value' => array($m, \strtotime(\date('Y-m-t 23:59:59', $m))), 'compare' => 'BETWEEN', 'type' => 'UNSIGNED');
            }
            if (!empty($meta_query)) {
                $qv['meta_query'] = $meta_query;
            }
        }
        return $query;
    }
    /**
     * Define own invoices messages for custom post type.
     *
     * @param array $messages
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function change_default_wordpress_post_messages_filter($messages)
    {
        global $post, $post_ID;
        $post_type = \get_post_type($post_ID);
        $obj = \get_post_type_object($post_type);
        $singular = $obj->labels->singular_name;
        $messages['inspire_invoice'] = array(
            0 => '',
            // Unused. Messages start at index 1.
            1 => \__('Invoice updated.', 'flexible-invoices'),
            2 => \__('Custom field updated.', 'flexible-invoices'),
            3 => \__('Custom field deleted.', 'flexible-invoices'),
            4 => \__('Invoice updated.', 'flexible-invoices'),
            5 => isset($_GET['revision']) ? \sprintf(\__($singular . ' rolled back to revision %s.', 'flexible-invoices'), \wp_post_revision_title((int) $_GET['revision'], \false)) : \false,
            6 => \__('Invoice issued.', 'flexible-invoices'),
            7 => \__('Invoice saved.', 'flexible-invoices'),
            8 => \__('Invoice submitted.', 'flexible-invoices'),
            9 => \__('Invoice scheduled', 'flexible-invoices'),
            10 => \__('Invoice draft updated', 'flexible-invoices'),
        );
        return $messages;
    }
    /**
     * Add link for filtering duplicated invoices.
     *
     * @param array $views
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function add_duplicated_filter($views)
    {
        $views['duplicated'] = \sprintf(\__('<a href="%s">Duplicated <span class="count">(%d)</span></a>', 'flexible-invoices'), \admin_url('edit.php?post_type=' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME . '&filter=show_duplicated'), \count($this->get_duplicated_posts_ids()));
        return $views;
    }
    /**
     * @return array
     */
    private function get_duplicated_posts_ids()
    {
        global $wpdb;
        $result = array();
        $rows = $wpdb->get_col($wpdb->prepare("SELECT GROUP_CONCAT(p.ID) FROM {$wpdb->posts} as p WHERE p.post_type = %s AND p.post_status = %s GROUP BY p.post_title HAVING COUNT( p.post_title ) > 1", \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'publish'));
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $result = \array_merge($result, \explode(',', $row));
            }
        }
        return $result;
    }
    /**
     * @param $null
     *
     * @internal You should not use this directly from another application
     */
    public function settings_advertising_block($null)
    {
        echo $this->renderer->render('woocommerce/ad-metabox', []);
    }
}
