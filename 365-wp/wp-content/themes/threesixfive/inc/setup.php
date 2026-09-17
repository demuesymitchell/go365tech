<?php
/**
 * Appearance → 365 Setup
 *
 * One-click tool that provisions every page this theme expects (ported
 * content from the live go365tech.com pages, plus placeholder scaffolding
 * for Products / Applications / Downloads / Blog), then builds the
 * primary nav menu and sets front-page/posts-page options.
 *
 * Safe to run more than once — it looks pages up by slug and skips ones
 * that already exist, so re-running just fills in anything missing.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function threesixfive_seed_pages() {
	// Ordered so parents are created before children reference them.
	return array(
		array(
			'slug'    => 'home',
			'title'   => 'Home',
			'content' => '',
		),
		array(
			'slug'     => 'what-we-do',
			'title'    => 'What We Do',
			'template' => 'page-what-we-do.php',
			'content'  => '',
		),
		array(
			'slug'    => 'gas-springs',
			'title'   => 'Gas Springs',
			'parent'  => 'what-we-do',
			'content' => 'Sometimes referred to as "gas shocks," gas springs are a core part of the 365 Technologies proficiency. We\'re gas spring designers capable of producing the perfect custom component or product solution based on specifications and creative gas spring design.',
		),
		array(
			'slug'    => 'hydraulic-design',
			'title'   => 'Hydraulic Design',
			'parent'  => 'what-we-do',
			'content' => "365 Technologies offers a full capability suite of hydraulic design services. We build and fabricate hydraulics, offer educational instruction and continuing hydraulic education certifications.\n\nWe also offer on-site troubleshooting and service for hydraulic systems, including those already deemed in need of repair. We service hydraulic motors, hydraulic pumps, pistons, gears, vanes, modular pumps and hi-lo 2 stage gear pumps.",
		),
		array(
			'slug'    => 'pneumatic-design',
			'title'   => 'Pneumatic Design',
			'parent'  => 'what-we-do',
			'content' => "365 Technologies provides pneumatic system design support starting at the connection to a machine's air preparation hardware and continues to correctly pairing valves with cylinders to ensure safe machine operation.\n\nWe also offer on-site troubleshooting and service for pneumatic systems, including those already deemed in need of repair. We service pneumatic systems in a myriad of focus markets, including agriculture, automotive (specialty vehicles and commercial vehicles), aerial and lift trucks, patient handling and specialty equipment in the medical sector and industrial.",
		),
		array(
			'slug'     => 'who-we-are',
			'title'    => 'Who We Are',
			'template' => 'page-who-we-are.php',
			'content'  => '',
		),
		array(
			'slug'     => 'contact-us',
			'title'    => 'Contact Us',
			'parent'   => 'who-we-are',
			'template' => 'page-contact.php',
			'content'  => '',
		),
		array(
			'slug'     => 'products',
			'title'    => 'Products',
			'template' => 'page-placeholder.php',
			'content'  => 'A full product catalog — categories, subcategories, and individual series, similar in structure to what you will find on comparable distributor sites — will live here.',
		),
		array(
			'slug'     => 'applications',
			'title'    => 'Applications',
			'template' => 'page-placeholder.php',
			'content'  => "Industries and use cases 365 Technologies serves — automotive, agricultural, medical, and industrial — will be detailed here.",
		),
		array(
			'slug'     => 'downloads',
			'title'    => 'Downloads',
			'template' => 'page-placeholder.php',
			'content'  => 'Catalogs, spec sheets, and other downloadable resources will be posted here.',
		),
		array(
			'slug'    => 'blog',
			'title'   => 'Blog',
			'content' => '',
		),
	);
}

function threesixfive_run_setup() {
	$log     = array();
	$id_map  = array();
	$seed    = threesixfive_seed_pages();

	foreach ( $seed as $def ) {
		$existing = get_page_by_path( $def['slug'] );

		if ( $existing ) {
			$id_map[ $def['slug'] ] = $existing->ID;
			$log[] = "Skipped (already exists): {$def['title']}";
			continue;
		}

		$parent_id = 0;
		if ( ! empty( $def['parent'] ) && isset( $id_map[ $def['parent'] ] ) ) {
			$parent_id = $id_map[ $def['parent'] ];
		}

		$page_id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_title'   => $def['title'],
			'post_name'    => $def['slug'],
			'post_content' => $def['content'],
			'post_status'  => 'publish',
			'post_parent'  => $parent_id,
		) );

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			$log[] = "FAILED: {$def['title']}";
			continue;
		}

		if ( ! empty( $def['template'] ) ) {
			update_post_meta( $page_id, '_wp_page_template', $def['template'] );
		}

		$id_map[ $def['slug'] ] = $page_id;
		$log[] = "Created: {$def['title']}";
	}

	// Front page / posts page.
	if ( isset( $id_map['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $id_map['home'] );
	}
	if ( isset( $id_map['blog'] ) ) {
		update_option( 'page_for_posts', $id_map['blog'] );
	}

	threesixfive_build_menu( $id_map, $log );

	return $log;
}

function threesixfive_build_menu( $id_map, &$log ) {
	$menu_name = 'Primary Menu';
	$menu_obj  = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu_obj ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = $menu_obj->term_id;
		// Clear existing items so re-running doesn't duplicate.
		$existing_items = wp_get_nav_menu_items( $menu_id );
		if ( $existing_items ) {
			foreach ( $existing_items as $item ) {
				wp_delete_post( $item->ID, true );
			}
		}
	}

	$add = function ( $title, $slug_or_url, $parent_menu_item_id = 0 ) use ( $menu_id, $id_map ) {
		if ( isset( $id_map[ $slug_or_url ] ) ) {
			$url = get_permalink( $id_map[ $slug_or_url ] );
		} else {
			$url = home_url( $slug_or_url );
		}
		return wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => $title,
			'menu-item-url'       => $url,
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent_menu_item_id,
		) );
	};

	$home_id       = $add( 'Home', '/' );
	$what_we_do_id = $add( 'What We Do', 'what-we-do' );
	if ( isset( $id_map['gas-springs'] ) ) {
		$add( 'Gas Springs', 'gas-springs', $what_we_do_id );
	}
	if ( isset( $id_map['hydraulic-design'] ) ) {
		$add( 'Hydraulic Design', 'hydraulic-design', $what_we_do_id );
	}
	if ( isset( $id_map['pneumatic-design'] ) ) {
		$add( 'Pneumatic Design', 'pneumatic-design', $what_we_do_id );
	}

	if ( isset( $id_map['products'] ) ) {
		$add( 'Products', 'products' );
	}
	if ( isset( $id_map['applications'] ) ) {
		$add( 'Applications', 'applications' );
	}

	$who_we_are_id = $add( 'Who We Are', 'who-we-are' );
	if ( isset( $id_map['contact-us'] ) ) {
		$add( 'Contact Us', 'contact-us', $who_we_are_id );
	}

	if ( isset( $id_map['downloads'] ) ) {
		$add( 'Downloads', 'downloads' );
	}
	if ( isset( $id_map['blog'] ) ) {
		$add( 'Blog', 'blog' );
	}
	if ( isset( $id_map['contact-us'] ) ) {
		$add( 'Contact', 'contact-us' );
	}

	$locations = get_theme_mod( 'nav_menu_locations' );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	$log[] = 'Primary menu built and assigned to header.';
}

function threesixfive_admin_menu() {
	add_theme_page(
		'365 Setup',
		'365 Setup',
		'manage_options',
		'threesixfive-setup',
		'threesixfive_admin_page'
	);
}
add_action( 'admin_menu', 'threesixfive_admin_menu' );

function threesixfive_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$log = array();

	if ( isset( $_POST['threesixfive_setup_nonce'] ) && wp_verify_nonce( $_POST['threesixfive_setup_nonce'], 'threesixfive_run_setup' ) ) {
		$log = threesixfive_run_setup();
	}
	?>
	<div class="wrap">
		<h1>365 Technologies — Site Setup</h1>
		<p>Creates every page this theme expects (About, What We Do + its three sub-pages, Who We Are, Contact Us, and Products / Applications / Downloads / Blog placeholders), then builds and assigns the primary navigation menu. Safe to run more than once — existing pages are left alone.</p>

		<form method="post">
			<?php wp_nonce_field( 'threesixfive_run_setup', 'threesixfive_setup_nonce' ); ?>
			<?php submit_button( 'Create Pages &amp; Menu' ); ?>
		</form>

		<?php if ( $log ) : ?>
			<h2>Result</h2>
			<ul style="background:#fff;border:1px solid #ccd0d4;padding:16px 24px;max-width:520px;">
				<?php foreach ( $log as $line ) : ?>
					<li><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul>
			<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">View the site →</a></p>
		<?php endif; ?>
	</div>
	<?php
}
