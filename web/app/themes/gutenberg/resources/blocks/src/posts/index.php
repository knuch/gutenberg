<?php

function as_blocks_posts_render( $attributes ) {
	$timberPosts = [];
	$args = array(
    'post_type' => 'post',
	);
	$posts = Timber::get_posts($args);
	$context = Timber::get_context();
	$context['posts'] = $posts;
	$context['title'] = $attributes['content'];
	$output = Timber::compile( 'view.twig', $context );
	return $output;
}

function as_blocks_register_post() {
	register_block_type( 'as-blocks/posts', array(
		'attributes' => array(
			'content' => array(
				'type' => 'string',
			)
		),
		'render_callback' => 'as_blocks_posts_render',
	) );
}

add_action( 'init', 'as_blocks_register_post' );
