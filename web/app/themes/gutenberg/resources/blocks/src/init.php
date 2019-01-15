<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function my_block_cgb_block_assets() { // phpcs:ignore
	// Styles.
	wp_enqueue_style(
		'my_block-cgb-style-css', // Handle.
		get_template_directory_uri().'/blocks/dist/blocks.style.build.css', // Block style CSS.
		array( 'wp-editor' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);
}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'my_block_cgb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function my_block_cgb_editor_assets() { // phpcs:ignore
	// Scripts.
	wp_enqueue_script(
		'my_block-cgb-block-js', // Handle.
		get_template_directory_uri().'/blocks/dist/blocks.build.js', // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_enqueue_style(
		'my_block-cgb-block-editor-css', // Handle.
		get_template_directory_uri().'/blocks/dist/blocks.editor.build.css', // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'my_block_cgb_editor_assets' );
/**
 * Autoload all blocks
 */
$blocks = glob(plugin_dir_path(dirname(__FILE__)).'/src/*/index.php');
foreach ($blocks as $block) {
  require_once $block;
}

/**
 * Disable some default blocks
 * https://rudrastyh.com/gutenberg/remove-default-blocks.html
 */
add_filter( 'allowed_block_types', 'as_blocks_allowed_block_types' );

function as_blocks_allowed_block_types( $allowed_blocks, $post = null ) {

/*    	$allowed_blocks = array(
		'core/paragraph',
		'core/image',
		'core/heading',
		'core/subhead',
		'core/list',
		'core/quote',
		'core/video',
		'core/table',
		'as-blocks/cat'
	); */

/*   $allowed_blocks = array(
		'as-blocks/dynamic',
		'as-blocks/posts',
		'as-blocks/cat'
	); */

	if( $post && $post->post_type === 'page' ) {
		$allowed_blocks[] = 'core/shortcode';
	}

	return $allowed_blocks;
}
