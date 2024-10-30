( function( $ ) {
	$( document ).ready(
		function() {
			// Hide contingent options if not needed
			refresh_contingent_input_visibility();
			// Change visibility as needed.
			$( ".prerequisite" ).on(
				"change",
				function() {
					refresh_contingent_input_visibility();
				}
			);

			$( "#type_id" ).on(
				"blur",
				function() {
					check_type_id_is_unique();
				}
			);

			if ( $( "#bp_type_show_in_list" ).prop( 'checked' ) == true) {
				$( '.term-bp_group_type_create_screen_checked-wrap' ).show();
			} else {
				  $( '.term-bp_group_type_create_screen_checked-wrap' ).hide();
			}
			$( "#bp_type_show_in_create_screen" ).on(
				"change",
				function() {
					console.log( $( this ).prop( 'checked' ) );
					if ( $( this ).prop( 'checked' ) == true) {
						 $( '.term-bp_group_type_create_screen_checked-wrap' ).show();
					} else {
							 $( '.term-bp_group_type_create_screen_checked-wrap' ).hide();
					}
				}
			);

		}
	);

	/**
	 * Show or hide extra questions that are contingent on other responses.
	 */
	function refresh_contingent_input_visibility() {
		$( ".prerequisite" ).each(
			function() {
				if ( $( this ).is( ':checked' ) ) {
					  $( this ).parent().find( ".contingent" ).show();
					  $( this ).parent().find( ".contingent" ).next().show();
				} else {
					$( this ).parent().find( ".contingent" ).hide();
					$( this ).parent().find( ".contingent" ).next().hide();
				}
			}
		);
	}

	/**
	 * Give user feedback about overlapping types.
	 */
	function check_type_id_is_unique() {
		$.ajax(
			{
				type: 'POST',
				url: ajaxurl,
				data: {
					'action': 'check-bp-type-id',
					'nonce': bpgt_admin_nonce.ajax_nonce,
					'pagenow': pagenow,
					'type': $( "#type_id" ).val(),
					'singular_name': $( "#singular_name" ).val(),
				},
				success: function (response) {
					console.log( response );
				},
				error: function (response) {
					console.log( response );
				}
			}
		);
	}
} )( jQuery )
