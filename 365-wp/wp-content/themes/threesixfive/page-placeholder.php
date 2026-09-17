<?php
/**
 * Template Name: Placeholder Section
 * Used for Products / Applications / Downloads — real navigational
 * slots that exist now so the menu/IA is future-proof, but with no
 * fleshed-out content yet.
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

	<section class="page-content">
		<div class="container">
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			<div class="placeholder-block">
				<span class="eyebrow">Coming soon</span>
				<p>This section is scaffolded in the navigation and site structure so it's ready to build out as 365 Technologies adds product categories, application/industry pages, or downloadable resources — no theme changes required, just new content.</p>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
