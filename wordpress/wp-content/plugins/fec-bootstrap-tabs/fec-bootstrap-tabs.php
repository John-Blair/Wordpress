<?php /*
Plugin Name: FEC Bootstrap Tabs
Plugin URI: http://pluralsight.com/training/TableOfContents/wordpress-theme-framework-bootstrap3
Description: Allows Bootstrap 3's toggleable tabs to be used via shortcodes on a WordPress site.
Version: 0.1
Author: John Blair
Author URI: 
*/

/**
 * Copyright (c) 2014 Chris Reynolds. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

add_action( 'wp_enqueue_scripts', 'ps_bootstrap_tab_js' );
function ps_bootstrap_tab_js() {
	//wp_enqueue_script( 'bs-tab', plugin_dir_url( __FILE__ ) . 'js/tab.js', array( 'jquery', 'bootstrap' ), '3.0.3', true );
}

add_shortcode( 'fec-bs-tablist', 'ps_bootstrap_tabs' );
function ps_bootstrap_tabs( $atts ) {
	extract( shortcode_atts( array(
		'tabs' => '', // a comma-separated array of tabs
		'active' => '' // allows the user to define an active tab
		), $atts )
	);

	$tabs = array();
	$active_tab = null;
	if ( isset($atts['tabs']) ) {
		$tabs = explode( ',', $atts['tabs'] );
		if ( isset($atts['active']) ) {
			$active_tab = sanitize_title( $atts['active'] );
		}
		$tabcount = 0;
		ob_start(); ?>

		<ul class="nav nav-tabs fec-nav-tabs">
			<?php foreach ( $tabs as $tab ) { ?>
			<li <?php if ( 0 == $tabcount && !$active_tab || ( $active_tab && sanitize_title($tab) == $active_tab ) ) { echo 'class="active"'; } ?>><a href="#<?php echo sanitize_title( $tab ); ?>" data-toggle="tab"><?php esc_attr_e( ucwords( $tab ) ); ?></a></li>
			<?php $tabcount++;
			} ?>
		</ul>
		<?php
	}
	return ob_get_clean();
}

add_shortcode( 'fec-bs-tabwrap', 'ps_bootstrap_tabwrap' );
function ps_bootstrap_tabwrap( $atts, $content="" ) {
	return '<div class="tab-content custom-tab-content">' . wp_kses_post( do_shortcode($content) ) . '</div>';
}

add_shortcode( 'fec-bs-tab-content', 'ps_bootstrap_tab_content' );
function ps_bootstrap_tab_content( $atts, $content="" ) {
	extract( shortcode_atts( array(
		'tab' => '', // the name of the tab. must match the name defined in the bs-tablist shortcode
		'active' => '' // whether this is the active tab. anything here is an accepted value
		), $atts )
	);

	if ( isset($atts['active']) ) {
		$active = 'in active';
	} else {
		$active = false;
	}

	$tab = null;
	if ( isset($atts['tab']) ) {
		$tab = sanitize_title( $atts['tab'] );
	}
	ob_start(); ?>

	<div class="tab-pane fade <?php echo $active; ?>" id="<?php echo $tab; ?>">
		<?php echo wp_kses_post( $content ); ?>
	</div>

	<?php
	return ob_get_clean();
}