<?php
/**
 * Divi-child Theme functions and definitions.
 *
 * @category Child-theme
 * @package  Divi-child
 * @author   DevWL <w.liszkiewicz@gmail.com>
 * @license  all rights reserve
 * @link     https://developer.wordpress.org/themes/basics/theme-functions/
 */

require 'vendor/autoload.php';

use My\Lib\ShortCode\GalleryShortCode;

add_action('wp_enqueue_scripts', 'divi_parent_theme_enqueue_styles');

/**
 * Enqueue scripts and styles.
 * 
 * @return void
 */
function divi_parent_theme_enqueue_styles()
{

    /*
     * INFO Enque Js file
     * wp_enqueue_style( $handle, $src, $deps, $ver, $media );
     * 
     * @param $handle is simply the name of the stylesheet.
     * @param $src is where it is located. The rest of the parameters are optional.
     * @param $deps refers to whether or not this stylesheet is dependent on another stylesheet. If this is set, this stylesheet will not be loaded unless its dependent stylesheet is loaded first.
     * @param string|bool|null $ver sets the version number.
     * @param string $media can specify which type of media to load this stylesheet in, such as ‘all’, ‘screen’, ‘print’ or ‘handheld.’
     * 
     * @return mixed
     */
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        ['parent-style'],
        1.0,
        'all'
    );
    
    /*
    * wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all')
    *
    * Registers the script if $src provided (does NOT overwrite), and enqueues it.
    *
    * @param string $handle — Name of the script. Should be unique.
    * @param string $src (Default empty string) Full URL of the script, or path of the script relative to the WordPress root directory.
    * @param string[] $deps (Default empty array.) Optional. An array of registered script handles this script depends on. 
    * @param string|bool|null $ver (Optional). String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
    * @param bool $in_footer . Default 'false'. (Optional). Whether to enqueue the script before instead of in the 
    * 
    * @return mixed
    */
    wp_enqueue_script( 
        'script', // label
        get_stylesheet_directory_uri() . '/assets/js/custom.js', // link to a js file 
        ['jquery'],  // scripts to be loaded before
        1.0, // version
        true // 
    );

}

new GalleryShortCode("demo");