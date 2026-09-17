<?php
/**
 * Default page template — used for What We Do, Gas Springs, Hydraulic
 * Design, Pneumatic Design, Who We Are, and any other standard page.
 * If the page has children, they're rendered as a card grid underneath
 * the content (mirrors the "What We Do" → 3 sub-pages structure).
 */
get_header();

while ( have_posts() ) :
	the_post();
	$parent_title = get_the_title();
	?>
	<section class="page-header">
		<div class="container">
			<div class="breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<?php if ( $post->post_parent ) : ?>
					/ <a href="<?php echo esc_url( get_permalink( $post->post_parent ) ); ?>"><?php echo esc_html( get_the_title( $post->post_parent ) ); ?></a>
				<?php endif; ?>
				/ <?php echo esc_html( $parent_title ); ?>
			</div>
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<section class="page-content">
		<div class="container">
			<div class="entry-content">
				<?php the_content(); ?>
			</div>

			<?php
			$children = get_pages( array(
				'child_of' => get_the_ID(),
				'parent'   => get_the_ID(),
				'sort_column' => 'menu_order',
			) );
			if ( $children ) :
				?>
				<div class="subpage-grid">
					<?php foreach ( $children as $child ) : ?>
						<div class="subpage-card">
							<h3><?php echo esc_html( $child->post_title ); ?></h3>
							<p><?php echo esc_html( wp_trim_words( $child->post_content, 18 ) ); ?></p>
							<a class="card-link" href="<?php echo esc_url( get_permalink( $child->ID ) ); ?>">Learn more →</a>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
