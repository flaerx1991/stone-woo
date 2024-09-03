<?php
function stone_post_types() { 
    register_post_type( 'dealers', 
        array(
            'labels' => array(
                'name' => __( 'Dealers' ), 
                'singular_name' => __( 'Dealer' ), 
                'menu_name' => 'Dealers'
            ),
            'show_in_rest' => true,
            'public' => true,
			'publicly_queryable' => false,
            'menu_position' => 5, 
            'supports' => array('title') 
        )
    );
}
add_action( 'init', 'stone_post_types' );

function register_custom_taxonomy() {
	$labels = array(
		'name' => _x( 'Collections', 'taxonomy general name' ),
		'singular_name' => _x( 'Collection', 'taxonomy singular name' ),
		'search_items' => __( 'Search Collection' ),
		'all_items' => __( 'All Collections' ),
		'parent_item' => __( 'Parent Collection' ),
		'parent_item_colon' => __( 'Parent Collection:' ),
		'edit_item' => __( 'Edit Collection' ),
		'update_item' => __( 'Update Collection' ),
		'add_new_item' => __( 'Add New Collection' ),
		'new_item_name' => __( 'New Collection' ),
		'menu_name' => __( 'Collections' ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'public' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'collection' ),
		'show_in_menu' => true,
	);
	register_taxonomy( 'collection', array( 'product', 'projects' ), $args );
	
	register_taxonomy( 'dealer_location', array('dealers'), [
        'label'                 => '', 
        'labels'                => [
            'name'              => 'Locations',
            'singular_name'     => 'Location',
            'menu_name'         => 'Locations',
        ],
        'description'           => '', 
        'public'                => true,
        'hierarchical'          => false,
		'publicly_queryable'	=> false,
        'capabilities'          => array(),
        'meta_box_cb'           => 'post_categories_meta_box', 
        'show_admin_column'     => true,
    ] );
}

add_action( 'init', 'register_custom_taxonomy' );
?>