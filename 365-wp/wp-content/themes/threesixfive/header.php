<?php
/**
 * Header: logo, primary navigation, header CTA.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$info = threesixfive_company_info();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#primary">Skip to content</a>

<header class="site-header">
	<div class="container site-header__inner">
		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
				<?php bloginfo( 'name' ); ?>
			<?php endif; ?>
		</a>

		<button class="nav-toggle" aria-expanded="false" aria-controls="primary-nav" aria-label="Toggle menu">
			<span></span><span></span><span></span>
		</button>

		<nav id="primary-nav" class="primary-nav" aria-label="Primary">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'fallback_cb'    => 'threesixfive_fallback_menu',
			) );
			?>
		</nav>

		<div class="header-cta">
			<a class="phone" href="tel:<?php echo esc_attr( $info['phone_href'] ); ?>"><?php echo esc_html( $info['phone'] ); ?></a>
			<a class="btn" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Us</a>
		</div>
	</div>
</header>

<main id="primary">
