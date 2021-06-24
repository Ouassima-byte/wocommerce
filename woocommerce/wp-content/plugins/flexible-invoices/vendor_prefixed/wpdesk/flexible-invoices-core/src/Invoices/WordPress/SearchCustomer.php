<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WP_User_Query;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Search customer.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WordPress
 */
class SearchCustomer implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /** @var string NONCE_ARG */
    const NONCE_ARG = 'security';
    /**
     * @param DocumentFactory $document_factory
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory)
    {
        $this->document_factory = $document_factory;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('wp_ajax_invoice-get-client-data', array($this, 'get_customer_data_action'));
        \add_action('wp_ajax_woocommerce-invoice-user-select', array($this, 'select_ajax_user_search'));
    }
    /*
     * Search user via AJAX for user list
     *
     * @internal You should not use this directly from another application
     */
    public function select_ajax_user_search()
    {
        $client_options = array();
        if (\check_ajax_referer('inspire_invoices', self::NONCE_ARG, \false)) {
            $name = $_POST['name'];
            if ($name) {
                $users = new \WP_User_Query(array('search' => '*' . \esc_attr($name) . '*', 'search_columns' => array('user_login', 'user_nicename', 'user_email', 'user_url')));
                $users_results = $users->get_results();
                $users_meta = new \WP_User_Query(array('meta_query' => array('relation' => 'OR', array('key' => 'billing_first_name', 'value' => \esc_attr($name), 'compare' => 'LIKE'), array('key' => 'billing_last_name', 'value' => \esc_attr($name), 'compare' => 'LIKE'), array('key' => 'billing_company', 'value' => \esc_attr($name), 'compare' => 'LIKE'))));
                $users_meta_results = $users_meta->get_results();
                $results = \array_merge($users_results, $users_meta_results);
                foreach ($results as $user) {
                    $client_options[$user->ID] = array('id' => $user->ID, 'text' => $this->prepare_option_text($user));
                }
            }
            \wp_send_json(array('items' => \array_values($client_options)));
        }
        \wp_send_json($client_options);
    }
    /**
     * Process data from user object for select
     *
     * @param $user
     *
     * @return string
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
     * Get selected user from list
     *
     * @return array
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
     * @return void
     *
     * @internal You should not use this directly from another application
     */
    public function get_customer_data_action()
    {
        if (\current_user_can('edit_posts') && \check_ajax_referer('inspire_invoices', self::NONCE_ARG, \false)) {
            $user = \get_user_by('id', (int) $_REQUEST['client']);
            if (!empty($user)) {
                $user_data = array('name' => $user->first_name . ' ' . $user->last_name, 'street' => '', 'street2' => '', 'postcode' => '', 'city' => '', 'nip' => '', 'country' => '', 'phone' => '', 'email' => $user->user_email);
                if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
                    $user_data = array('name' => empty($user->billing_company) ? $user->billing_first_name . ' ' . $user->billing_last_name : $user->billing_company, 'street' => $user->billing_address_1 . (empty($user->billing_address_2) ? '' : ' ' . $user->billing_address_2), 'postcode' => $user->billing_postcode, 'city' => $user->billing_city, 'nip' => $user->vat_number, 'country' => $user->billing_country, 'phone' => $user->billing_phone, 'email' => $user->user_email);
                }
                $result = array('result' => 'OK', 'code' => 100, 'userdata' => \apply_filters('inspire_invoices_client_data', $user_data, $_REQUEST['client'], $user));
            } else {
                $result = array('result' => 'Fail', 'code' => 101);
            }
            \header('Content-Type: application/json');
            echo \json_encode($result);
            die;
        }
    }
}
