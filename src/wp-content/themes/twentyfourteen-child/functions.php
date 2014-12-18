<?php

define('PAGE_TOP_LEVEL_ID', 0);

/**
 * Remove all unnecessary image sizes from media library
 */
function filter_image_sizes( $sizes ){
		
	unset( $sizes[ 'thumbnail' ]);
	unset( $sizes[ 'medium' ]);
	unset( $sizes[ 'large' ]);
	unset( $sizes[ 'post-thumbnail' ]);
	unset( $sizes[ 'twentyfourteen-full-width' ]);
	return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'filter_image_sizes' );


/**
 * Global function to retrieve the relative child template directory
 */
function get_child_template_directory_uri() {
	return get_stylesheet_directory_uri();
}

/**
 * Remove initial parent theme load scripts function
 */
function remove_twentyfourteen_scripts() {
	remove_action('wp_enqueue_scripts', 'twentyfourteen_scripts');
}

/**
 * main theme asset hook
 * register assets
 */
function twentyfourteen_child_scripts() {

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style('genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.2');

	// Load our main stylesheet.
	wp_enqueue_style('twentyfourteen-style', get_template_directory_uri() . '/style.css');
	wp_enqueue_style('twentyfourteen-child-style', get_stylesheet_uri());

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style('twentyfourteen-ie', get_template_directory_uri() . '/css/ie.css', array('twentyfourteen-style', 'genericons'), '20141010');
	wp_style_add_data('twentyfourteen-ie', 'conditional', 'lt IE 9');

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style('twentyfourteen-ie7', get_template_directory_uri() . '/css/ie7.css', array('twentyfourteen-style'), '20141010');
	wp_style_add_data('twentyfourteen-ie7', 'conditional', 'lt IE 8');

	//wp_enqueue_script('twentyfourteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', TRUE);

	if (is_singular() && wp_attachment_is_image()) {
		wp_enqueue_script('twentyfourteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array('jquery'), '20141010');
	}

	// Load javascript libraries (i.e. jQuery, Headroom.js, etc.)
	wp_enqueue_script('javascript-libraries', get_child_template_directory_uri() . '/js/libs.js', array(), '1.0.0', TRUE);

	// Load custom javascript scripts
	wp_enqueue_script('custom-scripts', get_child_template_directory_uri() . '/js/scripts.js', array(), '1.0.0', TRUE);
}

/**
 * enable livereload on localhost
 */
function livereload() {

	if( in_array( $_SERVER[ 'REMOTE_ADDR' ], array( '127.0.0.1', '::1' ))){

		if( gethostname() != 'monarch.local' ){

			wp_register_script( 'livereload', 'http://localhost:35729/livereload.js?snipver=1', NULL, FALSE, TRUE );
			wp_enqueue_script( 'livereload' );
		}
	}
}

/**
 * render menu - to be used on main page and overlay
 */
function render_menu() {

	$arguments = array(
		'menu' 			  => 'Navigation',
		'container_class' => 'container menu-items',
		'items_wrap'	  => '%3$s',
		'depth'			  => 0,
		'walker'		  => new Menu_Walker()
	);
	return wp_nav_menu( $arguments );
}

/**
 * Custom Walker_Nav_Menu to walk through navigation setup in back-end
 */
class Menu_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = array() ){

		if( $depth == 0 ){

			$output .= '<div class="col-md-2"><ul class="menu-item-list">';
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ){

		if( $depth == 0 ){

			$output .= '</ul></div>';
		}
	}

	public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ){

		if( $depth == 0 ){

			$output .= sprintf( '<hr/><div class="row"><div class="col-md-4"><h1 class="menu-item-top"><a href="%s" class="%s">%s</a></h1></div>', esc_attr( $object->url ), implode( ' ', $object->classes ), esc_attr( $object->title ));
		}

		if( $depth == 1 ){

			$output .= sprintf( '<li><a href="%s" class="%s">%s</a></li>', esc_attr( $object->url ), implode( ' ', $object->classes ), esc_attr( $object->title ));
		}
	}

	public function end_el( &$output, $object, $depth = 0, $args = array() ){

		if( $depth == 0 ){

			$output .= '</div>';
		}
	}
}




/*
 *  Rewrite the query URL to support anchors within parent pages
 */

function rewrite_permalink(){

	$page 	= get_queried_object();
	$parent = get_post( $page->post_parent )->post_name;
	$child 	= $page->post_name;

	if( $parent && $parent != '' && $child && $child != '' && $parent != $child ){

		$permalink = get_bloginfo( 'url' ) . '/' . $parent . '/#' . $child;
		wp_redirect( $permalink, 301 );
	}
}
add_action( 'get_header', 'rewrite_permalink', 0 );



/**
* get link metadata for page hierarchy
* @returns array
*/
function sandvik_get_page_hierarchy( $parent_id = -1 ){

	// store in static cache for performance
	static $menu = array();
	if ( empty( $menu[$parent_id] ) ) {
		
		$args = array(
			'sort_order' => 'ASC',
			'sort_column' => 'menu_order, post_title',
			'hierarchical' => 1,
			'exclude' => '2',
			'include' => '',
			'meta_key' => '',
			'meta_value' => '',
			'authors' => '',
			'child_of' => 0,
			'parent' => $parent_id,
			'exclude_tree' => '',
			'number' => '',
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish'
		);

		$pages = get_pages( $args );

		// transform the array of all page data into a hierarchical array of menu link metadata
		$menu[$parent_id] = sandvik_get_page_metadata($pages);
	}
	
	return $menu[$parent_id];
}

