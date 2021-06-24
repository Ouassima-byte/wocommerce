jQuery( document ).ready( function($) {
	$( '#flexible-invoices-woocommerce .inside' ).find( 'a' ).each( function() {
		$( this ).tipTip( {
			'attribute' : 'data-tip',
			'fadeIn' : 50,
			'fadeOut' : 50,
			'delay' : 200
		});
	});
});
