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

use My\Lib\Helpers\WpForms\Form;
use My\Lib\CustomPosts\CustomPost;
use My\Lib\Helpers\WpDebug\DebugWp;
use My\Lib\AdminPages\AdminMenuPage;
use My\Lib\ScriptsEnqueue\EnqueStyle;
use My\Lib\ScriptsEnqueue\EnqueScript;
use My\Lib\Helpers\WpEnchancments\AllowSVG;

const MAINDIR = __DIR__;

/* Enable Debuging */
(new DebugWp())->on();

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

/* Register custom post */
new CustomPost();

/* Add Top Level Admin Menu Page */
$adminPage = new AdminMenuPage("Child Theme Admin", "child_theme_admin", "My Settings");
$adminPage->iconUrl = get_stylesheet_directory_uri(). '/assets/images/icons/wp-icon.png';
$adminPage->position = 110;
$adminPage->renderPage(
    get_stylesheet_directory().'/View/Admin/main-setting-home.php', 
    []
);

/* Add Top Level Admin Menu Page shared subpage */
$adminSubPage1 = new AdminMenuPage("Child Theme Admin", "$adminPage->slug", "Home", $adminPage->slug);

/* Add Sub Page Admin Menu - Level down Page and Content */
$adminSubPage2 = new AdminMenuPage("Theme Admin", "child_theme_admin-settings", "Basics", $adminPage->slug);

$adminSubPage2->renderPage(
    get_stylesheet_directory().'/View/Admin/main-setting-basics.php',
    [
        "demoSlug" => $adminSubPage2->slug,
        "form" => new Form($adminSubPage2->slug)
    ]
);

/* Allow SVG Upload and Display */
new AllowSVG();

