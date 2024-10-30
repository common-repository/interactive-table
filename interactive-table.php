<?php
/*
Plugin Name: Interactive Table Lite
Plugin URI: http://plugins.rmweblab.com/interactive-table/
Description: A dynamic way to display clean & responsive interactive table for your contents in a nice view.
Author: RM Web Lab
Version: 2.0.0
Author URI: http://rmweblab.com
Text Domain: interactive-table
Domain Path: /languages

Copyright: © 2018 RMWebLab.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*****/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define contants
define('INTERACTIVE_TABLE_ROOT', dirname(__FILE__));
define('INTERACTIVE_TABLE_URL', plugins_url( 'interactive-table/' ));

include_once(INTERACTIVE_TABLE_ROOT . '/include/it-functions.php');
include_once(INTERACTIVE_TABLE_ROOT . '/include/it-shortcodess.php');
include_once(INTERACTIVE_TABLE_ROOT . '/include/it-metaboxes-config.php');


class Interactive_Table_Lite {

    /* Constructor for the class */
    function __construct() {
  		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
  		add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
      add_action('init', array(&$this, 'register_it_custom_post_type'), 10);

  		/* Add admin menu */
  		add_action('admin_menu', array(&$this, 'it_settings_page'));

  		add_action('wp_enqueue_scripts', array(&$this, 'it_print_scripts'), 10);
  		add_action( 'wp_head', array(&$this, 'it_dynamic_styles') );
  		/*interactive table post details page*/
  		add_filter('single_template', array(&$this, 'get_interactive_table_post_type_template'));

    }

	/**
	 * Init localisations and files
	 */
	public function init() {
    //Settings save
    require_once('include/it-settings-process.php');
		// Localisation
		load_plugin_textdomain( 'interactive-table', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}


	/**
	 * Add relevant links to plugins page
	 * @param  array $links
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'edit.php?post_type=interactive_table&page=it-settings' ) . '">' . __( 'Settings', 'interactive-table' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}


    /**
     * Plugins settings page
     */
    public function it_settings_page() {
				add_submenu_page( 'edit.php?post_type=interactive_table', 'Category', 'Category', 'manage_options', 'it-cell-cetegory', array(&$this, 'it_cell_category_plug_page'));
				add_submenu_page( 'edit.php?post_type=interactive_table', 'Settings', 'Settings', 'manage_options', 'it-settings', array(&$this, 'it_settings_plug_page'));
    }

    /**
     * Plugins settings page
     */
    public function it_settings_plug_page() {
  		require_once( INTERACTIVE_TABLE_ROOT . '/include/it-settings.php');
  	}

		/**
     * Plugins category page
     */
    public function it_cell_category_plug_page() {
  		require_once( INTERACTIVE_TABLE_ROOT . '/include/it-cell-category.php');
  	}


    /**
     * Register Interactive Table CPT
     *
     */
  	public function register_it_custom_post_type() {
          $include_path = INTERACTIVE_TABLE_ROOT . '/include/';
  		    include_once($include_path . 'it-custom-post-type.php');
  	}

    /**
     * InteractiveTable ajax script load.
     */
  	public function it_print_scripts() {
  		wp_enqueue_script('jquery');

  		$ajaxurl = admin_url('admin-ajax.php');
  		$ajax_nonce = wp_create_nonce('InteractiveTable');
  		$display_mode = get_option('it_display_mode');
  		$highlight_mode = get_option('it_highlight_mode');
  		$cell_highlight_color = get_option('cell_highlight_color');
  		$heading_position = get_option('it_block_heading_position');
  		$cell_hover = get_option('it_cell_hover');
  		$title_hover = get_option('it_title_hover');
  		$args = array(
  			'post_type' => 'interactive_table',
  			'posts_per_page' => -1,
  			'orderby'   => 'menu_order',
  			'order'     => 'ASC',
  		);

  		$query = new WP_Query( $args );
  		$posts_per_row = 1;
  		if($query->have_posts()) {
  			$total_posts = $query->post_count;
  			$terms = get_terms( 'it_cell_category' );
  			$posts_per_row = ceil($total_posts/count($terms));
  		}
  		wp_localize_script( 'jquery', 'ajaxObj', array( 'ajaxurl' => $ajaxurl, 'ajax_nonce' => $ajax_nonce, 'display_mode' => $display_mode, 'heading_position' => $heading_position, 'v_posts_per_row' => $posts_per_row, 'highlight_mode' => $highlight_mode, 'cell_hover' => $cell_hover, 'title_hover' => $title_hover ) );

  		$categories = get_terms( 'it_cell_category', array('hide_empty' => 0) );
  		$term_colors = array();
  	    foreach ( $categories as $category ) {
  	        $t_id = $category->term_id;
  	        $term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
  	        if (isset($term_meta['cell_cat_color']) && ($term_meta['cell_cat_color'] != '')) {
  	        	$term_color = $term_meta['cell_cat_color'];
  	        } else {
  	        	$term_color = $cell_highlight_color;
  	        }
  	        $term_colors[$category->slug] = $term_color;
  	    }
  		wp_localize_script( 'jquery', 'termObj', $term_colors );

  		wp_enqueue_style( 'table-style', plugins_url('/css/itv-style.css', __FILE__ ) );
  		if( get_option('it_load_default_css') != 'No' ) {
  			wp_enqueue_style( 'default-table-style', plugins_url('/css/itv-default.css', __FILE__ ) );
  		}
  		wp_enqueue_script( 'table-script', plugins_url('/js/it-script.js', __FILE__ ) );
  	}

    /**
     * InteractiveTable load dynamic styles.
     */
  	public function it_dynamic_styles() {
  	?>
  		<style type="text/css">

  		</style>
      <?php

	   }

		 /**
			* Install default data
			*/
		 static function it_plugin_install(){
			 require_once('include/default-settings.php');
		 }

		 /**
			* Uninstall default data
			*/
		 static function it_plugin_uninstall(){
			 require_once('include/uninstall-default-settings.php');
		 }

    /**
     * InteractiveTable load single post template.
     */
  	public function get_interactive_table_post_type_template($single_template) {
  		 global $post;

  		 if ($post->post_type == 'interactive_table') {
  			$single_template_from_theme = get_stylesheet_directory() . '/single-interactive_table.php';
  			if (file_exists($single_template_from_theme)) {
  				$single_template = $single_template_from_theme;
  			}else{
  			  	$single_template = dirname( __FILE__ ) . '/templates/single-interactive_table.php';
  			  }
  		 }
  		 return $single_template;
  	}
}


function color_picker_assets($hook_suffix) {
	// $hook_suffix to apply a check for admin page.
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'field-color-picker', plugins_url('/js/Field_Color.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'color_picker_assets' );


global $Interactive_Table_Lite;
$Interactive_Table_Lite = new Interactive_Table_Lite();

register_activation_hook( __FILE__, array( 'Interactive_Table_Lite', 'it_plugin_install' ) );
register_deactivation_hook( __FILE__, array( 'Interactive_Table_Lite', 'it_plugin_uninstall' ) );
