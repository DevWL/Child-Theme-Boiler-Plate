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

use My\Lib\ScriptsEnqueue\EnqueScript;
use My\Lib\ScriptsEnqueue\EnqueStyle;
use My\Lib\CustomPosts\CustomPost;

/* Enqueue parent style using custom class wrapper*/
(new EnqueStyle(
    'parent-style',
    get_template_directory_uri() . '/style.css'
))->setVersion(1.1)->enqueue();

/* Enqueue child theme front css styles */
(new EnqueStyle(
    'child-style',
    get_stylesheet_directory_uri() . '/assets/css/custom-front.css',
    ['parent-style'],
    1.1,
    'all'
))->setDisableOnAdmin()->enqueue();

/* Enqueue child theme admin css styles */
(new EnqueStyle(
    'child-admin-style',
    get_stylesheet_directory_uri() . '/assets/css/custom-admin.css',
    ['parent-style'],
    1.1,
    'all'
))->setDisableOnFront()->enqueue();

/* Enqueue js scripts */
(new EnqueScript(
    'script', // label
    get_stylesheet_directory_uri() . '/assets/js/custom.js', // link to a js file 
    ['jquery'],  // scripts to be loaded before
    1.0, // version
    true // 
))->setVersion(1.1)->setDisableOnAdmin()->enqueue();

new CustomPost();