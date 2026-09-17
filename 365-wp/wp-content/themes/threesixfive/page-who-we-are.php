<?php
/**
 * Template Name: Who We Are (About)
 * Two alternating image/text sections — "Market Knowledge" and
 * "Engineered Service" — ported from the original site.
 */
get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="page-header">
		<div class="container">
			<div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php the_title(); ?></div>
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<section class="feature-row">
		<div class="container" style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;">
			<div class="feature-row__media" aria-hidden="true">Team / office photo</div>
			<div class="feature-row__body">
				<h2>Market Knowledge</h2>
				<p>All of our field and professional personnel work from a strong background of experience in product design for hydraulic, pneumatic and electric motion-control applications.</p>
			</div>
		</div>
	</section>

	<section class="feature-row">
		<div class="container" style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;">
			<div class="feature-row__body" style="order:2;">
				<h2>Engineered Service</h2>
				<p>365 Technologies' engineered services offerings center on design solutions for niche products across many industries, including agricultural, automotive, industrial, manufacturing, medical and more.</p>
			</div>
			<div class="feature-row__media" aria-hidden="true" style="order:1;">Engineering chart / data</div>
		</div>
	</section>

	<?php if ( get_the_content() ) : ?>
		<section class="page-content">
			<div class="container entry-content">
				<?php the_content(); ?>
			</div>
		</section>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
