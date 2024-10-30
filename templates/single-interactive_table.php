<?php
/**
 * The template for interactive table post details
 *
 */

get_header(); ?>

<div id="primary" class="content-area it_single_post_details">
	<main id="main" class="site-main">
	
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
		$post_id = get_the_ID();
		?>	
		
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' );	?>
			</header><!-- .entry-header -->		
			
			
			<div class="entry-content">
				<div class="it_photo"><?php echo get_the_post_thumbnail( $post_id, 'full' ); ?></div><!--it_photo-->
				<div class="it_details">
                	<?php the_content(); ?>
                </div><!--it_details-->
			</div><!-- .entry-content -->		
		
		</article><!-- #post-## -->
		<?php
		// End the loop.
		endwhile;
		?>		
	</main><!-- .site-main -->
</div><!--content-area-->



<?php get_footer(); ?>