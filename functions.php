<?php
require_once __DIR__ . '/vendor/autoload.php';

add_action( 'after_setup_theme', function () {
	Iwf2b\App::get_instance()->bootstrap( __DIR__ . '/app', 'DevLog' );
} );

add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'editor-styles' );

add_editor_style( 'assets/css/style-editor.css' );

register_sidebar( [
	'name'          => __( 'Sidebar', 'devlog' ),
	'id'            => 'sidebar',
	'description'   => '',
	'class'         => '',
	'before_widget' => '<section id="%1$s" class="c-section widget %2$s">',
	'after_widget'  => "</section>\n",
	'before_title'  => '<h2 class="c-section__title">',
	'after_title'   => "</h2>\n",
] );