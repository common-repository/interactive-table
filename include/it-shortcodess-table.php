<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
function it_dynamic_table_shortcode( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'block_heading_position' => get_option('it_block_heading_position'),
        'block_associates_color' => get_option('cell_highlight_color'),
        'cell_title_color' => get_option('it_cell_title_color'),
        'cell_hover_title_color' => get_option('it_cell_hover_title_color'),
        'cell_font_color' => get_option('it_cell_font_color'),
        'cell_hover_font_color' => get_option('it_cell_hover_font_color'),
        'read_more_font_color' => get_option('it_read_more_font_color'),
        'cell_read_more' => get_option('it_read_more'),
        'vertical_posts_per_row' => get_option('it_vertical_posts_per_row'),
		    'fixed_highlight' => get_option('fixed_highlight'),
        'category' => null
    ), $atts));

	$table_output = '';

  $category_arr = explode(",", $category);
	$display_mode = get_option('it_display_mode');

	//print_r($group_arr);

	if( ($category !== null) && (sizeof($category_arr) > 0) ) {
		$args = array(
			'post_type' => 'interactive_table',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'it_cell_category',
					'field'    => 'id',
					'terms'    => $category_arr,
				),
			),
			'orderby'   => 'menu_order',
			'order'     => 'ASC',
		);
	} else {
		$args = array(
			'post_type' => 'interactive_table',
			'posts_per_page' => -1,
			'orderby'   => 'menu_order',
			'order'     => 'ASC',
		);
	}

	$it_query = new WP_Query( $args );

	if($it_query->have_posts()) {
		$table_output .= '<style type="text/css">
				.column_list .cell .cell-title a { color: '.$cell_title_color.'; }
				.column_list { color: '.$cell_font_color.'; }
				.column_list .cell.highlight .cell-title a { color: '.$cell_hover_title_color.'; }
				.column_list .highlight { color: '.$cell_hover_font_color.'; }
				.column_list .cell .v-col-content > a, .column_list .cell .horizontal_td_wrap > a, .column_list .v-filtered-content > a { color: '.$read_more_font_color.'; }
				.column_list .highlight, .column_list .cell-heading.highlight { background-color: '.$block_associates_color.'; }
			</style>';

		$table_output .= '<div class="dynamic_table_wrap display_heading_'.$block_heading_position.'">';

		$terms = get_terms( 'it_cell_category', array(
			'include'    => $category_arr,
		 ) );

		$count = 0;
		$heading_pos = $block_heading_position;
		if( $heading_pos == 'Top' ) {
			$class = 'horizontal pos-top';
			$col_class = 'horizontal';
		} elseif( $heading_pos == 'Left' ) {
			$class = 'vertical pos-left';
			$col_class = 'vertical';
		} elseif( $heading_pos == 'Right' ) {
			$class = 'vertical pos-right';
			$col_class = 'vertical';
		} elseif( $heading_pos == 'Bottom' ) {
			$class = 'horizontal pos-bottom';
			$col_class = 'horizontal';
		} else {
			$class = '';
			$col_class = '';
		}

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$termOrderArr = array();
			$empOrderArr = array();
			$term_colors = array();
			foreach ( $terms as $term ) {
				// Get the custom fields based on the $presenter term ID
				$cell_cat_custom_fields = get_option( "taxonomy_term_$term->term_id" );
				//$table_output .= $cell_cat_custom_fields['cell_cat_order'];
				if( $cell_cat_custom_fields['cell_cat_order'] != '' ) {
					$termOrderArr[$term->term_id] = $cell_cat_custom_fields['cell_cat_order'];
				} else {
					$empOrderArr[$term->term_id] = '';
					//continue;
				}

				$t_id = $term->term_id;
				$term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
				if (isset($term_meta['cell_cat_color']) && ($term_meta['cell_cat_color'] != '')) {
					$term_color = $term_meta['cell_cat_color'];
				} else {
					$term_color = $block_associates_color;
				}
				$term_colors[$term->slug] = $term_color;

			}
			asort($termOrderArr);
			// merge the two arrays
			$termsArr = $termOrderArr + $empOrderArr;
			$serializedTermIds = array_keys($termsArr);

		}

		//Enable css for fixed heighlight mode
		if(($fixed_highlight == 'enable') && isset($term_colors)){
			$table_output .= '<style type="text/css">';
			foreach ( $term_colors as $term_color_slug => $term_color_code ) {
				$table_output .= '.column_list .'.$term_color_slug.'{ background:'.$term_color_code.' !important;}';
			}
			$table_output .= '.column_list .cell .cell-title, .column_list .cell .cell-title a{ color:'.$cell_hover_title_color.';}';
			$table_output .= '.column_list .cell{ color:'.$cell_hover_font_color.';}';
			$table_output .= '</style>';
		}

		if(isset($term_colors)){
			$table_output .= '<style type="text/css">';
			foreach ( $term_colors as $term_color_slug => $term_color_code ) {
				$table_output .= '.column_list .cell-heading.'.$term_color_slug.'.highlight{ background-color:'.$term_color_code.';}';
			}
			$table_output .= '</style>';
		}

		$extra_count = 0;
		if(	$display_mode == 'Filter' ) {
			$extra_count = 1;
		}

		$total_posts = $it_query->post_count;
		$extra_cols = $total_posts%(count($terms)+$extra_count); // +1 for All taxonomy filter
		$posts_per_row = ceil($total_posts/(count($terms)+$extra_count)); // +1 for All taxonomy filter


		$vertical_posts_in_row = 3;
		if( $vertical_posts_per_row ) {
			$vertical_posts_in_row = $vertical_posts_per_row;
		}

		$table_output .= '<div class="column_list '.$col_class.'" data-terms="'.count($terms).'" data-vcols="'.$vertical_posts_in_row.'">';
		$post_count = 0;
		$i = 0;
		$term_count = 1;
		$hasExtra = false;
		$skipped_post_id = '';
		$td_count = 0;
		$horzRow = 1;

		$total_rows = ceil($total_posts/(count($terms)+$extra_count)); // +1 for All taxonomy filter
		$extra_tds = count($terms) - $extra_cols;

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) && (($heading_pos == 'Top')) ) {
			$table_output .= '<div class="heading '.$class.' terms-'.count($terms).'" data-terms="'.count($terms).'">';
			if(	$display_mode == 'Filter' ) {
				$table_output .= '<div data-tax="all" data-taxposts="'.$total_posts.'" class="cell-heading"><div class="cellwrap"><span>' . __('All', 'IT') . '</span></div></div>';
			}
			foreach ( $termsArr as $termId => $termOrder ) {
				$theTerm = get_term_by('id', $termId, 'it_cell_category');
				$table_output .= '<div data-tax="'.$theTerm->slug.'" data-taxposts="'.$theTerm->count.'" class="cell-heading '.$theTerm->slug.'"><div class="cellwrap"><span>' . $theTerm->name . '</span>'.term_description($termId, 'it_cell_category').'</div></div>';
				$count++;
			}
			$table_output .= '</div>';
		}

		if( ! empty( $terms ) && ! is_wp_error( $terms ) && (($heading_pos == 'Left') || ($heading_pos == 'Right')) ) {
			$table_output .= '<div class="heading_col">';
			if(	$display_mode == 'Filter' ) {
				$table_output .= '<div data-tax="all" data-taxposts="'.$total_posts.'" class="cell-heading"><div class="cellwrap"><span>' . __('All', 'IT') . '</span></div></div>';
			}
			foreach ( $termsArr as $termId => $termOrder ) {
				$theTerm = get_term_by('id', $termId, 'it_cell_category');
				$table_output .= '<div data-tax="'.$theTerm->slug.'" data-taxposts="'.$theTerm->count.'" class="cell-heading '.$theTerm->slug.'"><div class="cellwrap"><span>' . $theTerm->name . '</span>'.term_description($termId, 'it_cell_category').'</div></div>';
				$count++;
			}
			$table_output .= '</div>';
		}

		global $post;

		if( ($col_class == 'vertical') ) {
			$table_output .= '<div class="vertical-posts-container">';
		}
		while ( $it_query->have_posts() ) {
			$it_query->the_post();

			$read_more = $cell_read_more;

			if( ($col_class == 'vertical') ) {
				$post_terms = array();
				$term_list = wp_get_post_terms($post->ID, 'it_cell_category', array("fields" => "all"));
				foreach($term_list as $term_single) {
					$post_terms[] = $term_single->slug;
				}
				$allTerms = join(",", $post_terms);
				$allTermsClass = join(" ", $post_terms);


				if($read_more == 'No') {
					$content = get_the_content();
					$content = apply_filters('the_content', $content);
					$title_link = '';
					$title_link_close = '';
				} else {
					$content = get_post_meta($post->ID, '_it_short_description', true);
					$content = apply_filters('the_content', $content);
					if(get_post_meta($post->ID, '_it_url', true))
						$read_more_url = esc_url(get_post_meta($post->ID, '_it_url', true));
					else
						$read_more_url = get_permalink($post->ID);
					$content = $content.'<br /><a class="cell_read_more" href="'.$read_more_url.'">'.get_option('readmore_label').'</a>';
					$title_link = '<a href="'.$read_more_url.'">';
					$title_link_close = '</a>';
				}

				$cell_edit_link = '';
				$post_edit_url = get_edit_post_link( $post->ID );
				if($post_edit_url){
					$cell_edit_link = '<a class="edit_cell" href="'.$post_edit_url.'">Edit Cell</a>';
				}

				if( $post_count == 0 ) {
					$table_output .= '<div class="it_row" data-row="'.$horzRow.'">';
				}
				$it_cell_class = get_post_meta($post->ID, '_it_cell_class', true);
				$table_output .= '<div data-posttax="'.$allTerms.'" class="cell '.$allTermsClass.' '.$it_cell_class.'"><div class="v-col-content cellwrap"><span class="cell-title">' .$title_link. get_the_title() .$title_link_close. '</span>'.$content. $cell_edit_link .'</div></div>';
				$post_count++;

				if( $post_count == $vertical_posts_in_row ) {
					$table_output .= '</div>';
					$post_count = 0;
					$horzRow++;
				}

			} elseif( ($col_class == 'horizontal') ) {
				if( $post_count == 0 ) {
					$table_output .= '<div class="it_row">';
				}

				$post_terms = array();
				$term_list = wp_get_post_terms($post->ID, 'it_cell_category', array("fields" => "all"));
				foreach($term_list as $term_single) {
					$post_terms[] = $term_single->slug;
				}
				$allTerms = join(",", $post_terms);
				$allTermsClass = join(" ", $post_terms);

				if($read_more == 'No') {
					$content = get_the_content();
					$content = apply_filters('the_content', $content);
					$title_link = '';
					$title_link_close = '';
				} else {
					$content = get_post_meta($post->ID, '_it_short_description', true);
					$content = apply_filters('the_content', $content);
					if(get_post_meta($post->ID, '_it_url', true))
						$read_more_url = esc_url(get_post_meta($post->ID, '_it_url', true));
					else
						$read_more_url = get_permalink($post->ID);
					$content = $content.'<br /><a class="cell_read_more" href="'.$read_more_url.'">'.get_option('readmore_label').'</a>';
					$title_link = '<a href="'.$read_more_url.'">';
					$title_link_close = '</a>';
				}
				$cell_edit_link = '';
				$post_edit_url = get_edit_post_link( $post->ID );
				if($post_edit_url){
					$cell_edit_link = '<a class="edit_cell" href="'.$post_edit_url.'">Edit Cell</a>';
				}
				$it_cell_class = esc_attr(get_post_meta($post->ID, '_it_cell_class', true));
				$table_output .= '<div data-posttax="'.$allTerms.'" class="cell '.$allTermsClass.' '.$it_cell_class.'"><div class="horizontal_td_wrap cellwrap"><span class="cell-title">' .$title_link. get_the_title() .$title_link_close. '</span>'.$content. $cell_edit_link .'</div></div>';

				$post_count++;

				if( ($extra_cols > 0) && ($total_rows == $horzRow) && ( $post_count >= $extra_cols) ) {
					for($j=0; $j<($extra_tds+$extra_count); $j++) { //$extra_tds
						$table_output .= '<div data-posttax="" class="cell">&nbsp;</div>';
						$post_count++;
					}
				}

				if( $post_count == (count($terms)+$extra_count) ) { //count($terms)  // +1 for All taxonomy filter
					$table_output .= '</div>';
					$post_count = 0;
					$horzRow++;
				}
			}
		}
		wp_reset_postdata();

		if( ($col_class == 'vertical') ) {
			$table_output .= '</div>';
		}

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) && (($heading_pos == 'Bottom')) ) {
			$table_output .= '<div class="heading '.$class.' terms-'.count($terms).'" data-terms="'.count($terms).'">';
			if(	$display_mode == 'Filter' ) {
				$table_output .= '<div data-tax="all" data-taxposts="'.$total_posts.'" class="cell-heading"><div class="cellwrap"><span>' . __('All', 'IT') . '</span></div></div>';
			}
			foreach ( $termsArr as $termId => $termOrder ) {
				$theTerm = get_term_by('id', $termId, 'it_cell_category');
				$table_output .= '<div data-tax="'.$theTerm->slug.'" data-taxposts="'.$theTerm->count.'" class="cell-heading '.$theTerm->slug.'"><div class="cellwrap"><span>' . $theTerm->name . '</span>'.term_description($termId, 'it_cell_category').'</div></div>';
				$count++;
			}
			$table_output .= '</div>';
		}

		$table_output .= '</div>';
		$table_output .= '</div>';
	}

	return $table_output;
}
add_shortcode('interactive_table', 'it_dynamic_table_shortcode');
