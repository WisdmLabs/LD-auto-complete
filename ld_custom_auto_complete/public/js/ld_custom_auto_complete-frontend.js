jQuery(
	function () {
		if ( jQuery( '.learndash_timer' ).length ) {
				jQuery( '.learndash_timer' ).each(
					function ( idx, item ) {
						var timer_el = jQuery( item );

						var timer_seconds = timer_el.data( 'timer-seconds' );
						var button_ref    = timer_el.data( 'button' );

						if ( ( typeof button_ref !== 'undefined' ) && ( jQuery( button_ref ).length ) ) {
									var timer_button_el = jQuery( button_ref );
							if ( ( typeof timer_seconds !== 'undefined' ) && ( typeof timer_button_el !== 'undefined' ) ) {
								timer_seconds = parseInt( timer_seconds );

								var cookie_key = timer_el.attr( 'data-cookie-key' );

								if ( typeof cookie_key !== 'undefined' ) {
											var cookie_name = 'learndash_timer_cookie_' + cookie_key;
								} else {
										var cookie_name = 'learndash_timer_cookie';
								}

								var cookie_timer_seconds = jQuery.cookie( cookie_name );

								if ( typeof cookie_timer_seconds !== 'undefined' ) {
										timer_seconds = parseInt( cookie_timer_seconds );
								}

								if ( timer_seconds >= 1 ) {
									var learndash_timer_var = setInterval(
										function () {
											timer_seconds = timer_seconds - 1;
											if ( timer_seconds <= 0 ) {
													clearInterval( learndash_timer_var );
													timer_button_el.hide();
													jQuery.cookie( cookie_name, 0 );
													var bodyClasses   = jQuery( 'body' ).attr( 'class' );
													var postIdMatch   = bodyClasses.match( /postid-(\d+)/ );
													var postId        = postIdMatch ? postIdMatch[1] : null;
													var CourseIdMatch = bodyClasses.match( /learndash-cpt-sfwd-courses-(\d+)-parent/ );
													var CourseId      = CourseIdMatch ? CourseIdMatch[1] : null;
													var cookie_name   = 'learndash_timer_cookie_' + ld_custom_auto_complete_localized_data.user_id + '_' + CourseId + '_' + postId;
													var formData      = {
														action : 'mark_complete',
														nonce : ld_custom_auto_complete_localized_data.nonce,
														post_id : postId,
														course_id : CourseId,
														timer : timer_seconds,
												}
													jQuery.cookie( cookie_name, 0, { path: '/' } );
													jQuery.ajax(
														{
															url: ld_custom_auto_complete_localized_data.ajax_url,
															type: 'POST',
															data: formData,
															success: function ( response ) {
																// No action needed.
															},
															error: function ( error ) {
																// No action needed.
															}
														}
													);
											}
											// Store the timer state (value) into a cookie. This is done if the page reloads the student can resume
											// the time instead of restarting.
											jQuery.cookie( cookie_name, timer_seconds );
										},
										1000
									);
								} else {
									jQuery.cookie( cookie_name, 0 );
								}
							}
						}
					}
				);
		}
	}
);