<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
//notice-error, notice-warning, notice-success, or notice-info  updated is-dismissible
function interactive_table_notice_data_successfully_saved(){
  $class = 'notice updated is-dismissible';
	$message = __( 'Settings saved.', 'interactive-table' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

function interactive_table_notice_data_nonce_verify_required(){
  $class = 'notice notice-error is-dismissible';
	$message = __( 'Nonce unverified! Try again.', 'interactive-table' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}
