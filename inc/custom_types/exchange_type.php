<?php
/**
 * Custom Exchange post type
 */

function lauch_exchanges_init() {
    $labels = array(
        'name'                  => _x( 'Austausche', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Austausch', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'Austausch', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Austausch', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Neuen hinzufügen', 'textdomain' ),
        'add_new_item'          => __( 'Add New Exchange', 'textdomain' ),
        'new_item'              => __( 'New Exchange', 'textdomain' ),
        'edit_item'             => __( 'Austausch bearbeiten', 'textdomain' ),
        'view_item'             => __( 'Austausch ansehen', 'textdomain' ),
        'all_items'             => __( 'Alle Austausche', 'textdomain' ),
        'search_items'          => __( 'Search Exchanges', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Exchanges:', 'textdomain' ),
        'not_found'             => __( 'No events found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No events found in Trash.', 'textdomain' ),
        'featured_image'        => _x( 'Exchange Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'archives'              => _x( 'Exchange archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
        'insert_into_item'      => _x( 'Insert into event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this event', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
        'filter_items_list'     => _x( 'Filter events list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
        'items_list_navigation' => _x( 'Exchanges list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
        'items_list'            => _x( 'Exchanges list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'austausch' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'custom-fields', 'page-attributes', 'thumbnail' ),
        'taxonomies'         => array('exchange-program', 'year'),
    );

    register_post_type( 'exchange', $args );
}

add_action( 'init', 'lauch_exchanges_init' );
