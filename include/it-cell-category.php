<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="wrap">
	<?php
	if ( isset($_POST['it_cellcat_nonce']) && (! isset( $_POST['it_cellcat_nonce'] ) || ! wp_verify_nonce( $_POST['it_cellcat_nonce'], 'it_cellcat_action' ) ) ) {
		 //Verifiy not match..
		 interactive_table_notice_data_nonce_verify_required();
	} else {
		 if(isset($_POST['it_cellcat_submit'])){
			 	 $category_ids = $_POST['category_ids'];
				 $cellcat_title = $_POST['cellcat_title'];
				 $cellcat_desc = $_POST['cellcat_desc'];
				 $cellcat_order = $_POST['cellcat_order'];
				 $cellcat_color = $_POST['cellcat_color'];

				 if(isset($category_ids) && (count($category_ids) > 0)){
					 foreach ($category_ids as $key_index => $term_id) {
						 $new_cat_name = sanitize_text_field($cellcat_title[$key_index]);
						 $new_cat_desc = sanitize_textarea_field($cellcat_desc[$key_index]);
						 $new_cellcat_order = sanitize_text_field($cellcat_order[$key_index]);
						 $new_cellcat_color = sanitize_text_field($cellcat_color[$key_index]);
						 wp_update_term($term_id, 'it_cell_category', array(
							 'name' => $new_cat_name,
							 'description' => $new_cat_desc
						 ));
						 $req_term_meta['cell_cat_order'] = $new_cellcat_order;
						 $req_term_meta['cell_cat_color'] = $new_cellcat_color;
						 it_save_cell_cat_custom_fields( $term_id, $req_term_meta );
					 }
			 	 }

				 interactive_table_notice_data_successfully_saved();
		 }

		 if(isset($_POST['add_new_category_submit'])){
			 $new_term_1 = wp_insert_term(
				 'Clothing', // the term
				 'it_cell_category', // the taxonomy
					 array(
						 'description'=> 'Cat descriptions.',
						 'slug' => 'it-cat-1'
					 )
			 );
			 //#c0504e

			 $new_term_2 = wp_insert_term(
				 'Beauty', // the term
				 'it_cell_category', // the taxonomy
					 array(
						 'description'=> 'Cat descriptions.',
						 'slug' => 'it-cat-2'
					 )
			 );
			 //#9bbb58

			 $new_term_3 = wp_insert_term(
				 'Automotive', // the term
				 'it_cell_category', // the taxonomy
					 array(
						 'description'=> 'Cat descriptions.',
						 'slug' => 'it-cat-3'
					 )
			 );
			 //#8165a2

			 $new_term_4 = wp_insert_term(
				 'Accessories', // the term
				 'it_cell_category', // the taxonomy
					 array(
						 'description'=> 'Cat descriptions.',
						 'slug' => 'it-cat-4'
					 )
			 );
			 //#f79649
		 }
	}
	?>
<h2 style="margin-bottom:15px;"><?php echo __('Interactive Table Category', 'interactive-table'); ?></h2>
<form name="it_settings" method="post" action="edit.php?post_type=interactive_table&page=it-cell-cetegory">
	<input type="hidden" name="it_settings_page" value="interactive_table_settings">
	<?php wp_nonce_field( 'it_cellcat_action', 'it_cellcat_nonce' ); ?>
	<?php wp_referer_field(); ?>
	<div class="settings_left_col_catpage">
		<?php if(isset($_GET['view-shortcode']) && ($_GET['view-shortcode'] == 'yes')){ ?>
			<?php
			$term_ids = array();
			$term_ids_output = '';
			$term_ids_output_name = '';
			$categories = get_terms( 'it_cell_category', array('hide_empty' => 0) );
			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
				foreach ( $categories as $category ) {
						$term_ids[] = $category->term_id;
						$term_ids_output_name .= $category->term_id.' = '.$category->name.',   ';
				}
				$term_ids_output = implode(', ', $term_ids);
			}
			?>
				<div class="shortcode_block">
					<code>[interactive_table category="<?php echo $term_ids_output; ?>"]</code>
						<br /><br /><strong>Here:</strong> <?php echo $term_ids_output_name; ?>
				</div>
		<?php } ?>
	<table class="form-table">
		<tr valign="top">
			<th style="width:50px;"><?php echo __('ID', 'interactive-table'); ?></th>
			<th><?php echo __('Title', 'interactive-table'); ?></th>
			<th><?php echo __('Description', 'interactive-table'); ?></th>
			<th style="width:20px;"><?php echo __('Order', 'interactive-table'); ?></th>
			<th><?php echo __('Color', 'interactive-table'); ?></th>
		</tr>
		<?php
		$categories = get_terms( 'it_cell_category', array('hide_empty' => 0) );
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
					$term_id = $category->term_id;
					$term_name = $category->name;
					$term_description = $category->description;

					$term_order = 0;
					$term_color = '';
					$term_meta = get_option( "taxonomy_term_$term_id" );
					if (isset($term_meta['cell_cat_color']) && ($term_meta['cell_cat_color'] != '')) {
						$term_color = $term_meta['cell_cat_color'];
					}
					$term_order = '';
					if (isset($term_meta['cell_cat_order']) && ($term_meta['cell_cat_order'] != '')) {
						$term_order = $term_meta['cell_cat_order'];
					}
					?>
					<tr valign="top">
						<td style="padding-top:0;">
							<input type="hidden" name="category_ids[]" value="<?php echo intval($term_id); ?>" />
							<?php echo $term_id; ?>
						</td>
						<td style="padding-top:0;">
							<input type="text" name="cellcat_title[]" class="" value="<?php echo esc_html($term_name); ?>" />
						</td>
						<td style="padding-top:0;">
							<textarea name="cellcat_desc[]" class=""><?php echo esc_textarea($term_description); ?></textarea>
						</td>
						<td style="padding-top:0;">
							<input type="text" name="cellcat_order[]" max="2" class="order_call_input" value="<?php echo intval($term_order); ?>" />
						</td>
						<td style="padding-top:0;">
							<input type="text" name="cellcat_color[]" class="color-picker" value="<?php echo esc_html($term_color); ?>" />
						</td>
					</tr>
					<?php
			}
		}
		?>
		<tr valign="top">
			<td><a class="button" href="edit.php?post_type=interactive_table&page=it-cell-cetegory&view-shortcode=yes">View Shortcode</a></td>
			<td colspan="3">
			<?php
			$categories_check = get_terms( 'it_cell_category', array('hide_empty' => 0) );
			if ( ! empty( $categories_check ) && ! is_wp_error( $categories_check ) ) {
			  //Nothing insert.
			}else{
				?><p class="submit" style="text-align:center;"><input type="submit" name="add_new_category_submit" class="button button-secondary btn_add_new_categories" value="<?php _e('Add Categories', 'interactive-table') ?>" /></p><?php
			}
			?>
			</td>
			<td style="padding-top:0;">
				<p class="submit"><input type="submit" name="it_cellcat_submit" class="button-primary" value="<?php _e('Save Changes', 'interactive-table') ?>" /></p>
			</td>
		</tr>
	</table>
</div><!-- settings_left_col -->
<div class="itv_settings_right_box_catpage">
	<?php require_once( INTERACTIVE_TABLE_ROOT . '/include/it-promotion-box.php'); ?>
</div><!-- itv_settings_right_box -->
</form>
</div>
