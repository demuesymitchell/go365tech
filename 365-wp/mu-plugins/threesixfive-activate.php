<?php
/**
 * Auto-activates the 365 Technologies theme once WordPress finishes its
 * initial install, so there's no manual "Activate" click needed after a
 * fresh Railway deploy. Runs on wp-admin load only (never mid front-end
 * request), and is a no-op once the theme is already active.
 */
add_action( 'admin_init', function () {
	if ( wp_installing() ) {
		return;
	}
	if ( get_option( 'stylesheet' ) !== 'threesixfive' && wp_get_theme( 'threesixfive' )->exists() ) {
		switch_theme( 'threesixfive' );
	}
} );
