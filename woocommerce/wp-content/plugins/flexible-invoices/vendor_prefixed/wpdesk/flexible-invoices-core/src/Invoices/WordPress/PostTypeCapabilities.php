<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WP_Role;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\GeneralSettings;
/**
 * Manages invoice capabilities
 */
class PostTypeCapabilities
{
    const CAPABILITY_SINGULAR = 'flexible_invoice';
    const CAPABILITY_PLURAL = 'flexible_invoices';
    /**
     * @var Settings
     */
    private $settings;
    /**
     * PostTypeCapabilities constructor.
     *
     * @param Settings $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->settings = $settings;
    }
    /**
     * Assigns invoice capabilities to roles so the selected users can read/write the invoice data.
     */
    public function assign_basic_roles_capabilities_action()
    {
        $wp_roles = \wp_roles();
        $rolesWithAccess = array(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\GeneralSettings::ADMIN_ROLE);
        $new_roles = $this->settings->get('roles', array(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\GeneralSettings::SHOP_MANAGER_ROLE));
        if ($new_roles && \is_array($new_roles)) {
            $rolesWithAccess = \array_merge($rolesWithAccess, $new_roles);
        }
        foreach ($wp_roles->roles as $roleId => $roleStructure) {
            /** @var WP_Role $role */
            $role = \get_role($roleId);
            $capabilities = \array_unique(\array_values((array) $this->get_post_capability_map_as_object()));
            if (\in_array($roleId, $rolesWithAccess) && $role instanceof \WP_Role) {
                $this->add_caps_to_role($role, $capabilities);
            } else {
                $this->remove_caps_from_role($role, $capabilities);
            }
        }
    }
    /**
     * Returns invoice Post Type capabilities.
     *
     * @return \stdClass Wordpress post type caps field.
     */
    public function get_post_capability_map_as_object()
    {
        return (object) ['read' => 'read_flexible_invoice', 'read_post' => 'read_flexible_invoice', 'read_private_posts' => 'read_private_flexible_invoices', 'edit_post' => 'edit_flexible_invoice', 'edit_posts' => 'edit_flexible_invoices', 'edit_others_posts' => 'edit_others_flexible_invoices', 'edit_private_posts' => 'edit_private_flexible_invoices', 'edit_published_posts' => 'edit_published_flexible_invoices', 'delete_post' => 'delete_flexible_invoice', 'delete_posts' => 'delete_flexible_invoices', 'delete_others_posts' => 'delete_others_flexible_invoices', 'delete_private_posts' => 'delete_private_flexible_invoices', 'delete_published_posts' => 'delete_published_flexible_invoices', 'create_posts' => 'edit_flexible_invoices', 'publish_posts' => 'publish_flexible_invoices'];
    }
    /**
     * Add all capabilities from role.
     *
     * @param WP_Role $role
     * @param array    $capabilities
     */
    private function add_caps_to_role(\WP_Role $role, array $capabilities)
    {
        foreach ($capabilities as $cap) {
            $role->add_cap($cap);
        }
    }
    /**
     * Removes all capabilities to role.
     *
     * @param WP_Role $role
     * @param array    $capabilities
     */
    private function remove_caps_from_role(\WP_Role $role, array $capabilities)
    {
        foreach ($capabilities as $cap) {
            $role->remove_cap($cap);
        }
    }
}
