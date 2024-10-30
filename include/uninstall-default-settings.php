<?php
if ( ! defined( 'ABSPATH' ) ) {
exit;
}
if(get_option('it_clean_on_deactive') === 'yes'){
  update_option('it_block_heading_position', '');
  update_option('it_cell_title_color', '');
  update_option('it_cell_hover_title_color', '');
  update_option('it_cell_font_color', '');
  update_option('it_cell_hover_font_color', '');
  update_option('it_read_more_font_color', '');
  update_option('it_display_mode', '');
  update_option('it_highlight_mode', '');
  update_option('cell_highlight_color', '');
  update_option('it_single_cell_slug_url', '');
  update_option('it_cell_hover', '');
  update_option('it_title_hover', '');
  update_option('it_read_more', '');
  update_option('readmore_label', '');
  update_option('it_load_default_css', '');
  update_option('it_vertical_posts_per_row', '');
  update_option('fixed_highlight', '');
  it_clear_all_generated_post_data();
  update_option('it_clean_on_deactive', '');
}

function it_clear_all_generated_post_data(){
	$args = array(
		'post_type'  => 'interactive_table',
		'post_status' => 'any',
		'orderby' => 'post_date',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	$it_query = new WP_Query( $args );
	if ( $it_query->have_posts() ) {
		while ( $it_query->have_posts() ) {
			$it_query->the_post();
			$it_post_id = get_the_ID();
      wp_delete_post( $it_post_id, true );
		}
	}
	wp_reset_postdata();

  $categories = get_terms( 'it_cell_category', array('hide_empty' => 0) );
  if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
    foreach ( $categories as $category ) {
        wp_delete_term( $category->term_id, 'it_cell_category');
    }
  }

}
