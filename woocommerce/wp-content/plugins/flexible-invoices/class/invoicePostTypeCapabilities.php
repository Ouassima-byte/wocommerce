<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages invoice capabilities
 */
class invoicePostTypeCapabilities {

	const CAPABILITY_SINGULAR = 'flexible_invoice';
	const CAPABILITY_PLURAL = 'flexible_invoices';

	/** @var invoiceSettings */
	protected $settings;

	public function __construct( invoiceSettings $settings ) {
	    $this->settings = $settings;
    }

	/**
	 * Assigns invoice capabilites to roles so the selected users can read/write the invoice data.
	 */
	public function assignBasicRolesCapabilitiesAction() {
		$wp_roles = wp_roles();
		$rolesWithAccess = array( invoiceSettings::ADMIN_ROLE );

		$new_roles = $this->settings->getSettingValue('roles', array( invoiceSettings::SHOPMANAGER_ROLE ) );

		if( $new_roles && is_array( $new_roles ) ) {
			$rolesWithAccess = array_merge( $rolesWithAccess, $new_roles );
		}

		foreach ( $wp_roles->roles as $roleId => $roleStructure ) {
			/** @var WP_Role $role */
			$role = get_role( $roleId );
			
			$capabilities = array_unique( array_values( (array) $this->getPostCapabilityMapAsObject() ) );
			if ( in_array( $roleId, $rolesWithAccess ) && $role instanceof WP_Role ) {
				$this->addCapsToRole( $role, $capabilities );
			} else {
				$this->removeCapsFromRole( $role, $capabilities );
			}
		}
	}

	/**
	 * Returns invoice Post Type capabilities.
	 *
	 * @return \stdClass Wordpress post type caps field.
	 */
	public function getPostCapabilityMapAsObject() {
		return (object) [
			'read'                   => 'read_flexible_invoice',
			'read_post'              => 'read_flexible_invoice',
			'read_private_posts'     => 'read_private_flexible_invoices',
			'edit_post'              => 'edit_flexible_invoice',
			'edit_posts'             => 'edit_flexible_invoices',
			'edit_others_posts'      => 'edit_others_flexible_invoices',
			'edit_private_posts'     => 'edit_private_flexible_invoices',
			'edit_published_posts'   => 'edit_published_flexible_invoices',
			'delete_post'            => 'delete_flexible_invoice',
			'delete_posts'           => 'delete_flexible_invoices',
			'delete_others_posts'    => 'delete_others_flexible_invoices',
			'delete_private_posts'   => 'delete_private_flexible_invoices',
			'delete_published_posts' => 'delete_published_flexible_invoices',
			'create_posts'           => 'edit_flexible_invoices',
			'publish_posts'          => 'publish_flexible_invoices',
		];
    }

	/**
	 * Add all capabilities from role.
	 *
	 * @param WP_Role $role
	 * @param array   $capabilities
	 */
	private function addCapsToRole( WP_Role $role, array $capabilities ) {
		foreach ( $capabilities as $cap ) {
			$role->add_cap( $cap );
		}
	}

	/**
	 * Removes all capabilities to role.
	 *
	 * @param WP_Role $role
	 * @param array   $capabilities
	 */
	private function removeCapsFromRole( WP_Role $role, array $capabilities ) {
		foreach ( $capabilities as $cap ) {
			$role->remove_cap( $cap );
		}
	}
}