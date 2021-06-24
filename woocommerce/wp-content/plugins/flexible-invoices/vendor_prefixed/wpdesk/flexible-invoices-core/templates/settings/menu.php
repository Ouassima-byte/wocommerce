<?php

namespace WPDeskFIVendor;

/**
 * Scoper fix
 */
?>
<div id="fi-settings-header">
	<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
		<?php 
foreach ($menu_items as $item_key => $item_name) {
    ?>
			<a href="<?php 
    echo $base_url;
    ?>&tab=<?php 
    echo \esc_attr($item_key);
    ?>" class="nav-tab <?php 
    if ($selected === $item_key) {
        ?>nav-tab-active<?php 
    }
    ?>"><?php 
    echo \esc_html($item_name);
    ?></a>
		<?php 
}
?>
	</nav>
</div>
<?php 
