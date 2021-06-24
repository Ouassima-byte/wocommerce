<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class invoicePostType
{

	/**
	 * Name of the directory where invoices will be stored.
	 */
	const INVOICE_DIRECTORY_NAME = 'wordpress_invoices';

    const POST_TYPE = 'inspire_invoice';

    protected $_postType = 'inspire_invoice';
	protected $_prefix = "_invoice_";

    protected $_increase_number_on_save = false;
	protected $_increase_correction_number_on_save = false;

	protected $_postTypeArray = array();
	protected $_metaboxes = array();
	protected $_plugin;

	/** @var invoicePostTypeCapabilities */
	private $capabilities;

	const WP_OPTION_INVOICE_CAPABILITIES_POPULATED = 'invoice_capabilities_populated';

	const INVOICE_CAPABILITIES_VERSION = 1;

	/** @var string NONCE_ARG */
	const NONCE_ARG = 'security';

	public function __construct( Invoice $plugin, invoicePostTypeCapabilities $capabilities ) {
		$this->_plugin = $plugin;
		$this->capabilities = $capabilities;
		$post_type = $this->getPostTypeSlug();

		add_action( 'init', array( $this, 'initPostTypeAction' ), 0 );
		add_action( 'add_meta_boxes', array( $this, 'createCustomFieldsAction' ), 1, 2 );
		add_action( 'save_post', array( $this, 'saveCustomFieldsAction' ), 1, 2 );
		add_filter( 'manage_edit-' . $post_type . '_columns', array( $this, 'addCustomColumnsFilter' ) );
		add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'displayCustomColumnFilter' ), 10, 2 );
		add_action( 'wp_ajax_invoice-get-invoice', array( $this, 'getInvoiceAction' ) );
		add_action( 'wp_ajax_invoice-get-pdf-invoice', array( $this, 'getInvoicePdfAction' ) );
		add_action( 'wp_ajax_nopriv_invoice-get-invoice', array( $this, 'getInvoiceAction' ) );
		add_action( 'wp_ajax_nopriv_invoice-get-pdf-invoice', array( $this, 'getInvoicePdfAction' ) );
		add_action( 'wp_ajax_invoice-get-client-data', array( $this, 'getClientDataAction' ) );
		add_action( 'wp_ajax_invoice-send-by-email', array( $this, 'sendInvoiceByEmailAction' ) );
		add_action( 'wp_ajax_woocommerce-invoice-batch-download', array( $this, 'batch_download_action' ) );
		add_action( 'wp_ajax_woocommerce-invoice-user-select', array( $this, 'selectAjaxUserSearch' ) );

		if ( is_admin() ) {
			add_filter( 'post_row_actions', array( $this, 'removeQuickEditAction' ), 10, 2 );
			add_filter( 'default_title', array( $this, 'newInvoiceDefaultTitleFilter' ), 80, 2 );
			add_action( 'admin_init', array( $this, 'setDefaultLayoutAction' ) );
			add_action( 'admin_init', array( $this->capabilities, 'assignBasicRolesCapabilitiesAction' ) );
			add_action( 'restrict_manage_posts', array( $this, 'addInvoiceListingFiltersAction' ) );
			add_filter( 'months_dropdown_results', array( $this, 'modifyInvoiceListingMonthsFilter' ), 80, 2 );
			add_filter( 'parse_query', array( $this, 'filterInvoiceListingFilter' ) );
			add_filter( 'bulk_actions-edit-inspire_invoice', array( $this, 'setBulkActionsFilter' ) );
			add_action( 'admin_notices', array( $this, 'bulkActionsFilterAdminNotices' ) );
			add_filter( 'handle_bulk_actions-edit-inspire_invoice', array( $this, 'setBulkActionsHandler' ), 10, 3 );
			add_filter( 'post_updated_messages', array( $this, 'changeDefaultWordpressPostMessagesFilter' ) );
			add_action( 'before_delete_post', array( $this, 'removePdfInvoiceAction' ) );
			add_action( 'flexible_invoices_head', array( $this, 'head_scripts' ) );
			add_filter( 'views_edit-inspire_invoice', array( $this, 'add_duplicated_filter' ));
		}

	}

	public function add_duplicated_filter( $views ){
		$views['duplicated'] = sprintf( __( '<a href="%s">Duplicated <span class="count">(%d)</span></a>', 'flexible-invoices' ), admin_url( 'edit.php?post_type=' . $this->_postType .'&filter=show_duplicated' ), count($this->get_duplicated_posts_ids()) );
		return $views;
	}

	public function removePdfInvoiceAction( $id ){

	    // We check if the global post type isn't ours and just return
	    global $post_type;
	    if ( $post_type != 'inspire_invoice' ) return;

			$invoice = $this->invoiceFactory( $id );

			$path = $this->getPdfPath($invoice);
			if( $path ) {
				$file = $path . '/' . str_replace( array( '/' ), array( '_' ), $invoice->getFormattedInvoiceNumber() ) . '.pdf';

				if( file_exists( $file ) ) {
					unlink( $file );
				}
			}
	}

	public function changeDefaultWordpressPostMessagesFilter($messages)
	{
	    global $post, $post_ID;
	    $post_type = get_post_type( $post_ID );

	    $obj = get_post_type_object($post_type);
	    $singular = $obj->labels->singular_name;

		$messages['inspire_invoice'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __('Invoice updated.', 'flexible-invoices' ),
			2 => __('Custom field updated.', 'flexible-invoices' ),
			3 => __('Custom field deleted.', 'flexible-invoices' ),
			4 => __('Invoice updated.', 'flexible-invoices' ),
			5 => isset($_GET['revision']) ? sprintf( __($singular.' rolled back to revision %s.', 'flexible-invoices' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __('Invoice issued.', 'flexible-invoices' ),
			7 => __('Invoice saved.', 'flexible-invoices' ),
			8 => __('Invoice submitted.', 'flexible-invoices' ),
			9 => __('Invoice scheduled', 'flexible-invoices' ),
			10 => __('Invoice draft updated', 'flexible-invoices' ),
		);

	    return $messages;
	}

	public function setBulkActionsFilter($actions)
	{
	    if ( isset( $actions['edit'] ) )
	    {
	        unset( $actions['edit'] );
	    }

	    $actions['set_as_payed'] = __('Paid', 'flexible-invoices');

	    return $actions;
	}

	public function setBulkActionsHandler( $redirect_to, $doaction, $post_ids ) {
		if ( $doaction !== 'set_as_payed' ) {
			return $redirect_to;
		}
		foreach ( $post_ids as $post_id ) {
			$invoice = $this->invoiceFactory($post_id);
			$invoice->setPaymentStatus('paid');
			$invoice->save();
		}
		$redirect_to = add_query_arg( 'bulk_set_as_payed', count( $post_ids ), $redirect_to );
		return $redirect_to;
	}

	public function bulkActionsFilterAdminNotices() {
		if ( ! empty( $_REQUEST['bulk_set_as_payed'] ) ) {
			$invoices_count = intval( $_REQUEST['bulk_set_as_payed'] );
			printf( '<div id="message" class="updated notice"><p>' .
			        _n( '%s invoice marked as paid.',
				        '%s invoices marked as paid.',
				        $invoices_count,
				        'flexible-invoices'
			        ) . '</p></div>', $invoices_count );
		}
    }

    private function get_duplicated_posts_ids(){
	    global $wpdb;
	    $result = array();
	    $rows = $wpdb->get_col( $wpdb->prepare( "SELECT GROUP_CONCAT(p.ID) FROM $wpdb->posts as p WHERE p.post_type = %s AND p.post_status = %s GROUP BY p.post_title HAVING COUNT( p.post_title ) > 1", $this->_postType, 'publish' ) );

	    if(!empty($rows)){
	    	foreach ($rows as $row){
			    $result = array_merge( $result, explode(',', $row) );
		    }
	    }

		return $result;
    }

	public function filterInvoiceListingFilter($query)
	{
	    global $pagenow;
	    $qv = &$query->query_vars;
	    if ($pagenow == 'edit.php' && isset( $qv['post_type'] ) && $qv['post_type'] == 'inspire_invoice')
	    {
	        $meta_query = array();
			if( isset($_GET['filter']) && 'show_duplicated' === $_GET['filter']  ){
				$qv['post__in'] = $this->get_duplicated_posts_ids();
			}

	        if (!empty($_GET['paystatus']))
	        {
	            if ($_GET['paystatus'] == 'exceeded')
	            {
	                $meta_query[] = array(
                        'key' => '_payment_status',
                        'value' => 'topay',
                        'compare' => 'LIKE'
	                );
	                $meta_query[] = array(
	                        'key' => '_date_pay',
	                        'value' => strtotime(date('Y-m-d 00:00:00')),
	                        'compare' => '<'
	                );
	            } else {
	                $meta_query[] = array(
                        'key' => '_payment_status',
                        'value' => $_GET['paystatus'],
                        'compare' => 'LIKE'
	                );
	            }

	        }

	        if (!empty($_GET['user']))
	        {
	            $user = new WP_User((int)$_GET['user']);
	            if (empty($user->billing_company))
    	        {
    	            $name = $user->billing_first_name . ' ' . $user->billing_last_name;
    	        } else{
    	            $name = $user->billing_company;
    	        }

	            $meta_query[] = array(
                    'key' => '_client_filter_field',
                    'value' => $name,
                    'compare' => 'LIKE'
	            );
	        }

	        if (!empty($_GET['m']))
	        {
	            unset($qv['m']);
	            $m = strtotime(substr($_GET['m'], 0, 4) . '-' . substr($_GET['m'], 4, 2) . '-01 00:00:00');

	            $meta_query[] = array(
                    'key' => '_date_issue',
                    'value' => array($m, strtotime(date('Y-m-t 23:59:59', $m))),
                    'compare' => 'BETWEEN',
	                'type' => 'UNSIGNED'
	            );
	        }
	        if (!empty($meta_query))
	        {
	            $qv['meta_query'] = $meta_query;
	        }
	    }

	    return $query;
	}

	public function modifyInvoiceListingMonthsFilter($months, $post_type)
	{
	    if ($post_type == 'inspire_invoice')
	    {
	        global $wpdb;

	        $months = $wpdb->get_results( $wpdb->prepare( "
	                SELECT DISTINCT YEAR( FROM_UNIXTIME( pm.meta_value ) ) AS year, MONTH( FROM_UNIXTIME ( pm.meta_value ) ) AS month
	                FROM
	                   $wpdb->posts p,
	                   $wpdb->postmeta pm
	                WHERE
	                   pm.post_id = p.id AND
	                   p.post_type = %s AND
	                   pm.meta_key = '_date_issue'
	                ORDER BY
	                   pm.meta_value DESC
	                ", $post_type ) );
	    }
	    return $months;

	}

    /*
     * Search user via AJAX for user list
     *
     * return void
     */
	public function selectAjaxUserSearch() {
		$client_options = array();
		if( check_ajax_referer( $this->_plugin->getPluginNamespace(), self::NONCE_ARG, false ) ) {
            $name = $_POST['name'];
            if( $name ) {
                $users = new WP_User_Query( array(
                    'search'         => '*' . esc_attr( $name ) . '*',
                    'search_columns' => array(
                        'user_login',
                        'user_nicename',
                        'user_email',
                        'user_url',
                    ),
                ) );
                $users_results = $users->get_results();

                $users_meta = new WP_User_Query( array(
                    'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'billing_first_name',
                            'value'   => esc_attr( $name ),
                            'compare' => 'LIKE'
                        ),
                        array(
                            'key'     => 'billing_last_name',
                            'value'   => esc_attr( $name ),
                            'compare' => 'LIKE'
                        ),
                        array(
                            'key' => 'billing_company',
                            'value' => esc_attr( $name ) ,
                            'compare' => 'LIKE'
                        )
                    )
                ) );
                $users_meta_results = $users_meta->get_results();
                $results = array_merge( $users_results, $users_meta_results );
	            foreach( $results as $user ) {
		            $client_options[ $user->ID ] = array(
			            'id' => $user->ID,
			            'text' => $this->prepareOptionText( $user )
		            );
	            }

            }
            wp_send_json( array( 'items' => array_values( $client_options ) ) );
		}
		wp_send_json( $client_options );
    }

	/**
     * Process data from user object for select
	 * @param $user
	 *
	 * @return string
	 */
    public function prepareOptionText( $user ) {
        $name = '';
        $user_meta = get_user_meta( $user->ID );

        if ( isset( $user_meta['billing_company'][0] ) ) {
            $company = $user_meta['billing_company'][0];
            if ( !empty( $company ) ) {
                $name .= $company . ', ';
            }
        }
        if ( isset( $user_meta['billing_first_name'][0] ) ) {
            $billing_first_name = $user_meta['billing_first_name'][0];
            if ( !empty( $billing_first_name ) ) {
                $name .= $user_meta['billing_first_name'][0] . ' ';
            }
        }
        if ( isset( $user_meta['billing_last_name'][0] ) ) {
            $billing_last_name = $user_meta['billing_last_name'][0];
            if ( !empty( $billing_last_name ) ) {
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
    public function getSelectedUser() {
	    $user_data = array();
	    if ( isset( $_GET['user'] ) ) {
            $user_id = (int) $_GET['user'];
		    $user = get_userdata( $user_id );
		    if( $user ) {
			    $user_data = array( 'id' => $user_id, 'text' => $this->prepareOptionText( $user ) );
            }
	    }
	    return $user_data;
    }

	public function addInvoiceListingFiltersAction() {
	    global $typenow;
	    if ($typenow == 'inspire_invoice') {

	        $selected = $this->getSelectedUser();
    	    echo '<select name="user" id="inspire_invoice_client_select">';
            if( isset( $selected['id'] ) ) {
	            echo '<option value="'. $selected['id'] .'">'. $selected['text'] .'</option>';
            }
    	    echo '</select>';

    	    echo '<select name="paystatus">';
    	    $statuses = $this->getPaymentStatuses();
    	    $statuses['exceeded'] = __('Overdue', 'flexible-invoices');
    	    echo '<option value="">' . __('All statuses', 'flexible-invoices') . '</option>';
    	    $paystatus = '';
    	    if ( isset( $_GET['paystatus'] ) ) {
    	        $paystatus = $_GET['paystatus'];
            }
    	    foreach ($statuses as $key => $status)
    	    {
    	        echo '<option value="' . $key . '" ' . ($key == $paystatus ? 'selected="selected"': '') . '>' . $status . '</option>';
    	    }
    	    echo '</select>';
	    }
	}

	public function addCustomColumnsFilter( $columns ) {

	    unset($columns['date']);
		unset($columns['title']);

		$columns['invoice_title'] = __('Invoice', 'flexible-invoices' );
		$columns['client'] = __('Customer', 'flexible-invoices' );
		$columns['netto'] = __('Net price', 'flexible-invoices' );
		$columns['brutto'] = __('Gross price', 'flexible-invoices' );
		$columns['issue'] = __('Issue date', 'flexible-invoices' );
		$columns['pay'] = __('Due date', 'flexible-invoices' );

		$columns['order'] = __('Order', 'flexible-invoices' );
		$columns['status'] = __('Payment status', 'flexible-invoices' );

		$columns['sale'] = __('Date of sale', 'flexible-invoices' );
		$columns['currency'] = __('Currency', 'flexible-invoices' );
		$columns['paymethod'] = __('Payment method', 'flexible-invoices' );

		$columns['actions'] = __('Actions', 'flexible-invoices' );

	    return $columns;
	}

	/**
	 * Find duplicates.
	 *
	 * @param string $post_title Post title.
	 *
	 * @return int
	 */
	private function find_duplicates( $post_title ) {
    	global $wpdb;
		$duplicates = $wpdb->get_var( $wpdb->prepare( "SELECT count(ID) FROM {$wpdb->posts} WHERE `post_title` = %s AND `post_type` = %s AND `post_status` = 'publish'", $post_title, InvoicesNotices::POST_TYPE_NAME ) );
		return (int) $duplicates;
	}

	/**
	 * Add custom column body.
	 *
	 * @param string $column_name Column name,
	 * @param int    $post_id     Post ID.
	 */
	public function displayCustomColumnFilter($column_name, $post_id) {
	    global $post;
	    $invoice = $this->invoiceFactory($post_id);

		switch ($column_name)
		{
            case 'invoice_title':
            	$duplicates = $this->find_duplicates( $post->post_title );
	            $class = '';
	            $title_duplicated = '';

            	if( $duplicates > 1 ) {
		            $class = 'is_duplicated';
		            $title_duplicated = __( 'The name of invoice is duplicated!', 'flexible-invoices' );
	            }
                if ( $invoice->getCorrection() == '1' ) {
	                echo sprintf( '<span class="%s"><strong>%s</strong></span>', $class, $post->post_title );
                }
                else {
	                echo sprintf( '<strong><a class="%s" title="%s" href="%s">%s</a></strong>', $class, $title_duplicated, get_edit_post_link( $post_id ), $post->post_title );
                }
                break;
		    case 'client':
		          $client = $invoice->getClient();
		          echo @$client['name'];
		        break;
		    case 'netto':
		          echo $invoice->stringAsMoneyField($invoice->getCalculatedTotalNetPrice());
		        break;
	        case 'brutto':
                echo $invoice->stringAsMoneyField($invoice->getTotalPrice());
            break;
            case 'issue':
                echo @$invoice->getDateOfIssue();
            break;

            case 'pay':
                echo @$invoice->getDateOfPay();
            break;
            case 'order':
                do_action('inspire_invoices_display_order_column_for_invoice', $invoice);
            break;
            case 'status':
                echo @$invoice->getPaymentStatusString();
            break;
            case 'sale':
                echo @$invoice->getDateOfSale();
            break;
            case 'currency':
                echo @$invoice->getCurrency();
            break;
            case 'paymethod':
                echo @$invoice->getPaymentMethodString();
            break;
            case 'actions':
            	echo @$invoice->getActions( $invoice );
            break;

			default:
			    echo get_post_meta( $post_id, $this->_prefix . $column_name, true );
			break;
		}

	}

	/**
	 *
	 * @param int $id
	 * @return invoicePost
	 */
	public function invoiceFactory($id)
	{
	    $invoicePostClass = apply_filters('inspire_invoices_invoice_post_class', 'InvoicePost');
	    return new $invoicePostClass($id, $this->_plugin);
	}

	public function getPaymentStatuses()
	{
	    return apply_filters('inspire_invoices_payment_statuses', array(
            'topay' => __('Due', 'flexible-invoices'),
            'paid' => __('Paid', 'flexible-invoices'),
	    ));
	}


	public function getPaymentMethods() {
		$payment_methods = explode( "\n", $this->_plugin->getSettingValue( 'payment_methods', implode( "\n", array(
            'bank-transfer' => __('Bank transfer', 'flexible-invoices'),
            'cash' => __('Cash', 'flexible-invoices'),
            'orher' => __('Other', 'flexible-invoices')
        ) ) ) );
		$ret = array();
		foreach ( $payment_methods as $payment_method ) {
			$ret[sanitize_title( $payment_method )] = $payment_method;
		}
		return $ret;
        //return apply_filters( 'inspire_invoices_payment_methods', $ret );
		/*
        return apply_filters('inspire_invoices_payment_methods', array(
            'transfer' => __('Bank transfer', 'flexible-invoices'),
            'cash' => __('Cash', 'flexible-invoices'),
            'orher' => __('Other', 'flexible-invoices')
        ));
        */
	}

	public function getPaymentMethodsWoo( $append = false ) {
		return apply_filters('inspire_invoices_payment_methods', array(	));
	}

	public function appendPaymentMethod( $payment_methods, $payment_methods_woo, InvoicePost $invoice ) {
		if ( !empty( $invoice ) ) {
			$_payment_method = $invoice->getPaymentMethod();
			$_payment_method_name = $invoice->getPaymentMethodName();
			if ( isset( $_payment_method ) && $_payment_method != '' && !isset( $payment_methods[$_payment_method] ) && !isset( $payment_methods_woo[$_payment_method] ) ) {
				if ( isset( $_payment_method_name ) && $_payment_method_name != '' ) {
					$payment_methods[$_payment_method] = $_payment_method_name;
				}
				else {
					$payment_methods[$_payment_method] = $_payment_method;
				}
			}
		}
		return $payment_methods;
	}

	public function getPaymentCurrencies()
	{
		$inspire_invoices_currency = get_option('inspire_invoices_currency', array() );
		$ret = array();
		if ( is_array( $inspire_invoices_currency ) ) {
			foreach ( $inspire_invoices_currency as $currency ) {
				$ret[$currency['currency']] = $currency['currency'];
			}
		}
		return $ret;
	}

	public function getVatTypes()
	{
		$rates = array();
		$inspire_invoices_tax = get_option('inspire_invoices_tax', array() );

		$index = 0;
		foreach ( $inspire_invoices_tax as $tax ) {
			$rates[] = array( 'index' => $index, 'rate' => $tax['rate'], 'name' => $tax['name'] );
			$index++;
		}

        return apply_filters('inspire_invoices_vat_types', $rates );
	}

	public function getPostTypeArray()
	{
		global $menu;
		$menu_pos = 56.8673974;
		while ( isset( $menu[$menu_pos] ) ) {
			$menu_pos++;
		}
		return array(
				'label'               => 'inspire_invoice',
				'description'         => __('Invoices', 'flexible-invoices'),
				'labels'              => array(
						'name'                => __('Invoices', 'flexible-invoices'),
						'singular_name'       => __('Invoice', 'flexible-invoices'),
						'menu_name'           => __('Invoices', 'flexible-invoices'),
						'parent_item_colon'   => '',
						'all_items'           => __('All Invoices', 'flexible-invoices'),
						'view_item'           => __('View Invoice', 'flexible-invoices'),
						'add_new_item'        => __('Add New Invoice', 'flexible-invoices'),
						'add_new'             => __('Add New', 'flexible-invoices'),
						'edit_item'           => __('Edit Invoice', 'flexible-invoices'),
						'update_item'         => __('Save Invoice', 'flexible-invoices'),
						'search_items'        => __('Search Invoices', 'flexible-invoices'),
						'not_found'           => __('No invoices found.', 'flexible-invoices'),
						'not_found_in_trash'  => __('No invoices found in Trash.', 'flexible-invoices')
				),
				'supports'            => array( 'title' ),
				'taxonomies'          => array( ),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => $menu_pos,
				'menu_icon'           => 'dashicons-media-spreadsheet',
				'can_export'          => false,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => [invoicePostTypeCapabilities::CAPABILITY_SINGULAR, invoicePostTypeCapabilities::CAPABILITY_PLURAL],
				'map_meta_cap'        => false,
                'cap' => $this->capabilities->getPostCapabilityMapAsObject()
		);
	}


	public function setDefaultLayoutAction()
	{
	    $user = wp_get_current_user();
	    $columns = get_user_meta($user, 'screen_layout_inspire_invoice', true);
	    if (empty($columns))
	    {
	       update_user_meta($user, 'screen_layout_inspire_invoice', 1);
	    }

	    $hidden = get_user_meta($user, 'manageedit-inspire_invoicecolumnshidden', true);
	    if ($hidden === '')
	    {
	        $hidden = array('sale', 'currency', 'paymethod');
	        update_user_meta($user, 'manageedit-inspire_invoicecolumnshidden', $hidden);
	    }
	}

	public function removeQuickEditAction($actions)
	{
	    global $post;
		if( $post->post_type == $this->getPostTypeSlug() )
	    {
	        unset($actions['inline hide-if-no-js']);
	    }
	    return $actions;
	}

	public function sendInvoiceByEmailAction()
	{
	    if ( isset( $_POST['email'] ) && filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) )
	    {
    	    $invoice = $this->invoiceFactory($_POST['id']);
    	    $order = $invoice->getOrder();


    	    if (!empty($order))
    	    {
    	    	$invoice->sendByEmail($_POST['email']);
    	    	$result = array(
    	    			'result' => 'OK',
    	    			'email' => $_POST['email'],
    	    			'code' => 100
    	    	);
    	    } else {
    	    	$result = array(
    	    			'result' => 'OK',
    	    			'code' => 102
    	    	);
    	    }

	    } else {
	        $result = array(
                'result' => 'OK',
                'code' => 101
	        );
	    }


	    header('Content-Type: application/json');
	    echo json_encode($result);

	    die();
	}

	public function getClientDataAction()
	{
		if( current_user_can( 'edit_posts' ) && check_ajax_referer( $this->_plugin->getPluginNamespace(), self::NONCE_ARG, false ) ) {
			$user = get_user_by('id', (int) $_REQUEST['client']);
			if (!empty($user))
			{
				$userdata = array(
					'name' => $user->first_name . ' ' . $user->last_name,
					'street' => '',
					'postcode' => '',
					'city' => '',
					'nip' => '',
					'country' => '',
					'phone' => '',
					'email' => $user->user_email
				);

				$result = array(
					'result' => 'OK',
					'code' => 100,
					'userdata' => apply_filters('inspire_invoices_client_data', $userdata, $_REQUEST['client'])
				);
			} else {
				$result = array(
					'result' => 'OK',
					'code' => 101
				);
			}
			header('Content-Type: application/json');
			echo json_encode($result);

			die();
		}
	}

	public function newInvoiceDefaultTitleFilter($post_title, $post)
	{
	    if (empty($post_title) && $post->post_type == 'inspire_invoice')
	    {
	        $invoice = $this->invoiceFactory($post->ID);
	        $invoice->setDefaultValuesIfNumberEmpty();
	        //$invoice->save();
	        return $invoice->getFormattedInvoiceNumber();
	    } else {
	        return $post_title;
	    }
	}

	/**
	 * @param string $where
	 *
	 * @return string
	 */
	public function invoice_filter_where( $where = '' ) {
		$where .= " AND meta_value >= '" . strtotime(date('Y-m-d 00:00:00', strtotime($_GET['start_date']))) . "' AND meta_value <= '" . strtotime(date('Y-m-d 23:59:59', strtotime($_GET['end_date']))) . "'";
		return $where;
	}

	/**
	 * Batch invoices download.
	 */
	public function batch_download_action() {
		if ( isset( $_GET['batch_download'] ) && wp_verify_nonce( $_GET['batch_download'], 'download_invoices' ) && current_user_can( 'manage_options' ) ) {
			$zip      = new ZipArchive();
			$filename = 'invoices.zip';
			$zip->open( $this->getPdfPath() . $filename, ZipArchive::CREATE );

			add_filter( 'posts_where', array( $this, 'invoice_filter_where' ) );
			$invoices_query = new WP_Query( array(
				'post_type'   => 'inspire_invoice',
				'orderby'     => 'date',
				'order'       => 'ASC',
				'post_status' => 'publish',
				'nopaging'    => true,
				'meta_key'    => '_date_issue',
			) );
			$invoices       = $invoices_query->get_posts();
			remove_filter( 'posts_where', 'filter_where' );

			if ( ! count( $invoices ) ) {
				$zip->addFromString( 'no_invoices', '' );
			} else {
				foreach ( $invoices as $invoice_post ) {
					$id      = $invoice_post->ID;
					$invoice = $this->invoiceFactory( $id );
					$pdfData = $this->generatePdfFileContent( $invoice );
					$zip->addFromString( str_replace( array( '/' ), array( '_' ), $invoice->getFormattedInvoiceNumber() ) . '.pdf', $pdfData );
				}
			}
			$zip->close();

			header( 'Content-Type: application/zip' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			readfile( $this->getPdfPath() . $filename );
			unlink( $this->getPdfPath() . $filename );
			exit;
		}
	}

	/**
	 * admin ajax action
	 */
	public function getInvoiceAction( $id = null ) {
		if ( empty( $id ) ) {
			$id = $_GET['id'];
		}

		if ( is_admin() && isset( $_GET['hash'] ) && $_GET['hash'] == md5( NONCE_SALT . $_GET['id'] ) ) //|| current_user_can( 'manage_options' )
		{
			$invoice = $this->invoiceFactory( $id );

			do_action( 'flexible_invoices_pre_generate_pdf', $invoice );

			if ( $invoice->getCorrection() == '1' ) {
				echo $this->_plugin->loadTemplate( 'generated_correction', 'invoice', array(
					'invoice' => $invoice,
					'plugin'  => $this->_plugin
				) );
			} else {
				echo $this->_plugin->loadTemplate( 'generated_invoice', 'invoice', array(
					'invoice' => $invoice,
					'plugin'  => $this->_plugin
				) );
			}
		}
		die();
	}

	/**
	 * admin ajax action
	 */
	public function getInvoicePdfAction( $id = null) {
	    if (empty($id)) {
	       $id = $_GET['id'];
	    }
	    if ( ( isset( $_GET['hash'] ) && $_GET['hash'] == md5( NONCE_SALT . $id ) ) || current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) ) {
            $this->sendToBrowser($id);
	    }
	    die();
	}

	/**
	 * Get PDF path.
	 *
	 * @param WP_Post $invoice Invoice.
	 *
	 * @return string|null
	 */
	public function getPdfPath() {
		$upload_dir = wp_upload_dir();
		$path  = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( self::INVOICE_DIRECTORY_NAME );
		if( wp_mkdir_p( $path ) ) {
			file_put_contents( $path . '.htaccess', 'deny from all' );
		}
		return $path;
	}


	/**
	 * @return string
	 */
	private function temp_dir() {
		$upload_dir = \wp_upload_dir();
		$temp_dir   = \trailingslashit( $upload_dir['basedir'] ) . 'mpdf/tmp/';
		\wp_mkdir_p( $temp_dir );

		return $temp_dir;
	}

	/**
	 * @return array
	 */
	public function get_font_dir() {
		return [ \trailingslashit( $this->_plugin->get_plugin_dir() . '/vendor_prefixed/wpdesk/flexible-invoices-core/assets/fonts' ) ];
	}

	/**
	 * @return array
	 */
	private function fonts_data() {
		return [
				'dejavusanscondensed' => [
						'R'  => 'DejaVuSansCondensed.ttf',
						'I'  => 'DejaVuSansCondensed-Oblique.ttf',
						'B'  => 'DejaVuSansCondensed-Bold.ttf',
						'BI' => 'DejaVuSansCondensed-BoldOblique.ttf'
				],
		];
	}

	/**
	 * Set default config
	 */
	private function set_default_config() {
		return [
				'mode'             => '+aCJK',
				'format'           => 'A4',
				'autoLangToFont'   => \true,
				'autoScriptToLang' => \true,
				'tempDir'          => $this->temp_dir(),
				'fontDir'          => $this->get_font_dir(),
				'fontdata'         => $this->fonts_data(),
				'default_font'     => 'dejavusanscondensed'
		];
	}

	public function generatePdfFileContent($invoice) {
		$mpdf = new \WPDeskFIVendor\Mpdf\Mpdf( $this->set_default_config() );
		$mpdf->img_dpi = 200;
		//$mpdf->debug = true;

		do_action( 'flexible_invoices_pre_generate_pdf', $invoice );

        if ( $invoice->getCorrection() == '1' ) {
	        $mpdf->WriteHTML( $this->_plugin->loadTemplate( 'generated_correction', 'invoice', array(
		        'invoice' => $invoice,
		        'plugin'  => $this->_plugin
	        ) ) );
        }
        else {
	        $mpdf->WriteHTML( $this->_plugin->loadTemplate( 'generated_invoice', 'invoice', array(
		        'invoice' => $invoice,
		        'plugin'  => $this->_plugin
	        ) ) );
        }
		$pdfData = $mpdf->Output( str_replace(array('/'), array('_'), $invoice->getFormattedInvoiceNumber()) . '.pdf', 'S' );
		return $pdfData;
	}

	/**
	 * Debug HTML before render.
	 *
	 * Define FLEXIBLE_INVOICES_DEBUG in wp-config.php if you want display HTML not PDF in browser.
	 *
	 * @param $invoice
	 */
	public function debug_before_render_pdf( $invoice ) {
		echo $this->_plugin->loadTemplate( 'generated_invoice', 'invoice', array(
			'invoice' => $invoice,
			'plugin'  => $this->_plugin
		) );
		die();
	}

	/**
	 * @param int $invoice_id
	 *
	 * @return void
	 */
	public function sendToBrowser( $invoice_id ) {
		$post = get_post( $invoice_id );
		if ( ! $post ) {
			wp_die( __( 'This document does noe exist or was deleted.', 'flexible-invoices' ) );
		}
		$invoice = $this->invoiceFactory( $invoice_id );
		$name    = str_replace( array( '/' ), array( '_' ), $invoice->getFormattedInvoiceNumber() ) . '.pdf';

		if ( defined( 'FLEXIBLE_INVOICES_DEBUG' ) ) {
			$this->debug_before_render_pdf( $invoice );
		}

		header( 'Content-type: application/pdf' );
		if ( isset( $_GET['save_file'] ) ) {
			header( 'Content-Disposition: attachment; filename="' . $name . '"' );
		} else {
			header( 'Content-Disposition: inline; filename="' . $name . '"' );
		}
		$pdfData = $this->generatePdfFileContent( $invoice );
		echo $pdfData;
		exit;

	}

	public function createCustomFieldsAction($post_type, $post = null)
	{
	    if ($post_type == 'inspire_invoice')
	    {
    	    $invoice = $this->invoiceFactory($post->ID);

            add_meta_box( 'owner', __('Seller', 'flexible-invoices'), array( $this, 'displayOwnerMetaboxAction' ), $this->getPostTypeSlug(), 'normal', 'high', array('invoice' => $invoice) );
    	    add_meta_box( 'client', __('Customer', 'flexible-invoices'), array( $this, 'displayClientMetaboxAction' ), $this->getPostTypeSlug(), 'normal', 'high', array('invoice' => $invoice) );
    	    add_meta_box( 'products', __('Products', 'flexible-invoices'), array( $this, 'displayProductsMetaboxAction' ), $this->getPostTypeSlug(), 'normal', 'high', array('invoice' => $invoice) );
    	    add_meta_box( 'payment', __('Payments and other info', 'flexible-invoices'), array( $this, 'displayPaymentMetaboxAction' ), $this->getPostTypeSlug(), 'normal', 'high', array('invoice' => $invoice) );
    	    add_meta_box( 'options', __('Dates and actions', 'flexible-invoices'), array( $this, 'displayOptionsMetaboxAction' ), $this->getPostTypeSlug(), 'side', 'low', array('invoice' => $invoice) );
    	    add_meta_box( 'submitdiv2', __( 'Save/Issue', 'flexible-invoices' ), 'post_submit_meta_box', null, 'normal', 'low' );
	    }
	}

	public function saveCustomFieldsAction($post_id, $post)
	{

		if ( ! isset( $_POST['flexible_invoices_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['flexible_invoices_nonce'], 'flexible_invoices_nonce' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

	    if (!empty($_POST) && $post->post_type == 'inspire_invoice')
	    {
    	    $invoice = $this->invoiceFactory($post->ID);

    	    $invoice->setDefaultValuesIfNumberEmpty();

	        $invoice->setDateOfIssue( $_POST['date_issue'] );
    	    $invoice->setDateOfSale($_POST['date_sale']);
    	    $invoice->setDateToPay($_POST['date_pay']);

    	    $invoice->setNumber($_POST['number']);
    	    $invoice->setFormattedNumber($_POST['post_title']);

    	    $invoice->setCurrency($_POST['currency']);
    	    $invoice->setPaymentMethod($_POST['payment_method']);

    	    $_payment_method_name = '';
    	    $payment_methods = $this->getPaymentMethods();
    	    $payment_methods_woo = $this->getPaymentMethodsWoo();
    	    $payment_methods = $this->appendPaymentMethod( $payment_methods, $payment_methods_woo, $invoice );

    	    if ( isset( $payment_methods[$_POST['payment_method']] ) ) {
    	    	$_payment_method_name = $payment_methods[$_POST['payment_method']];
    	    }
    	    else {
    	    	$_payment_method_name = $payment_methods_woo[$_POST['payment_method']];
    	    }

    	    $invoice->setPaymentMethodName( $_payment_method_name );

    	    $invoice->setPaymentStatus($_POST['payment_status']);
    	    $invoice->setTotalPrice($_POST['total_price']);
    	    $invoice->setTotalPaid($invoice->format_decimal( $_POST['total_paid'] ) );

    	    $invoice->setNotes( strip_tags( $_POST['notes'] ) );

    	    //$invoice->setAddOrderId($_POST['add_order_id'] == 1);

            $invoice->setOwnerFromArray($_POST['owner']);
    	    $invoice->setClientFromArray($_POST['client']);
    	    if (!empty($_POST['product'])) {
		        $invoice->setProductsFromPostArray($_POST['product']);
            }


    	    do_action('inspire_invoices_before_save_invoice_custom_fields', $invoice);

    	    $invoice->save();

    	    do_action('inspire_invoices_after_save_invoice_custom_fields', $invoice);
	    }
	}

	public function displayOptionsMetaboxAction($post, $args)
	{
	    $invoice = $args['args']['invoice'];

	    echo $this->_plugin->loadTemplate('options_metabox', 'invoice_edit', array(
	            'invoice' => $invoice,
	            'plugin' => $this->_plugin
	    ));
	}

	public function displayOwnerMetaboxAction( $post, $args ) {
		$invoice = $args['args']['invoice'];
		echo $this->_plugin->loadTemplate( 'owner_metabox', 'invoice_edit', array(
			'invoice'        => $invoice,
			'signature_user' => $this->_plugin->getSettingValue( 'signature_user' ),
			'plugin'         => $this->_plugin
		) );
	}

	public function displayClientMetaboxAction($post, $args)
	{
	    $invoice = $args['args']['invoice'];

		wp_nonce_field( 'flexible_invoices_nonce', 'flexible_invoices_nonce' );

	    echo $this->_plugin->loadTemplate('client_metabox', 'invoice_edit', array(
	            'invoice' => $invoice,
	            'plugin' => $this->_plugin
	    ));
	}

	public function displayProductsMetaboxAction($post, $args)
	{
	    $invoice = $args['args']['invoice'];

	    echo $this->_plugin->loadTemplate('products_metabox', 'invoice_edit', array(
	            'invoice' => $invoice,
	            'plugin' => $this->_plugin
	    ));
	}

	public function displayPaymentMetaboxAction($post, $args)
	{
	    $invoice = $args['args']['invoice'];

	    echo $this->_plugin->loadTemplate('payment_metabox', 'invoice_edit', array(
	            'invoice' => $invoice,
	            'plugin' => $this->_plugin
	    ));
	}



	/**
     * @param string $number_reset_type
     * @param int $timestamp
	 * @return int
	 */
	public function generateNextInvoiceNumber( $number_reset_type, $timestamp, $mark_for_save = true )	{
	    $previous_timestamp = intval( $this->_plugin->getSettingValue('order_start_invoice_number_timestamp', $timestamp ) );
	    $reset_number = false;
	    if ( $number_reset_type == 'month' ) {
	        if ( date( 'm.Y', $previous_timestamp ) != date( 'm.Y', $timestamp ) ) {
	            $reset_number = true;
            }
        }
		if ( $number_reset_type == 'year' ) {
			if ( date( 'Y', $previous_timestamp ) != date( 'Y', $timestamp ) ) {
				$reset_number = true;
			}
		}
        if ( $reset_number ) {
	        $invoice_number = 1;
        }
        else {
	        $invoice_number = intval( $this->_plugin->getSettingValue( 'order_start_invoice_number', 1 ) );
        }
        if ( $mark_for_save ) {
	        $this->_increase_number_on_save = true;
        }
	    return $invoice_number;
	}


	/**
	 * @param $number_reset_type
	 * @param $timestamp
	 *
	 * @return int
	 */
	public function increaseNextInvoiceNumber( $number_reset_type, $timestamp ) {
		$invoice_number = $this->generateNextInvoiceNumber( $number_reset_type, $timestamp, false );
		if ( $this->_increase_number_on_save ) {
			$invoice_number ++;
			$this->_plugin->setSettingValue( 'order_start_invoice_number', $invoice_number );
			$this->_plugin->setSettingValue( 'order_start_invoice_number_timestamp', $timestamp );
			$this->_increase_number_on_save = false;
		}
		return $invoice_number;
    }


	/**
	 * @param $number_reset_type
	 * @param $timestamp
     *
	 * @return int
	 */
	public function generateNextCorrectionNumber( $number_reset_type, $timestamp, $mark_to_save = true )	{
		$previous_timestamp = intval( $this->_plugin->getSettingValue('correction_start_invoice_number_timestamp', $timestamp ) );
		$reset_number = false;
		if ( $number_reset_type == 'month' ) {
			if ( date( 'm.Y', $previous_timestamp ) != date( 'm.Y', $timestamp ) ) {
				$reset_number = true;
			}
		}
		if ( $number_reset_type == 'year' ) {
			if ( date( 'Y', $previous_timestamp ) != date( 'Y', $timestamp ) ) {
				$reset_number = true;
			}
		}
		if ( $reset_number ) {
			$invoice_number = 1;
		}
		else {
			$invoice_number = intval( $this->_plugin->getSettingValue( 'correction_start_invoice_number', 1 ) );
		}
		if ( $mark_to_save ) {
			$this->_increase_correction_number_on_save = true;
		}
		return $invoice_number;
	}

	/**
	 * @param $number_reset_type
	 * @param $timestamp
	 *
	 * @return int
	 */
	public function increaseNextCorrectionNumber( $number_reset_type, $timestamp ) {
		$invoice_number = $this->generateNextCorrectionNumber( $number_reset_type, $timestamp, false );
		if ( $this->_increase_correction_number_on_save ) {
			$invoice_number ++;
			$this->_plugin->setSettingValue( 'correction_start_invoice_number', $invoice_number );
			$this->_plugin->setSettingValue( 'correction_start_invoice_number_timestamp', $timestamp );
			$this->_increase_correction_number_on_save = false;
		}
		return $invoice_number;
	}

	/**
	 *
	 * @param WC_Order $order
	 * @return InvoicePost
	 */
	public function fetchInvoiceForOrder(WC_Order $order)
	{
	    $invoice = InvoicePost::createFromOrder($order, $this);

	    return $invoice;
	}

	public function head_scripts() {
		// wp_register_style( 'reset-css', plugins_url( 'assets/css/reset.css', __FILE__ ) );
		// wp_register_style( 'print-css', plugins_url( 'assets/css/print.css', __FILE__ ) );
		// wp_register_style( 'front-css', plugins_url( 'assets/css/front.css', __FILE__ ) );
		//
		// wp_enqueue_style( 'reset-css' );
		// wp_enqueue_style( 'print-css' );
		// wp_enqueue_style( 'front-css' );
		?>
		<link href="<?php echo $this->_plugin->getPluginUrl(); ?>/assets/css/reset.css" rel="stylesheet" type="text/css" media="screen,print" />
		<link href="<?php echo $this->_plugin->getPluginUrl(); ?>/assets/css/print.css" rel="stylesheet" type="text/css" media="print" />
		<link href="<?php echo $this->_plugin->getPluginUrl(); ?>/assets/css/front.css" rel="stylesheet" type="text/css" media="screen,print" />
		<?php
	}



	public function getMetaboxesArray()
	{
		return $this->_metaboxes;
	}

	public function getPostTypeSlug()
	{
		return $this->_postType;
	}

	public function initPostTypeAction()
	{
		register_post_type( $this->getPostTypeSlug(), $this->getPostTypeArray() );
	}

	/**
	 * Remove the default Custom Fields meta box
	 */
	public function removeDefaultCustomFields( $type, $context, $post ) {
		foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
			remove_meta_box( 'postcustom', $this->getPostTypeSlug(), $context );
			/*foreach ( $this->postTypes as $postType ) {
			 remove_meta_box( 'postcustom', $postType, $context );
			}*/
		}
	}

}
