<?php /*
Plugin Name: FEC Bootstrap Accordion
Plugin URI: http://pluralsight.com/training/accordionleOfContents/wordpress-theme-framework-bootstrap3
Description: Allows Bootstrap 3's toggleable accordion to be used via shortcodes on a WordPress site.
Version: 0.1
Author: John Blair
Author URI: http://theblairs.azurewebsites.net/
*/

/**
 * Copyright (c) 2018 John Blair. All rights reserved.
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
 * MERCHANaccordionILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */


/*
<div class="panel-group fec-accordion" id="accordion">

  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse"  data-parent="accordion" href="#one">
           Title
        </a>
      </h4>
    </div>
    <div id="one" class="panel-collapse collapse in">
      <div class="panel-body">
       content
      </div>
    </div>
  </div>
  
  
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle collapsed" data-toggle="collapse"  data-parent="accordion" href="#one">
           Title
        </a>
      </h4>
    </div>
    <div id="one" class="panel-collapse collapse">
      <div class="panel-body">
       content
      </div>
    </div>
  </div>

</div>
*/
add_action( 'wp_enqueue_scripts', 'ps_bootstrap_accordion_js' );
function ps_bootstrap_accordion_js() {
	wp_enqueue_script( 'bs-accordion', plugin_dir_url( __FILE__ ) . 'js/bootstrap-accordion.js', array( 'jquery', 'bootstrap' ), '3.0.3', true );
}

// Accordion wrapper.
// [fec-bs-accordionwrap id="accordion1"]
add_shortcode( 'fec-bs-accordionwrap', 'ps_bootstrap_accordionwrap' );
function ps_bootstrap_accordionwrap( $atts, $content="" ) {
  extract( shortcode_atts( array(
		  'id' => 'accordion'
		  ), $atts )
	  );
	return '<div class="panel-group fec-accordion" id="' . $atts['id'] . '">' . do_shortcode($content)  .'</div>';
}


// Accordion Content
// [fec-bs-accordion-content title="..." parentId="accordion1" id="one" active=".."] content [/fec-bs-accordion-content]
add_shortcode( 'fec-bs-accordion-content', 'ps_bootstrap_accordion_content' );
function ps_bootstrap_accordion_content( $atts, $content="" ) {
	extract( shortcode_atts( array(
		'parentid' => 'accordion', // the name of the accordion. must match the name defined in the bs-accordionlist shortcode
		'active' => '', // whether this is the active accordion. anything here is an accepted value
    'id' => 'collapseOne',
    'title' => 'Click Me'
		), $atts )
	);

	if ( isset($atts['active']) ) {
    // Open
		$active = 'in';
    $collapsed = 'open';  // Active one is open.
	} else {
		$active = '';
    $collapsed = 'collapsed';
	}
  $id=$atts['id'];
  $parentId=$atts['parentid'];
	$title = null;
	if ( isset($atts['title']) ) {
		$title = sanitize_title( $atts['title'] );
	}
	ob_start(); ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a class="accordion-toggle <?php echo $collapsed; ?>" data-toggle="collapse"  data-parent=<?php echo "$parentId"; ?> href="#<?php echo $id; ?>">
        <?php echo $title; ?>
      </a>
    </h4>
  </div>
  <div id="<?php echo $id; ?>" class="panel-collapse collapse <?php echo $active; ?>">
    <div class="panel-body">
      <?php echo wp_kses_post( $content ); ?>
    </div>
  </div>
</div>
<?php
	return ob_get_clean();
}


