<?php

namespace WPDeskFIVendor;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
$params = isset($params) && \is_array($params) ? $params : [];
/**
 * @var $document Document
 */
$document = isset($params['document']) ? $params['document'] : 0;
$type = isset($params['type']) ? $params['type'] : 0;
$name = isset($params['name']) ? $params['name'] : 0;
$url = isset($params['url']) ? $params['url'] : 0;
?>

<div class="flexible-invoices-document">
	<header class="title"><h2><?php 
echo $name;
?></h2></header>
	<p><a href="<?php 
echo \esc_url($url);
?>" target="_blank"><?php 
echo $document->get_formatted_number();
?></a></p>
</div>
<?php 
