<?php
/**
 * Template Name: What We Do (Service List)
 * Alternating image/text rows, one per child page (Gas Springs,
 * Hydraulic Design, Pneumatic Design) — mirrors the original site's
 * "What We Do" layout. Children are pulled by slug so the row order and
 * copy stay in sync with whatever those pages actually say.
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

	<?php
	$children = get_pages( array(
		'child_of'    => get_the_ID(),
		'parent'      => get_the_ID(),
		'sort_column' => 'menu_order',
	) );

	if ( $children ) :
		$i = 0;
		foreach ( $children as $child ) :
			$reverse = ( 0 === $i % 2 ) ? '' : 'style="order:2;"';
			$media_reverse = ( 0 === $i % 2 ) ? 'style="order:1;"' : '';
			$thumb = get_the_post_thumbnail_url( $child->ID, 'large' );
			?>
			<section class="feature-row">
				<div class="container" style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;">
					<div class="feature-row__media" <?php echo $media_reverse; ?> aria-hidden="true">
						<?php if ( $thumb ) : ?>
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $child->post_title ); ?>">
						<?php else : ?>
							<?php echo esc_html( $child->post_title ); ?> photo
						<?php endif; ?>
					</div>
					<div class="feature-row__body" <?php echo $reverse; ?>>
						<h2><?php echo esc_html( $child->post_title ); ?></h2>
						<a class="card-link" href="<?php echo esc_url( get_permalink( $child->ID ) ); ?>">additional information →</a>
					</div>
				</div>
			</section>
			<?php
			$i++;
		endforeach;
	else :
		?>
		<section class="page-content">
			<div class="container">
				<div class="placeholder-block">
					<span class="eyebrow">No service pages yet</span>
					<p>Add Gas Springs, Hydraulic Design, and Pneumatic Design as child pages of What We Do (or run Appearance → 365 Setup) to populate this list.</p>
				</div>
			</div>
		</section>
		<?php
	endif;
endwhile;

get_footer();
