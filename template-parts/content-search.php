<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Courtyard
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'pt-post-wrap' ); ?>>
	<?php
	$custom_image = get_template_directory_uri() . '/inc/admin/images/7.jpg';
	$image_id               = get_post_thumbnail_id();
	$image_path             = wp_get_attachment_image_src( $image_id, 'courtyard-800x500', true );
	$image_alt              = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	$alt 					= !empty( $image_alt ) ? $image_alt : the_title_attribute( 'echo=0' ) ;
	?>

	<?php if (has_post_thumbnail() || '' != $custom_image ) : ?>
		<figure>
			<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
				<?php if ( !has_post_thumbnail() ) : ?>
					<img src="<?php echo esc_url( $custom_image ); ?>" alt="<?php echo esc_attr( $alt ); ?>" title="<?php the_title_attribute(); ?>" />
				<?php else : ?>
					<img src="<?php echo esc_url( $image_path[0] ); ?>" alt="<?php echo esc_attr( $alt ); ?>" title="<?php the_title_attribute(); ?>" />
				<?php endif; ?>
			</a>
		</figure>
	<?php endif; ?>

	<div class="pt-content-wrap<?php if( !has_post_thumbnail() ) { echo ' pt-content-wrap-border'; } ?>">
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>

			<div class="entry-meta">
				<?php courtyard_posted_one(); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	</div>
</article><!-- #post-## -->
