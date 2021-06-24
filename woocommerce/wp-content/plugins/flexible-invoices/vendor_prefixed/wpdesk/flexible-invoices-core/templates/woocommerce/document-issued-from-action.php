<?php

namespace WPDeskFIVendor;

$params = isset($params) && \is_array($params) ? $params : [];
$actions = $params['actions'];
foreach ($actions as $action) {
    echo '<a target="blank" href="' . $action['url'] . '" class="button wc-action-button ' . $action['action'] . '" aria-label="' . \esc_attr($action['name']) . '">';
    echo $action['name'];
    echo '</a> ';
}
