<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
// A callback function to add a custom field to our "it_cell_category" taxonomy
function it_cell_category_taxonomy_custom_fields($tag) {
   // Check for existing taxonomy meta for the term we're editing
	$t_id = $tag->term_id; // Get the ID of the term we're editing
	$term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
?>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="cell_cat_order"><?php echo __('Order', 'interactive-table'); ?></label>
	</th>
	<td>
		<input type="text" name="term_meta[cell_cat_order]" id="term_meta[cell_cat_order]" size="25" style="width:60%;" value="<?php echo $term_meta['cell_cat_order'] ? esc_html($term_meta['cell_cat_order']) : ''; ?>"><br />
		<span class="description"><?php echo __('Cell Category Order.', 'interactive-table'); ?></span>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="cell_cat_color"><?php echo __('Color', 'interactive-table'); ?></label>
	</th>
	<td>
		<input type="text" name="term_meta[cell_cat_color]" id="term_meta[cell_cat_color]" size="25" class="color-picker" value="<?php echo $term_meta['cell_cat_color'] ? esc_html($term_meta['cell_cat_color']) : ''; ?>"><br />
		<span class="description"><?php echo __('Cell Category Color.', 'interactive-table'); ?></span>
	</td>
</tr>

<?php
}
add_action( 'it_cell_category_edit_form_fields', 'it_cell_category_taxonomy_custom_fields', 10, 2 );

function it_add_custom_tax_field_oncreate( $term ){
	echo "<div class='form-field term-order-wrap'>";
	echo "<label for='term_meta[cell_cat_order]'>".__('Order', 'interactive-table')."</label>";
	echo "<input id='term_meta[cell_cat_order]' value='' size='10' type='text' name='term_meta[cell_cat_order]'/>";
	echo '<p class="description">'.__('Cell Category Order.', 'interactive-table').'</p>';
	echo "</div>";

	echo "<div class='form-field term-color-wrap'>";
	echo "<label for='term_meta[cell_cat_color]'>".__('Color', 'interactive-table')."</label>";
	echo '<input type="text" id="term_meta[cell_cat_color]" name="term_meta[cell_cat_color]" class="color-picker" value="" />';
	echo '<p class="description">'.__('Cell Category Color', 'interactive-table').'.</p>';
	echo "</div>";
}
// Add the fields to the "it_cell_category" taxonomy, using our callback function
add_action( 'it_cell_category_add_form_fields', 'it_add_custom_tax_field_oncreate' );

// A callback function to save our extra taxonomy field(s)
function it_save_taxonomy_custom_fields( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_term_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
        foreach ( $cat_keys as $key ){
		      if ( isset( $_POST['term_meta'][$key] ) ){
		          $term_meta[$key] = sanitize_text_field($_POST['term_meta'][$key]);
		      }
        }
        //save the option array
        update_option( "taxonomy_term_$t_id", $term_meta );
    }
}
// Save the changes made on the "it_cell_category" taxonomy, using our callback function
add_action( 'create_it_cell_category', 'it_save_taxonomy_custom_fields' );
add_action( 'edited_it_cell_category', 'it_save_taxonomy_custom_fields', 10, 2 );


function it_save_cell_cat_custom_fields( $term_id, $req_term_meta ) {
    if ( isset( $req_term_meta ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_term_$t_id" );
        $cat_keys = array_keys( $req_term_meta );
        foreach ( $cat_keys as $key ){
		      if ( isset( $req_term_meta[$key] ) ){
		          $term_meta[$key] = sanitize_text_field($req_term_meta[$key]);
		      }
        }
        //save the option array
        update_option( "taxonomy_term_$t_id", $term_meta );
    }
}
