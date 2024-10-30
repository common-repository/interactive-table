<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
if( get_option('it_single_cell_slug_url') ) {
	$single_cell_slug_url = get_option('it_single_cell_slug_url');
} else {
	$single_cell_slug_url = 'interactive-table';
}
$labels = array(
	'name' 					=> __( 'Interactive Table', 'interactive-table' ),
	'singular_name' 		=> __( 'Interactive Table', 'interactive-table' ),
	'menu_name'				=> _x( 'INAV Table', 'Admin menu name', 'interactive-table' ),
	'add_new' 				=> __( 'Add New', 'interactive-table' ),
	'add_new_item' 			=> __( 'Add New', 'interactive-table' ),
	'edit' 					=> __( 'Edit', 'interactive-table' ),
	'edit_item' 			=> __( 'Edit Cell', 'interactive-table' ),
	'new_item' 				=> __( 'New Cell', 'interactive-table' ),
	'view' 					=> __( 'View Cell', 'interactive-table' ),
	'view_item' 			=> __( 'View Cell', 'interactive-table' ),
	'search_items' 			=> __( 'Search Cells', 'interactive-table' ),
	'not_found' 			=> __( 'No Cells found', 'interactive-table' ),
	'not_found_in_trash' 	=> __( 'No Cells found in trash', 'interactive-table' ),
	'parent' 				=> __( 'Parent Cell', 'interactive-table' )
);
register_post_type('interactive_table', array('labels' => $labels,
		'description' 			=> __( 'Responsive Interactive Table', 'interactive-table' ),
		'public' 				=> true,
		'show_ui' 				=> true,
		'capability_type' => 'post',
		'map_meta_cap'			=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> false,
		'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
		'rewrite' => array('slug' => $single_cell_slug_url),
		'taxonomies' => array('itcategories'),
		'query_var' 			=> true,
		'supports' 				=> array('title', 'editor', 'thumbnail', 'page-attributes', 'revisions'),
		'show_in_nav_menus' 	=> true,
		'menu_icon' => INTERACTIVE_TABLE_URL . 'img/it-nav.png',
	)
);

register_taxonomy( 'it_cell_category',
	apply_filters( 'interactive_table_taxonomy_objects_it_cell_category', array( 'interactive_table' ) ),
	apply_filters( 'interactive_table_taxonomy_args_it_cell_category', array(
		'hierarchical' 			=> true,
		'label' 				=> __( 'Cell Category', 'interactive-table' ),
		'labels' => array(
				'name' 				=> __( 'Cell Category', 'interactive-table' ),
				'singular_name' 	=> __( 'Cell Category', 'interactive-table' ),
				'menu_name'			=> _x( 'Category', 'Admin menu name', 'interactive-table' ),
				'search_items' 		=> __( 'Search Cell Category', 'interactive-table' ),
				'all_items' 		=> __( 'All Cell Categories', 'interactive-table' ),
				'parent_item' 		=> __( 'Parent Cell Category', 'interactive-table' ),
				'parent_item_colon' => __( 'Parent Cell Category:', 'interactive-table' ),
				'edit_item' 		=> __( 'Edit Cell Category', 'interactive-table' ),
				'update_item' 		=> __( 'Update Cell Category', 'interactive-table' ),
				'add_new_item' 		=> __( 'Add New Cell Category', 'interactive-table' ),
				'new_item_name' 	=> __( 'New Cell Category Name', 'interactive-table' )
			),
		'show_ui' 				=> true,
		'show_in_nav_menus' => false,
		'show_admin_column'     => true,
		'query_var' 			=> true,
		'rewrite' => array( 'slug' => 'cell-category' ),
	) )
);

function interactive_table_admin_page_removing(){
	remove_submenu_page( 'edit.php?post_type=interactive_table', 'edit-tags.php?taxonomy=it_cell_category&amp;post_type=interactive_table' );
}
add_action( 'admin_menu', 'interactive_table_admin_page_removing' );

function interactive_table_settings_script($hook) {
  if(($hook == 'interactive_table_page_it-settings') || ($hook == 'interactive_table_page_it-cell-cetegory')) {
    wp_enqueue_style( 'it_admin_css', INTERACTIVE_TABLE_URL.'css/itv-admin.css' );
  }
}
add_action( 'admin_enqueue_scripts', 'interactive_table_settings_script' );

// Add inline CSS in the admin head with the style tag
function interactive_table_global_admin_head() {
	echo '<style>#it_cell_category-adder, #it_cell_category-add-toggle {display: none !important;}
	</style>';
}
add_action( 'admin_head', 'interactive_table_global_admin_head' );
