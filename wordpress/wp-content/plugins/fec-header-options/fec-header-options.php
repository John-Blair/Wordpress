<?php
/*
	Plugin Name: FEC Page Header Options
	Description: Page Header Options: Carousel, Header Image
	Author: John Blair
	Version: 1.0
	Author URI: http://#
	License: GPLv3
	License URI: http://gnu.org/licenses/gpl.html
 */

// Initialise the plugin.
add_action( 'init', 'fec_init' );
function fec_init() {
	add_action( 'admin_menu', 'fec_custom_meta_boxes_header' );

	add_action( 'admin_enqueue_scripts', 'fec_admin_scripts_header' );
	add_action( 'wp_enqueue_scripts', 'fec_public_css_header' );
	add_action( 'save_post', 'fec_save_postdata_header', 1, 2 ); // 2 arguments post id and post
	add_filter( 'the_content', 'fec_filter_content_header' );
}

function fec_custom_meta_boxes_header() {
	// Add custom header options to a page
	add_meta_box( 'fec_custom_meta_boxes_header', 'Header', 'fec_custom_meta_header', 'page', 'normal', 'high' );
}

function fec_custom_meta_header() {
	global $post;

	// nonce required to save to db.
	echo '<input type="hidden" name="fec_noncename" id="fec_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	//Carousel.
	echo '<p><label for="fec_carousel_id">Carousel Id</label><br />';
	echo '<input style="width: 55%;" type="text" name="fec_carousel_id" value="' .  get_post_meta($post->ID, 'fec_carousel_id', true)  . '" /></p>';


	// Header Image
	echo '<p><label for="fec_header_image">Header Image</label><br />';
	echo '<input style="width: 55%;" id="fec_header_image" class="album-art" name="fec_header_image" value="' . get_post_meta($post->ID, 'fec_header_image', true) . '" type="text" /> <input id="fec_upload_file_image_button" type="button" class="upload_button button button-primary" value="Upload Image" />';

}


function fec_admin_scripts_header() {
	wp_enqueue_script( 'fec-header-image-uploader', plugins_url( 'js/uploader.js', __FILE__ ), array( 'jquery' ), '1.0' );
	wp_enqueue_script( 'media-upload' );
}

function fec_public_css_header() {
	//if ( !is_admin() )
	//	wp_enqueue_style( 'genericons', plugins_url( 'css/genericons.css', __FILE__ ), array(), '1.0' );
}

function fec_save_postdata_header( $post_id, $post ) {
	$nonce = isset( $_POST['fec_noncename'] ) ? $_POST['fec_noncename'] : 'some random string here';
	if ( !wp_verify_nonce( $nonce, plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}
	/* can the user edit the post? */
	if ( !current_user_can( 'edit_post', $post->ID ) )
		return $post->ID;

	/* here's a bunch of stuff we're storing */
	$meta_keys = array(
		'fec_carousel_id' => 'numeric',
		'fec_header_image' => 'url'
	);

	/* sanitize data based on type of content */
	foreach( $meta_keys as $meta_key => $type ) {
		if( $post->post_type == 'revision' )
			return;
		if ( isset( $_POST[ $meta_key ] ) ) {
			if ( $type == 'text' ) {
				$value = wp_kses_post( $_POST[ $meta_key ] );
			}
			if ( $type == 'url' ) {
				$value = htmlspecialchars( $_POST[ $meta_key ] );
			}
			if ( $type == 'embed' ) {
				$kses_allowed = array_merge(wp_kses_allowed_html( 'post' ), array('iframe' => array(
					'src' => array(),
					'style' => array(),
					'width' => array(),
					'height' => array(),
					'scrolling' => array(),
					'frameborder' => array()
					)));
				$value = wp_kses( $_POST[ $meta_key ], $kses_allowed );
			}
			if ( $type == 'numeric' && is_numeric($_POST[ $meta_key ]) ) {
					$value = wp_kses_data( $_POST[ $meta_key ] );
			}

			update_post_meta( $post->ID, $meta_key, $value );
		} else {
			delete_post_meta( $post->ID, $meta_key );
		}
	}
}

function fec_filter_content_header( $content ) {
	global $post;

	$new_content = null;

	$fec_carousel_id = null;
	if ( get_post_meta( $post->ID, 'fec_carousel_id', true ) ) {
		$fec_carousel_id = get_post_meta( $post->ID, 'fec_carousel_id', true );
	}
	if ($fec_carousel_id){
		$new_content .= "<div>Carousel Id=$fec_carousel_id </div>" ;
		$new_content .= "<div>" . do_shortcode("[smartslider3 slider=$fec_carousel_id]") . "</div>";
	}

	$fec_header_image = null;
	if ( get_post_meta( $post->ID, 'fec_header_image', true ) ) {
		$fec_header_image = get_post_meta( $post->ID, 'fec_header_image', true );

	}
	if ( $fec_header_image ) {
		$new_content .= '<img src="' . $fec_header_image . '" alt="Album Art" class="alignleft" style="max-width: 150px; max-height: 150px;" />';
	}


	return $content . $new_content;
}