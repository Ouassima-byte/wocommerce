<?php

namespace WPDeskFIVendor;

/**
 * Scoper fix
 */
?>
<p class="submit"><input type="submit" value="<?php 
\esc_attr_e('Save changes', 'flexible-invoices');
?>" class="button button-primary" id="submit" name=""></p>
</form>

<script type="text/javascript">
	(function($) {
		$( '.tips, .help_tip, .woocommerce-help-tip' ).tipTip( {
			'attribute': 'data-tip',
			'fadeIn': 50,
			'fadeOut': 50,
			'delay': 200
		} );
	})(jQuery);
</script>

<?php 
