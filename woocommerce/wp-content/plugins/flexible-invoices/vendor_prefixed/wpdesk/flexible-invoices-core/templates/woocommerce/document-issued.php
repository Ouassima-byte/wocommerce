<?php

namespace WPDeskFIVendor;

$params = isset($params) && \is_array($params) ? $params : [];
$document_id = isset($params['document_id']) ? $params['document_id'] : 0;
$document_number = isset($params['document_number']) ? $params['document_number'] : 0;
$order_id = isset($params['order_id']) ? $params['order_id'] : 0;
$order_has_items = isset($params['order_has_items']) ? $params['order_has_items'] : \false;
$can_issued = isset($params['can_issued']) ? $params['can_issued'] : \false;
$can_edited = isset($params['can_edited']) ? $params['can_edited'] : \true;
$button_label = isset($params['button_label']) ? $params['button_label'] : \false;
$type = isset($params['type']) ? $params['type'] : \false;
$url = \wp_nonce_url(\admin_url('admin-ajax.php?action=woocommere-generate-document&order_id=' . $order_id) . '&single_order=1');
$actions = [];
if ($document_id) {
    if ($document_id) {
        $actions[] = ['url' => \wp_nonce_url(\admin_url('admin-ajax.php?action=invoice-get-pdf-invoice&id=' . $document_id)), 'name' => \__('View', 'flexible-invoices'), 'action' => 'button view-invoice', 'hint' => \__('View', 'flexible-invoices')];
        $actions[] = ['url' => \wp_nonce_url(\admin_url('admin-ajax.php?action=invoice-get-pdf-invoice&id=' . $document_id . '&save_file=1')), 'name' => \__('Download', 'flexible-invoices'), 'action' => 'button get-invoice', 'hint' => \__('Download', 'flexible-invoices')];
    } else {
        if ($order_has_items) {
            $actions[] = ['url' => \wp_nonce_url(\admin_url('admin-ajax.php?action=woocommere-generate-document&order_id=' . $order_id . '&single_order=1')), 'name' => \__('Issue', 'flexible-invoices'), 'action' => 'button generate-invoice', 'hint' => \__('Issue', 'flexible-invoices')];
        }
    }
    $edit_url = \admin_url('post.php?post=' . $document_id . '&action=edit');
    if ($can_edited) {
        echo '<p><a href="' . $edit_url . '" class="edit-invoice" data-tip="' . \__('Edit Invoice', 'flexible-invoices') . '">';
        echo $document_number;
        echo '</a></p>';
    } else {
        echo '<p>' . $document_number . '</p>';
    }
    echo '<p>';
    foreach ($actions as $action) {
        echo '<a target="blank" href="' . $action['url'] . '" class="' . $action['action'] . '" data-tip="' . \esc_attr($action['hint']) . '">';
        echo $action['name'];
        echo '</a>';
    }
    echo '</p>';
} else {
    if ($can_issued && $can_edited) {
        echo '<p><a target="blank" href="' . $url . '&type=' . $type . '" class="button generate-invoice document-' . $type . '" data-tip="">';
        echo $button_label;
        echo '</a></p>';
    }
}
