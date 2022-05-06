<?php

namespace My\Lib\ShortCode;

/**
 * Custom Shortcode
 */
class GalleryShortCode
{

    /**
     * Register Shortcode
     *
     * @param string $name Shortcode slug used to trigger shortcode in WP
     */
    public function __construct(string $name = "custom")
    {
        $this->name =  $name;
        $this->addAction('init', 'addGutenbergSupportForCustomPost');
    }

    /**
     * Undocumented function 
     *
     * @param string $customPostName 
     * 
     * @return void
     */
    public function addGutenbergSupportForCustomPost(string $customPostName = "demo")
    {
        register_post_type( $customPostName,
            // WordPress CPT Options Start
            array(
                'labels' => array(
                    'name' => __( $customPostName ),
                    'singular_name' => __( $customPostName )
                ),
                'has_archive' => true,
                'public' => true,
                'rewrite' => array('slug' => $customPostName),
                'show_in_rest' => true,
                'supports' => array('editor')
            )
        );
    }

    private function addAction($hook, $methodName)
    {
        add_action( $hook, [$this, $methodName] );
    }
}
