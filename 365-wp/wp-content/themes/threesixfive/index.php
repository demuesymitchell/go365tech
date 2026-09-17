<?php
/**
 * Fallback template (blog index / search / archives).
 */
get_header();
?>
<section class="page-header">
	<div class="container">
		<h1><?php is_home() ? _e( 'Blog', 'threesixfive' ) : the_archive_title(); ?></h1>
	</div>
</section>

<section class="page-content">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'entry-content' ); ?> style="margin-bottom:2.5em;padding-bottom:2.5em;border-bottom:1px solid var(--color-border);">
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p><?php echo esc_html( get_the_date() ); ?></p>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<div class="placeholder-block">
				<span class="eyebrow">Coming soon</span>
				<p>No posts yet — this is where 365 Technologies news and updates will appear.</p>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
