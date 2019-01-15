<?php
// see https://codex.wordpress.org/Function_Reference/register_post_type

function create_example() {

  $args = [
    'labels' => [
      'name' => __( 'Examples' ),
      'singular_name' => __( 'example' )
    ],
    'public' => true,
    'has_archive' => true,
    'menu_icon' => 'dashicons-calendar'
  ];

  register_post_type( 'example',
    $args
    );


  register_taxonomy(
    'example_category',
    'example',
    array(
      'label' => __( 'Exemple de taxonomie', 'lang' ),
      'public' => true,
      'rewrite'     => ['slug' => 'example/slug'],
      'hierarchical' => true,
    )
  );
}
add_action( 'init', 'create_example' );

/**
 * Hide default taxonomy metabox
 */
add_action( 'admin_menu', function () {
  $cpt = 'example';
  $taxonomy = 'example_category';
	remove_meta_box( 'tagsdiv-'.$taxonomy, $cpt, 'side' );
} );

/**
 * Alter default ordering using ACF
 */
add_action(
	'pre_get_posts',
	function ($query){
		if(is_archive() && is_post_type_archive( 'example' )):
				$query->set( 'meta_key', 'example_acf_slug' );
				$query->set( 'orderby', ['meta_value' => 'DESC'] );
		endif;
	}
);
