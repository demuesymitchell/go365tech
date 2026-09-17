<?php
/**
 * Footer: contact block, quick links, social, closing tags.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$info = threesixfive_company_info();
?>
</main><!-- #primary -->

<footer class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div class="footer-col">
				<div class="footer-logo">
					<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
						<strong style="color:#fff;font-size:1.2rem;"><?php bloginfo( 'name' ); ?></strong>
					<?php endif; ?>
				</div>
				<p><?php echo esc_html( $info['address1'] ); ?><br><?php echo esc_html( $info['address2'] ); ?></p>
				<p>
					<a href="mailto:<?php echo esc_attr( $info['email'] ); ?>"><?php echo esc_html( $info['email'] ); ?></a><br>
					<a href="tel:<?php echo esc_attr( $info['phone_href'] ); ?>"><?php echo esc_html( $info['phone'] ); ?></a>
				</p>
				<div class="social-row">
					<a href="<?php echo esc_url( $info['facebook'] ); ?>" aria-label="Facebook">f</a>
					<a href="<?php echo esc_url( $info['instagram'] ); ?>" aria-label="Instagram">ig</a>
					<a href="<?php echo esc_url( $info['twitter'] ); ?>" aria-label="Twitter/X">x</a>
					<a href="<?php echo esc_url( $info['linkedin'] ); ?>" aria-label="LinkedIn">in</a>
				</div>
			</div>

			<div class="footer-col">
				<h4>What We Do</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/what-we-do/' ) ); ?>">Overview</a></li>
					<li><a href="<?php echo esc_url( home_url( '/gas-springs/' ) ); ?>">Gas Springs</a></li>
					<li><a href="<?php echo esc_url( home_url( '/hydraulic-design/' ) ); ?>">Hydraulic Design</a></li>
					<li><a href="<?php echo esc_url( home_url( '/pneumatic-design/' ) ); ?>">Pneumatic Design</a></li>
				</ul>
			</div>

			<div class="footer-col">
				<h4>Company</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/who-we-are/' ) ); ?>">Who We Are</a></li>
					<li><a href="<?php echo esc_url( home_url( '/products/' ) ); ?>">Products</a></li>
					<li><a href="<?php echo esc_url( home_url( '/applications/' ) ); ?>">Applications</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Us</a></li>
				</ul>
			</div>
		</div>

		<div class="footer-bottom">
			<span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</span>
			<span>Site by 365 Technologies</span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
