<?php
/**
 * 365 Technologies theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'THREESIXFIVE_VERSION', '1.0.0' );

/**
 * Core theme setup.
 */
function threesixfive_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'threesixfive' ),
		'footer'  => __( 'Footer Menu', 'threesixfive' ),
	) );
}
add_action( 'after_setup_theme', 'threesixfive_setup' );

/**
 * Styles & scripts.
 */
function threesixfive_assets() {
	wp_enqueue_style( 'threesixfive-style', get_stylesheet_uri(), array(), THREESIXFIVE_VERSION );
	wp_enqueue_script( 'threesixfive-main', get_template_directory_uri() . '/assets/js/main.js', array(), THREESIXFIVE_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'threesixfive_assets' );

/**
 * Sensible excerpt / widget defaults (kept minimal on purpose — this theme
 * does not ship widget areas yet; add them here when the Blog/News section
 * is built out).
 */
function threesixfive_content_width() {
	$GLOBALS['content_width'] = 820;
}
add_action( 'after_setup_theme', 'threesixfive_content_width' );

/**
 * Pull site contact details from one place so header/footer/Contact page
 * all stay in sync. Edit these to update everywhere at once, or replace
 * with theme-mod / ACF fields later.
 */
function threesixfive_company_info( $key = null ) {
	$info = array(
		'name'     => '365 Technologies',
		'address1' => '8531 S Fwy Dr',
		'address2' => 'Macedonia, OH 44056',
		'phone'    => '(330) 468-3300',
		'phone_href' => '+13304683300',
		'email'    => 'info@go365tech.com',
		'facebook' => 'https://facebook.com/',
		'instagram'=> 'https://instagram.com/',
		'twitter'  => 'https://twitter.com/',
		'linkedin' => 'https://linkedin.com/',
		'map_embed'=> 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2996.272246814872!2d-81.51559257373954!3d41.32469299127964!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88311fd765855a89%3A0x4112c3dc73d1afc!2s8531+S+Fwy+Dr%2C+Macedonia%2C+OH+44056!5e0!3m2!1sen!2sus!4v1485887472207',
	);
	if ( $key ) {
		return isset( $info[ $key ] ) ? $info[ $key ] : '';
	}
	return $info;
}

/**
 * Fallback nav (shown only if no "primary" menu has been assigned yet —
 * e.g. before the 365 Setup tool has been run, or before an admin has
 * built a menu manually in Appearance → Menus).
 */
function threesixfive_fallback_menu() {
	echo '<ul id="primary-menu" class="menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/what-we-do/' ) ) . '">What We Do</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/who-we-are/' ) ) . '">Who We Are</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/contact-us/' ) ) . '">Contact</a></li>';
	echo '</ul>';
}

/**
 * One-click content/menu provisioning (Appearance → 365 Setup).
 */
require get_template_directory() . '/inc/setup.php';
