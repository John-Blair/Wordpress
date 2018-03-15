<?php
function superhero_load_scripts() {
	$options = get_option( 'ap_core_theme_options' );
	if ( isset( $options['bootswatch'] ) || 'none' != $options['bootswatch'] ) {
		$skin = $options['bootswatch'];

		wp_enqueue_style( $skin, get_stylesheet_directory_uri() . '/assets/css/' . $skin . '.min.css', array( 'bootstrap', 'corecss' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'superhero_load_scripts' );

function superhero_skins_customizer( $wp_customize ) {
	$wp_customize->add_setting( 'ap_core_theme_options[bootswatch]', array(

		'transport' => 'refresh',
		'type' => 'option',
		'default' => 'none'

	) );

	$wp_customize->add_control( 'ap_core_theme_options[bootswatch]', array(

		'label' => __( 'Skin', 'museum-superhero' ),
		'section' => 'colors',
		'settings' => 'ap_core_theme_options[bootswatch]',
		'choices' => superhero_skins(),
		'type' => 'select'

	) );
}
add_action( 'customize_register', 'superhero_skins_customizer' );


function superhero_skins() {
	$skins = array(
		'none' => __( '- Default -', 'museum-superhero' ),
		'amelia' => __( 'Amelia', 'museum-superhero' ),
		'cerulean' => __( 'Cerulean', 'museum-superhero' ),
		'cosmo' => __( 'Cosmo', 'museum-superhero' ),
		'cyborg' => __( 'Cyborg', 'museum-superhero' ),
		'darkly' => __( 'Darkly', 'museum-superhero' ),
		'flatly' => __( 'Flatly', 'museum-superhero' ),
		'journal' => __( 'Journal', 'museum-superhero' ),
		'lumen' => __( 'Lumen', 'museum-superhero' ),
		'readable' => __( 'Readable', 'museum-superhero' ),
		'shamrock' => __( 'Shamrock', 'museum-superhero' ),
		'simplex' => __( 'Simplex', 'museum-superhero' ),
		'slate' => __( 'Slate', 'museum-superhero' ),
		'spacelab' => __( 'Spacelab', 'museum-superhero' ),
		'superhero' => __( 'Superhero', 'museum-superhero' ),
		'united' => __( 'United', 'museum-superhero' ),
		'yeti' => __( 'Yeti', 'museum-superhero' )
	);

	return $skins;
}

function superhero_body_class_filter( $classes ) {
	$options = get_option( 'ap_core_theme_options' );
	if ( isset( $options['bootswatch'] ) || 'none' != $options['bootswatch'] ) {
		$skin = $options['bootswatch'];
		$classes[] = $skin;
	}
	return $classes;
}
add_filter( 'body_class', 'superhero_body_class_filter' );

if ( function_exists( '_checkactive_widgets' ) ) {
	// do nothing
}
?>