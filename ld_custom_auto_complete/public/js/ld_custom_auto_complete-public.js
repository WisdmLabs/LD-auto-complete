jQuery(
	function () {
		if (jQuery( '.learndash_timer' ).length) {
			jQuery( '.learndash_timer' ).each(
				function (idx, item) {
					const timer_el    = jQuery( item );
					let timer_seconds = timer_el.data( 'timer-seconds' );
					const button_ref  = timer_el.data( 'button' );

					if (typeof button_ref !== 'undefined' && jQuery( button_ref ).length) {
						const timer_button_el = jQuery( button_ref );

						if (typeof timer_seconds !== 'undefined' && typeof timer_button_el !== 'undefined') {
							timer_seconds = parseInt( timer_seconds );

							const cookie_key  = timer_el.attr( 'data-cookie-key' );
							const cookie_name = typeof cookie_key !== 'undefined'
							? 'learndash_timer_cookie_' + cookie_key
							: 'learndash_timer_cookie';

							const cookie_timer_seconds = jQuery.cookie( cookie_name );

							if (typeof cookie_timer_seconds !== 'undefined') {
								timer_seconds = parseInt( cookie_timer_seconds );
							}

							if (timer_seconds >= 1) {
								const learndash_timer_var = setInterval(
									function () {
										timer_seconds -= 1;

										if (timer_seconds <= 0) {
											clearInterval( learndash_timer_var );
											timer_button_el.hide();
											jQuery.cookie( cookie_name, 0 );

											const bodyClasses         = jQuery( 'body' ).attr( 'class' );
											const postIdMatch         = bodyClasses.match( /postid-(\d+)/ );
											const postId              = postIdMatch ? postIdMatch[1] : null;
											const CourseIdMatch       = bodyClasses.match( /learndash-cpt-sfwd-courses-(\d+)-parent/ );
											const CourseId            = CourseIdMatch ? CourseIdMatch[1] : null;
											const updated_cookie_name = 'learndash_timer_cookie_' + ld_custom_auto_complete_localized_data.user_id + '_' + CourseId + '_' + postId;

											const formData = {
												action: 'mark_complete',
												nonce: ld_custom_auto_complete_localized_data.nonce,
												post_id: postId,
												course_id: CourseId,
												timer: timer_seconds,
											};

											jQuery.cookie( updated_cookie_name, 0, { path: '/' } );

											jQuery.ajax(
												{
													url: ld_custom_auto_complete_localized_data.ajax_url,
													type: 'POST',
													data: formData,
													success: function (response) {
														// No action needed.
													},
													error: function (error) {
														// No action needed.
													}
												}
											);
										}

										// Store the timer state (value) into a cookie. This is done so that if the page reloads,
										// the student can resume the time instead of restarting.
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