/**
* parse page data into a useful array
* @returns array
*/
function sandvik_get_page_metadata( &$pages ){

	$arr = array();

	foreach( $pages as $page_metadata ){

		$arr[ $page_metadata->post_parent ][ $page_metadata->ID ] = array(
			'post_id' 		=> $page_metadata->ID,
			'post_title'	=> $page_metadata->post_title,
			'post_name'		=> $page_metadata->post_name,
			'post_parent'	=> $page_metadata->post_parent,
			'guid'			=> $page_metadata->guid,
		);
	}
	
	return $arr;
}


/**
*
*/
function sandvik_get_top_level_pager() {
	$current_page_id = get_the_ID();
	$current_page_parents = get_post_ancestors($current_page_id);
	$current_page_parent = reset($current_page_parents);

	if (empty($current_page_parent)) {
		$current_page_parent = $current_page_id;
	}

	// fetch metadata for top-level pages
	$pages = sandvik_get_page_hierarchy(PAGE_TOP_LEVEL_ID);

	// reindex top-level array from 0
	$pages = array_values(reset($pages));
	
	foreach ($pages AS $offset => $page_metadata) {
		if ($page_metadata['post_id'] == $current_page_id) {
			
			if ($offset > 0) {
				$previous = array_slice($pages, $offset - 1, 1);
				$previous = reset($previous); // we just want the element
			} else {
				$previous = array();
			}
			$next = array_slice($pages, $offset + 1, 1);
			$next = reset($next); // we just want the element

			return compact('previous', 'next');
		}
	}
	
	return array(); // no results
}




/*
* get featured image url
*/
function get_featured_image_as_background( $post_id ) {

	$featured_image_url = wp_get_attachment_url( get_post_thumbnail_id( $post_id ));
	return sprintf( 'style="background-image:url(\'%s\');"', $featured_image_url );
}



/*
* Render multipe rows with multiple small sized images and additional captions
*/

function render_small_images( $id ){

	$limit 		= 4;
	$classes 	= "col-md-2";
	$collection = simple_fields_fieldgroup( 'small_images', $id );
	$rows 		= get_collection_in_rows( $collection, $limit );
	$html 		= render_row_columns( $rows, $limit, $classes );

	return $html;
}


/*
* Render multipe rows with multiple medium sized images and additional captions
*/

function render_medium_images( $id ){

	$limit 		= 2;
	$classes 	= "col-md-4";
	$collection = simple_fields_fieldgroup( 'medium_images', $id );
	$rows 		= get_collection_in_rows( $collection, $limit );
	$html 		= render_row_columns( $rows, $limit, $classes );

	return $html;
}


/*
* Render multipe rows with multiple large sized images and additional captions
*/

function render_large_images( $id ){

	$limit 		= 1;
	$classes 	= "col-md-8";
	$collection = simple_fields_fieldgroup( 'large_images', $id );
	$rows 		= get_collection_in_rows( $collection, $limit );
	$html 		= render_row_columns( $rows, $limit, $classes );

	return $html;	
}


/*
* Nest an array within a new array within params limit increments
*/

function get_collection_in_rows( $collection, $limit ){

	$rows  = [];
	$index = 0;
	$count = 0;

	foreach( $collection as $item ){

		$rows[ $index ][] = $item;

		if( $count % $limit == $limit - 1 ){
			$index += 1;
		}

		$count += 1;
	}
	return $rows;
}


/*
* Render the actual rows and columns with spacing to the right side
*/

function render_row_columns( $rows, $limit, $classes ){

	$html = '';

	foreach( $rows as $row ){

		$html .= '<div class="row">';

		$count = $limit - count( $row );
		while( $count-- ){

			$html .= sprintf( '<div class ="%s"></div>', $classes );
		}

		foreach( $row as $column ){

			$html .= sprintf( '<div class="%s">%s', $classes, $column[ 'image' ][ 'link' ][ 'full' ]);

			if( $column[ 'caption' ] != '' ){

				$html .= sprintf( '<p class="entry-caption">%s</p>', nl2br( $column[ 'caption' ]));
			}

			$html .= '</div>';
		}

		$html .= '</div>';
	}

	return $html;	
}



/*
* Render the download section within a page, chapter or paragraph
*/

function render_download( $id ){

	$download = simple_fields_value( 'file', $id );

	if( $download ){

		$html = sprintf( '<a href="%s" target="_blank" class="hidden-sm hidden-xs"><i class="icon icon_download-icon"></i> <span class="label">Download Section</span></a>', $download[ 'url' ]);
		return $html;		
	}
}



/*
 * Don't know what this does..:S
 */
function mydie( $obj ){

	die( sprintf( '<pre style="%s">%s</pre>', "width:100%;background-color:#fff;color:#111;", json_encode( $obj, JSON_PRETTY_PRINT )));
}



/**
 * register hooks
 */
add_action( 'init', 'remove_twentyfourteen_scripts' );
add_action( 'wp_enqueue_scripts', 'twentyfourteen_child_scripts' );
add_action( 'wp_enqueue_scripts', 'livereload' );

