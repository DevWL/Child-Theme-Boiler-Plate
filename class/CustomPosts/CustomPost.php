<?php

namespace My\Lib\CustomPosts;

/**
 * Custom PostType
 */
class CustomPost
{

    /**
     * Register Shortcode
     *
     * @param string $name Shortcode slug used to trigger shortcode in WP
     */
    public function __construct(string $nameSingular = "item", $namePlurar = "items")
    {
        $this->nameSingular = $nameSingular;
        $this->namePlurar = $namePlurar;
        $this->addAction('init', 'addGutenbergSupportForCustomPost');
    }

    /**
     * Register Custom post Type
     *
     * @see WP register_post_type() function manual
     *
     * @return void
     */
    public function addGutenbergSupportForCustomPost()
    {

        $labels = array(
            'name' => _x(ucfirst($this->nameSingular), 'Post type general name', $this->nameSingular),
            'singular_name' => _x(ucfirst($this->nameSingular), 'Post type singular name', $this->nameSingular),
            'menu_name' => _x(ucfirst($this->namePlurar), 'Admin Menu text', $this->nameSingular),
            'name_admin_bar' => _x(ucfirst($this->nameSingular), 'Add New on Toolbar', $this->nameSingular),
            'add_new' => __('Add New', $this->nameSingular),
            'add_new_item' => __('Add New ' . $this->nameSingular, $this->nameSingular),
            'new_item' => __('New ' . $this->nameSingular, $this->nameSingular),
            'edit_item' => __('Edit ' . $this->nameSingular, $this->nameSingular),
            'view_item' => __('View ' . $this->nameSingular, $this->nameSingular),
            'all_items' => __('All ' . $this->namePlurar, $this->nameSingular),
            'search_items' => __('Search ' . $this->namePlurar, $this->nameSingular),
            'parent_item_colon' => __('Parent ' . $this->namePlurar . ':', $this->nameSingular),
            'not_found' => __('No ' . $this->namePlurar . ' found.', $this->nameSingular),
            'not_found_in_trash' => __('No ' . $this->namePlurar . ' found in Trash.', $this->nameSingular),
            'featured_image' => _x(ucfirst($this->nameSingular) . ' Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', $this->nameSingular),
            'set_featured_image' => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', $this->nameSingular),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', $this->nameSingular),
            'use_featured_image' => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', $this->nameSingular),
            'archives' => _x(ucfirst($this->nameSingular) . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', $this->nameSingular),
            'insert_into_item' => _x('Insert into ' . $this->nameSingular, 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', $this->nameSingular),
            'uploaded_to_this_item' => _x('Uploaded to this ' . $this->nameSingular, 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', $this->nameSingular),
            'filter_items_list' => _x('Filter ' . $this->namePlurar . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', $this->nameSingular),
            'items_list_navigation' => _x(ucfirst($this->namePlurar) . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', $this->nameSingular),
            'items_list' => _x(ucfirst($this->namePlurar) . ' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', $this->nameSingular),
        );
        $args = array(
            'labels' => $labels,
            'description' => ucfirst($this->nameSingular) . ' custom post type.',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $this->nameSingular),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 20,
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'page-attributes'),
            'taxonomies' => array('category', 'post_tag'),
            'show_in_rest' => true,
        );

        register_post_type(
            ucfirst($this->nameSingular),
            $args
        );
    }

    /**
     * Trigger WP add_action function in a class wrapper
     *
     * @param string $hook 
     * @param string $methodName 
     * 
     * @return void
     */
    public function addAction(string $hook, string $methodName)
    {
        add_action($hook, [$this, $methodName]);
    }
}
