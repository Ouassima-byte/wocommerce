<?php

namespace WPDesk\FlexibleInvoices\Addons\Filters;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;

class AdvancedFiltersAddon implements Hookable {

	const OPTION_DATE_KEY = 'addon_filters_free_date';
	const OPTION_NUM_KEY  = 'addon_filters_free_permanently_close';
	const SCRIPTS_VERSION = '1.0.0';

	/**
	 * @var Renderer
	 */
	private $renderer;

	/**
	 * @var string
	 */
	private $plugin_url;

	/**
	 * @param Renderer $renderer
	 * @param string   $plugin_url
	 */
	public function __construct( Renderer $renderer, string $plugin_url ) {
		$this->renderer   = $renderer;
		$this->plugin_url = $plugin_url;
	}

	/**
	 * Fire hooks.
	 */
	public function hooks() {
		if ( $this->can_show() && ! Plugin::is_active( 'flexible-invoices-advanced-filters/flexible-invoices-advanced-filters.php' ) ) {
			add_action( 'admin_footer', [ $this, 'render_filters' ] );
			add_action( 'wp_ajax_close_addon_filters', [ $this, 'close_addon_filters_action' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		}
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @internal You should not use this directly from another application.
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( $screen->id === 'edit-inspire_invoice' ) {
			wp_enqueue_style( 'flex-box-grid', $this->plugin_url . 'assets/css/flexboxgrid.min.css', '', self::SCRIPTS_VERSION );
			wp_enqueue_style( 'fi-addon', $this->plugin_url . 'assets/css/addon.css', '', self::SCRIPTS_VERSION );
			wp_enqueue_script( 'fi-addon', $this->plugin_url . 'assets/js/addon.js', [ 'jquery' ], self::SCRIPTS_VERSION, true );
		}
	}

	/**
	 * Render filter view.
	 *
	 * @internal You should not use this directly from another application.
	 */
	public function render_filters() {
		if ( current_user_can( 'edit_posts' ) ) {
			$screen = get_current_screen();
			if ( $screen->id === 'edit-inspire_invoice' ) {
				echo $this->renderer->render(
					'addons/advanced-filters-html',
					[
						'currencies'       => [],
						'countries'        => [],
						'payment_methods'  => [],
						'taxes'            => [],
						'document_types'   => [],
						'price_types'      => [],
						'date_types'       => [],
						'payment_statuses' => [],
					]
				);
			}
		}
	}

	/**
	 * @internal You should not use this directly from another application.
	 */
	public function close_addon_filters_action() {
		$date = gmdate( 'Y-m-d H:i:s', time() );
		update_option( self::OPTION_DATE_KEY, strtotime( $date . ' +14 days' ), false );
		update_option( self::OPTION_NUM_KEY, (int) get_option( self::OPTION_NUM_KEY, 0 ) + 1 );
		wp_send_json_success();
	}

	/**
	 * @return bool
	 */
	public function can_show(): bool {
		$is_permanently = (int) get_option( self::OPTION_NUM_KEY, 1 );
		$date           = (int) get_option( self::OPTION_DATE_KEY, time() );
		if ( $is_permanently > 1 ) {
			return false;
		} elseif ( $is_permanently === 1 && $date > time() ) {
			return false;
		} else {
			return true;
		}
	}


}
