<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="wrap">
	<?php
	if ( isset($_POST['it_settings_nonce']) && (! isset( $_POST['it_settings_nonce'] ) || ! wp_verify_nonce( $_POST['it_settings_nonce'], 'it_settings_action' ) ) ) {
		 //Verifiy not match..
		 interactive_table_notice_data_nonce_verify_required();
	} else {
		 if(isset($_POST['it_settings_options_submit'])){
				 update_option( 'it_block_heading_position', sanitize_text_field($_POST['block_heading_position']) );
				 update_option( 'it_cell_title_color', sanitize_text_field($_POST['cell_title_color']) );
				 update_option( 'it_cell_font_color', sanitize_text_field($_POST['cell_font_color']) );
				 update_option( 'it_cell_hover_title_color', sanitize_text_field($_POST['cell_hover_title_color']) );
				 update_option( 'it_cell_hover_font_color', sanitize_text_field($_POST['cell_hover_font_color']) );
				 update_option( 'it_read_more_font_color', sanitize_text_field($_POST['read_more_font_color']) );
				 update_option( 'it_display_mode', sanitize_text_field($_POST['display_mode']) );
				 update_option( 'it_highlight_mode', sanitize_text_field($_POST['highlight_mode']) );
				 update_option( 'cell_highlight_color', sanitize_text_field($_POST['cell_highlight_color']) );
				 update_option( 'it_load_default_css', sanitize_text_field($_POST['load_default_css']) );
				 update_option( 'it_vertical_posts_per_row', sanitize_text_field($_POST['vertical_posts_per_row']) );
				 if( isset($_POST['cell_hover']) ) {
					 update_option( 'it_cell_hover', sanitize_text_field($_POST['cell_hover']) );
				 } else {
					 update_option( 'it_cell_hover', '' );
				 }

				 if( isset($_POST['title_hover']) ) {
					 update_option( 'it_title_hover', sanitize_text_field($_POST['title_hover']) );
				 } else {
					 update_option( 'it_title_hover', '' );
				 }

				 if( isset($_POST['fixed_highlight']) ) {
					 update_option( 'fixed_highlight', sanitize_text_field($_POST['fixed_highlight']) );
				 } else {
					 update_option( 'fixed_highlight', '' );
				 }
				 if(isset($_POST['it_clean_on_deactive'])){
	         update_option('it_clean_on_deactive', sanitize_text_field($_POST['it_clean_on_deactive']));
	       }else{
	         update_option('it_clean_on_deactive', 'no');
	       }
				 interactive_table_notice_data_successfully_saved();
		 }
	}
	?>
