<?php

use HelloNico\Twig\DumpExtension;

/**
 * Register all custom twig functions
 */
add_filter(
	'timber/twig',
	function ( \Twig_Environment $twig ) {
		/**
		 * to add a function to twig, use the following syntax:
		 * $twig->addFunction( new Timber\Twig_Function( 'newsletter_signup', 'newsletter_signup_function' ) );
		 */
		return $twig;
	}
);

/**
 * add dump extension to twig
 * (https://github.com/nlemoine/timber-dump-extension/blob/master/functions.php)
 */
function add_dump_extension( \Twig_Environment $twig ) {
    $twig->addExtension(new DumpExtension());
    return $twig;
};
if (defined('WP_DEBUG') && WP_DEBUG === true && function_exists('add_filter') && class_exists('HelloNico\Twig\DumpExtension')) {
    add_filter('timber/twig', 'add_dump_extension');
}
