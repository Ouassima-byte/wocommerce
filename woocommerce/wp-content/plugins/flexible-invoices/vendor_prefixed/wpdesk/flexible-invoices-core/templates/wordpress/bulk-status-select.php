<?php

namespace WPDeskFIVendor;

$selected = $params['selected'];
echo '<select name="user" id="inspire_invoice_client_select">';
if (isset($selected['id'])) {
    echo '<option value="' . $selected['id'] . '">' . $selected['text'] . '</option>';
}
echo '</select>';
echo '<select name="paystatus">';
$statuses = $params['statuses'];
$statuses['exceeded'] = \__('Overdue', 'flexible-invoices');
echo '<option value="">' . \__('All statuses', 'flexible-invoices') . '</option>';
$paystatus = '';
if (isset($_GET['paystatus'])) {
    $paystatus = $_GET['paystatus'];
}
foreach ($statuses as $key => $status) {
    echo '<option value="' . $key . '" ' . ($key == $paystatus ? 'selected="selected"' : '') . '>' . $status . '</option>';
}
echo '</select>';
