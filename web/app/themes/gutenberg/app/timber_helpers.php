
<?php

/**
 * This fuctions returns an array with menu items to include in the breadcrumbs
 */
function get_breadcrumb() {
	$context = Timber::get_context();
    $post = new Timber\Post();
    $items = $context['menu']->items;
	$crumbs = [];

    if ($items) {
        foreach ($items as $item) {
            if ($item->current_item_parent
            || $item->current_item_ancestor
            || ($item->current && get_post_type() !== 'page')) {
            $crumbs[] = $item;
            }
        }
    }

    if (is_single()) {
        if (get_post_type() == 'post') {
            // Breadcrumb for an article.
            $job = new Timber\Post();
            $job->post_title = 'Articles';
            $job->url = get_post_type_archive_link('post');
            $crumbs[] = $job;
        }
        if (get_post_type() == 'testimonial') {
            // Breadcrumb for a testimonial.
            $testimonial = new Timber\Post();
            $testimonial->post_title = 'Témoignages';
            $testimonial->url = get_post_type_archive_link('testimonial');
            $crumbs[] = $testimonial;
        }
        if (get_post_type() == 'job') {
            // Breadcrumb for a job.
            $job = new Timber\Post();
            $job->post_title = 'Jobs';
            $job->url = get_post_type_archive_link('job');
            $crumbs[] = $job;
        }

        $page_for_posts = new Timber\Post();
        $page_for_posts->post_title = $post->title;
        $page_for_posts->current = true;
        $crumbs[] = $page_for_posts;
    }

    if (get_post_type() == 'page') {
        $page_for_posts = new Timber\Post();
        $page_for_posts->post_title = $post->title;
        $page_for_posts->current = true;
        $crumbs[] = $page_for_posts;
    }

    if ($post->_wp_page_template == 'views/privacy.php') {
        $page_for_posts = new Timber\Post();
        $page_for_posts->post_title = $post->title;
        $page_for_posts->current = true;
        $crumbs[] = $page_for_posts;
    }

	return $crumbs;
}


/**
 * Dynamic template rendering for ACF flexible content
 * each flexible content is processed, and the corresponding template is loaded
 * acf_fc_template (template name) is used to determine which file is loaded
 * following this pattern: /web/teme/malley-prairie/resources/views/acf/[acf_fc_template].twig.
 *
 * If no file exists, an error message is outputted.
 *
 * @param Twig_Environment $twig
 * @return $twig
 */
add_filter( 'timber/twig', function( \Twig_Environment $twig ) {
    $twig->addFunction( new Timber\Twig_Function( 'render_flexible_content', 'render_flexible_content' ) );
    return $twig;
} );

function render_flexible_content(\Timber\Post $post = null) {
    if(!$post) return null;

    $context = Timber::get_context();
    $fields = $post->get_field('flexible_content');

    if(!$fields) return null;
    foreach($fields as $field) {
        $slug = $field['acf_fc_layout'];

        // define filename and try to render
        $filename = get_stylesheet_directory() . "/views/acf/" . $slug . ".twig";
        $context['acf_template'] = "/resources/views/acf/" . $slug . ".twig";

        if(file_exists($filename)) {
            // Render current post.
            $context['post'] = $post;
            // Render custom field.
            $context['acf'] = $field;

            if(isset($context['acf']['pages'])) {
                foreach ($context['acf']['pages'] as $i => $page) {
                    $thumb = get_post_thumbnail_id($page['page']->ID);
                    if (strlen($thumb) == 0) continue;
                    $image = new Timber\Image($thumb);
                    $context['acf']['pages'][$i]['image'] = $image;
                }
            }
            // render file
            Timber::render( $filename, $context );
        } else {
            $filename = get_stylesheet_directory() . "/views/acf/missing.twig";
            Timber::render( $filename, $context );
        }
    }
}

/**
 * change excerpt length
 * These functions ares used by the smart_excerpt() function
 */
function smart_excerpt_length( $length = 0 ) {
	return 55;
}
add_filter( 'excerpt_length', 'smart_excerpt_length', 9999 );

function smart_excerpt_more( $more = '') {
    return ' (...)';
}
add_filter( 'excerpt_more', 'smart_excerpt_more' );

/**
 * custom function for excerpts. With a given post, returns a formatted excerpt.
 * it will have the same behaviour whether it takes a user-defined excerpt or generates one from the content
 * @arg $post a WP_Post object
 */
function smart_excerpt($post = null) {
    if(!$post) return '';
    $excerpt = $post->post_excerpt ?? '';
	if (strlen($excerpt) == 0 && $excerpt !== '') {
		// custom excerpt is empty, let's generate one
		$excerpt = strip_shortcodes($post->post_content);
		$excerpt = str_replace(array("\r\n", "\r", "\n", "&nbsp;"), "", $excerpt);
		$excerpt = wp_trim_words($excerpt, smart_excerpt_length(), smart_excerpt_more());
	} else {
		// custom excerpt is set, let's trim it
		$excerpt = wp_trim_words($excerpt, smart_excerpt_length(), smart_excerpt_more());
    }
    if ($excerpt === '') {
        $fields = $post->get_field('flexible_content');
        if ($fields) {
            foreach ($fields as $field) {
                if ($field["acf_fc_layout"] === 'subtitle') {
                    $excerpt = wp_trim_words($field['text'], smart_excerpt_length(), smart_excerpt_more());
                }
            }
        }
    }
	return $excerpt;
}
