<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
	<div class="inspire-settings">
		<div class="inspire-main-content">
			<?php
    		    $settings_pages = apply_filters('inspire_invoices_settings_pages', array(
    		        'settings' => array(
		                'page' => 'edit.php?post_type=inspire_invoice&page=invoices_settings&tab=settings',
		                'title' => __( 'Settings', 'flexible-invoices' )
		            ),
    		    	'currency' => array(
		                    'page' => 'edit.php?post_type=inspire_invoice&page=invoices_settings&tab=currency',
		                    'title' => __( 'Currency', 'flexible-invoices' )
		            ),
    		    	'tax' => array(
		                    'page' => 'edit.php?post_type=inspire_invoice&page=invoices_settings&tab=tax',
		                    'title' => __( 'Tax rates', 'flexible-invoices' )
		            ),
    		    	'reports' => array(
		                    'page' => 'edit.php?post_type=inspire_invoice&page=invoices_settings&tab=reports',
		                    'title' => __( 'Reports', 'flexible-invoices' )
		            ),
					'generate' => array(
		                    'page' => 'edit.php?post_type=inspire_invoice&page=invoices_settings&tab=generate',
		                    'title' => __( 'Download', 'flexible-invoices' )
		            ),
    		    ));

			    if ( is_flexible_invoices_woocommerce_active() ) {
				    $settings_pages['corrections'] = array(
					    'page'  => 'edit.php?post_type=inspire_invoice&page=invoices_settings&tab=corrections',
					    'title' => __( 'Corrections', 'flexible-invoices' )
				    );
			    }

			?>

			<h2 class="nav-tab-wrapper">
			   <?php foreach ($settings_pages as $key => $item): ?>
			       <a class="nav-tab <?php if ($args['current_tab'] === $key): ?>nav-tab-active<?php endif; ?>" href="<?php echo admin_url( $item['page'] ); ?>"><?php echo $item['title']; ?></a>
			   <?php endforeach; ?>

			</h2>

			<?php
			   if ($args['current_tab'] == 'settings') {
			       echo $this->loadTemplate('submenu_invoices_settings', 'settings', $args);
			   } elseif ($args['current_tab'] == 'currency') {
			       echo $this->loadTemplate('submenu_invoices_currency', 'settings', $args);
			   } elseif ($args['current_tab'] == 'tax') {
			       echo $this->loadTemplate('submenu_invoices_tax', 'settings', $args);
			   } elseif ($args['current_tab'] == 'reports') {
			       echo $this->loadTemplate('submenu_invoices_reports', 'settings', $args);
			   } elseif ($args['current_tab'] == 'generate') {
			       echo $this->loadTemplate('submenu_invoices_generate', 'settings', $args);
			   } elseif ($args['current_tab'] == 'corrections') {
				   echo $this->loadTemplate('submenu_invoices_corrections', 'settings', $args);
			   }
			 ?>

			<?php do_action( 'inspire_invoices_after_display_settings', $args['current_tab'] ); ?>
		</div>

		<div class="inspire-sidebar metabox-holder">
            <?php if ( ! is_flexible_invoices_woocommerce_active() ): ?>
				<?php
					$buylink_en = 'https://www.wpdesk.net/products/flexible-invoices-woocommerce/?utm_source=flexible-invoices-settings&amp;utm_medium=link&amp;utm_campaign=flexible-invoices-woocommerce-extension';
					$buylink_pl = 'https://www.wpdesk.pl/sklep/faktury-woocommerce/?utm_source=flexible-invoices-settings&amp;utm_medium=link&amp;utm_campaign=flexible-invoices-woocommerce-extension';
					$button_url = get_locale() === 'pl_PL' ? $buylink_pl : $buylink_en;
				?>
					<div class="stuffbox">
			        <h3 class="hndle"><?php esc_html_e( 'WooCommerce Integration', 'flexible-invoices' ); ?></h3>
                    <div class="inside">
                        <div class="main">
							<ul>
								<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Automatic invoicing', 'flexible-invoices' ); ?></li>
								<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Corrective invoices', 'flexible-invoices' ); ?></li>
								<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'VAT MOSS support', 'flexible-invoices' ); ?></li>
								<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'EU VAT Number validation in VIES', 'flexible-invoices' ); ?></li>
							</ul>
							<a class="button button-primary" href="<?php echo $button_url; ?>" target="_blank">
								<?php esc_html_e( 'Buy Flexible Invoices Pro →', 'flexible-invoices' ); ?>
							</a>
						</div>
					</div>
                        </div>
                    </div>
			    </div>
            <?php endif; ?>

            <?php if ( is_flexible_invoices_woocommerce_active() ): ?>
                <div class="stuffbox">
                    <h3 class="hndle"><?php _e( 'Get more WP Desk Plugins!', 'flexible-invoices' ); ?></h3>
					<?php
						$ad_links = [
								[
										'link' => esc_attr__( 'https://www.wpdesk.net/products/woocommerce-print-orders-address-labels/?utm_source=flexible-invoices-settings&amp;utm_medium=link&amp;utm_campaign=woocommerce-print-orders-address-labels', 'flexible-invoices' ),
										'label' => esc_html__( 'Print Orders and Address Labels', 'flexible-invoices' ),
										'descr' => esc_html__( '- Speed up the fulfillment process, packing and shipping by printing address labels and order details.', 'flexible-invoices' ),
								],
								[
										'link' => esc_attr__( 'https://www.wpdesk.net/products/active-payments-woocommerce/?utm_source=flexible-invoices-settings&amp;utm_medium=link&amp;utm_campaign=active-payments-plugin', 'flexible-invoices' ),
										'label' => esc_html__( 'Active Payments', 'flexible-invoices' ),
										'descr' => esc_html__( '- Conditionally hide payment methods for cash on delivery shipping options. Add fees to payment methods.', 'flexible-invoices' ),
								],
								[
										'link' => esc_attr__( 'https://www.wpdesk.net/products/flexible-pricing-woocommerce/?utm_source=flexible-invoices-settings&amp;utm_medium=link&amp;utm_campaign=flexible-pricing-woocommerce', 'flexible-invoices' ),
										'label' => esc_html__( 'Flexible Pricing', 'flexible-invoices' ),
										'descr' => esc_html__( '- Create promotions like Buy One Get One Free to get more sales in your store.', 'flexible-invoices' ),
								],
						]
					?>
                    <div class="inside">
						<div class="main">
							<div class="main">
								<?php foreach( $ad_links as $ad_link ): ?>
								<?php echo sprintf( '<p><a href="%s" target="_blank">%s</a> %s</p>', $ad_link['link'], $ad_link['label'], $ad_link['descr'] ); ?>
								<?php endforeach; ?>
							</div>
						</div>
                    </div>
                </div>
            <?php endif; ?>
		</div>
	</div>
</div>
