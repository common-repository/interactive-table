<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
add_action('add_meta_boxes', 'it_cell_specification_metaboxes');
function it_cell_specification_post_box() {
	echo '<input type="hidden" name="it_cell_specification_noncename" id="it_cell_specification_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	global $post;
	?>
	<table style="width:100%;">
		<tr>
		<td>
		<?php
			$content = esc_html(get_post_meta($post->ID, '_it_short_description', true));
			$editor_id = 'it_short_description';
			wp_editor( $content, $editor_id, array( 'textarea_rows' => 5) );
		?>
		</td>
		</tr>
		<?php $it_url = esc_url(get_post_meta($post->ID, '_it_url', true));	?>
		<tr><td><?php echo __('Read More Redirect Link:', 'interactive-table'); ?> <input type="text" class="regular-text" name="it_url" id="it_url" value="<?php echo $it_url; ?>" /></td></tr>
		<?php $it_cell_class = get_post_meta($post->ID, '_it_cell_class', true);	?>
		<tr><td><?php echo __('Cell Custom CSS Class:', 'interactive-table'); ?> <input type="text" class="regular-text" name="it_cell_class" id="it_cell_class" value="<?php echo $it_cell_class; ?>" /></td></tr>
	</table>
	<?php
}

function it_cell_specification_metaboxes() {
	add_meta_box('it_cell_specification', __('Short Description', 'interactive-table'), 'it_cell_specification_post_box', 'interactive_table', 'normal', 'high');
}



add_action( 'save_post', 'it_cell_specification_add_or_save', 10, 2 );
function it_cell_specification_add_or_save($post_id, $post){

		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if (!isset($_POST['it_cell_specification_noncename']) || !wp_verify_nonce($_POST['it_cell_specification_noncename'], plugin_basename(__FILE__))) {
			return $post->ID;
		}

	  // Check permissions
	  if ( 'interactive_table' == $_POST['post_type'] ){
			if ( !current_user_can( 'edit_pages', $post_id ) )
				return;
	  }


		if ($_POST['it_short_description']) {
			add_post_meta($post_id, '_it_short_description', wp_kses_post($_POST['it_short_description']), TRUE) or update_post_meta($post_id, '_it_short_description', wp_kses_post($_POST['it_short_description']));
		} else {
			delete_post_meta($post_id, '_it_short_description');
		}
		if ($_POST['it_url']) {
			add_post_meta($post_id, '_it_url', sanitize_text_field($_POST['it_url']), TRUE) or update_post_meta($post_id, '_it_url', sanitize_text_field($_POST['it_url']));
		} else {
			delete_post_meta($post_id, '_it_url');
		}

		if ($_POST['it_cell_class']) {
			add_post_meta($post_id, '_it_cell_class', sanitize_text_field($_POST['it_cell_class']), TRUE) or update_post_meta($post_id, '_it_cell_class', sanitize_text_field($_POST['it_cell_class']));
		} else {
			delete_post_meta($post_id, '_it_cell_class');
		}

		$total_cells = get_total_it_cell();
		if($total_cells > 12){
			global $wpdb;
			$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
			add_filter( 'redirect_post_location', 'itv_cell_restriction_notice_query_var', 99 );
		}

}

function itv_cell_restriction_notice_query_var( $location ) {
	return add_query_arg( array( 'cell_limitation' => 'yes' ), $location );
}

add_action( 'admin_notices', 'it_cell_validation_admin_notice' );
function it_cell_validation_admin_notice(){
	if(isset($_GET['cell_limitation']) && ($_GET['cell_limitation'] =='yes')){
		?>
		<div class="error">
			<p><?php _e( 'You have the lite version of Interactive Table, which limits you to 12 table cell. Please <a href="http://codecanyon.net/item/responsive-interactive-table/16312261?ref=RMweblab" target="_blank">upgrade to</a> the pro version if you need more.', 'interative-table' ); ?></p>
		</div>
		<?php
	}
}

function get_total_it_cell(){
	$count_cells = wp_count_posts('interactive_table');
	$published_cells = $count_cells->publish;
	return $published_cells;
}