<h2 style="margin-bottom:15px;"><?php echo __('Interactive Table Settings', 'interactive-table'); ?></h2>
<form name="it_settings" method="post" action="edit.php?post_type=interactive_table&page=it-settings">
	<input type="hidden" name="it_settings_page" value="interactive_table_settings">
	<?php wp_nonce_field( 'it_settings_action', 'it_settings_nonce' ); ?>
	<?php wp_referer_field(); ?>
	<div class="settings_left_col">
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
			<th scope="row" style="padding-top:0;"><?php echo __('Block Heading', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
	        <select name="block_heading_position">
	        	<option value="Top" <?php if( get_option('it_block_heading_position') == 'Top' ) echo 'selected="selected"'; ?>><?php echo __('Top', 'interactive-table'); ?></option>
	        	<option disabled="disabled" value="Left" <?php if( get_option('it_block_heading_position') == 'Left' ) echo 'selected="selected"'; ?>><?php echo __('Left', 'interactive-table'); ?></option>
	        	<option disabled="disabled" value="Right" <?php if( get_option('it_block_heading_position') == 'Right' ) echo 'selected="selected"'; ?>><?php echo __('Right', 'interactive-table'); ?></option>
	        	<option disabled="disabled" value="Bottom" <?php if( get_option('it_block_heading_position') == 'Bottom' ) echo 'selected="selected"'; ?>><?php echo __('Bottom', 'interactive-table'); ?></option>
	        </select>
					<br />
					<small>* All disabled features are available in <a href="http://codecanyon.net/item/responsive-interactive-table/16312261?ref=RMweblab" target="_blank">our pro version.</a></small>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Posts per row', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
            	<input type="text" name="vertical_posts_per_row" value="<?php echo get_option('it_vertical_posts_per_row'); ?>" />
                <br />
                <small><?php echo __('Applicable if "Block Heading" set to Left/Right', 'interactive-table'); ?></small>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Cell Title Color', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
				<input type="text" name="cell_title_color" class="color-picker" value="<?php echo get_option('it_cell_title_color'); ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Cell Hover Title Color', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
				<input type="text" name="cell_hover_title_color" class="color-picker" value="<?php echo get_option('it_cell_hover_title_color'); ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Cell Font Color', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
				<input type="text" name="cell_font_color" class="color-picker" value="<?php echo get_option('it_cell_font_color'); ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Cell Hover Font Color', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
				<input type="text" name="cell_hover_font_color" class="color-picker" value="<?php echo get_option('it_cell_hover_font_color'); ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Read More Font Color', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
				<input type="text" name="read_more_font_color" class="color-picker" value="<?php echo get_option('it_read_more_font_color'); ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Display Mode', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
                <select name="display_mode">
                	<option value="Highlight" <?php if( get_option('it_display_mode') == 'Highlight' ) echo 'selected="selected"'; ?>><?php echo __('Highlight', 'interactive-table'); ?></option>
                	<option disabled="disabled" value="Filter" <?php if( get_option('it_display_mode') == 'Filter' ) echo 'selected="selected"'; ?>><?php echo __('Filter', 'interactive-table'); ?></option>
                </select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Highlight Mode', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
                <select name="highlight_mode">
                	<option value="On Hover" <?php if( get_option('it_highlight_mode') == 'On Hover' ) echo 'selected="selected"'; ?>><?php echo __('On Hover', 'interactive-table'); ?></option>
                	<option disabled="disabled" value="On Click" <?php if( get_option('it_highlight_mode') == 'On Click' ) echo 'selected="selected"'; ?>><?php echo __('On Click', 'interactive-table'); ?></option>
                </select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Highlight Color', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
				<input type="text" name="cell_highlight_color" class="color-picker" value="<?php echo get_option('cell_highlight_color'); ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Cell Hover', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
				<input type="checkbox" name="cell_hover" id="cell_hover" value="enable" <?php if(get_option('it_cell_hover')) echo 'checked'; ?> /> <label for="cell_hover"><?php echo __('Enable', 'interactive-table'); ?></label>
			</td>
		</tr>
	</table>
	<table class="form-table">
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Single cell slug URL', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
            	<input disabled="disabled" type="text" name="single_cell_slug_url" value="box-cell" />
                <br />
                <small><?php echo __('Re-save WP Settings->Permalink again if you update this slug URL', 'interactive-table'); ?></small>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Title Hover', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
				<input type="checkbox" name="title_hover" id="title_hover" value="enable" <?php if(get_option('it_title_hover')) echo 'checked'; ?> /> <label for="title_hover"><?php echo __('Enable', 'interactive-table'); ?></label>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Read More', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
                <select name="read_more">
									<option value="No" <?php if( get_option('it_read_more') == 'No' ) echo 'selected="selected"'; ?>><?php echo __('No', 'interactive-table'); ?></option>
                	<option disabled="disabled" value="Yes" <?php if( get_option('it_read_more') == 'Yes' ) echo 'selected="selected"'; ?>><?php echo __('Yes', 'interactive-table'); ?></option>
                </select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Read More Label', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
        	<input disabled="disabled" type="text" name="readmore_label" value="Read More" />
            <br />
            <small><?php echo __('Read More Link Text', 'interactive-table'); ?></small>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Load Default CSS', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
          <select name="load_default_css">
          	<option value="Yes" <?php if( get_option('it_load_default_css') == 'Yes' ) echo 'selected="selected"'; ?>><?php echo __('Yes', 'interactive-table'); ?></option>
          	<option value="No" <?php if( get_option('it_load_default_css') == 'No' ) echo 'selected="selected"'; ?>><?php echo __('No', 'interactive-table'); ?></option>
          </select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Fixed Highlight', 'interactive-table'); ?></th>
			<td style="padding-top:0;">
				<input type="checkbox" name="fixed_highlight" id="fixed_highlight" value="enable" <?php if(get_option('fixed_highlight')) echo 'checked'; ?> /> <label for="fixed_highlight"><?php echo __('Enable', 'interactive-table'); ?></label>
			</td>
		</tr>
		<tr>
		<?php
		$it_clean_on_deactive = '';
		if(get_option('it_clean_on_deactive')){
			$it_clean_on_deactive = get_option('it_clean_on_deactive');
		}
		?>
		<th scope="row"><?php echo __( 'Deactivation', 'interactive-table' ); ?></th>
		<td><fieldset><legend class="screen-reader-text"><span><?php echo __( 'Deactivation', 'interactive-table' ); ?></span></legend><label for="it_clean_on_deactive">
		<input name="it_clean_on_deactive" type="checkbox" id="it_clean_on_deactive"  <?php checked( 'yes', $it_clean_on_deactive, true ); ?> value="yes">
		<?php echo __( 'Delete all settings on deactivation. DO NOT use this option, unless you want to remove ALL Interactive Table settings and data.', 'interactive-table' ); ?></label>
		</fieldset></td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"></th>
			<td style="padding-top:0;">
				<p class="submit"><input type="submit" name="it_settings_options_submit" class="button-primary" value="<?php _e('Save Changes', 'interactive-table') ?>" /></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"></th>
			<td style="padding-top:0;">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" style="padding-top:0;"></th>
			<td style="padding-top:0;">
				<a class="button" href="edit.php?post_type=interactive_table&page=it-settings&view-shortcode=yes">View Shortcode</a>
			</td>
		</tr>
	</table>
</div><!-- settings_left_col -->
<div class="itv_settings_right_box">
	<?php require_once( INTERACTIVE_TABLE_ROOT . '/include/it-promotion-box.php'); ?>
</div><!-- itv_settings_right_box -->

</form>
</div>
