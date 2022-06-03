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
// use My\Lib\Helpers\WpDebug\DebugWp;
use My\Lib\AdminPages\AdminMenuPage;
// use My\Lib\ShortCodes\NameShortcode;
use My\Lib\ScriptsEnqueue\EnqueStyle;
use My\Lib\ScriptsEnqueue\EnqueScript;
use My\Lib\ShortCodes\GetOptionShortcode;
use My\Lib\Helpers\WpEnchancments\AllowSVG;

const MAINDIR = __DIR__;

/* Enable Debuging */
// (new DebugWp())->on();

/* *********************************** CSS ENQUEUE SCRIPTS *************************************** */

/* Enqueue parent style using custom class wrapper*/
(new EnqueStyle(
    'parent-style',
    get_template_directory_uri() . '/style.css'
))->setVersion(1.1)->enqueueOnFront()->add();

/* Enqueue css styles on frontend */
(new EnqueStyle(
    'child-style',
    get_stylesheet_directory_uri() . '/assets/css/custom-front.css',
    ['parent-style'],
    1.1,
    'all'
))->enqueueOnFront()->add();

/* Enqueue css styles in admin area */
(new EnqueStyle(
    'child-admin-style',
    get_stylesheet_directory_uri() . '/assets/css/custom-admin.css',
    [],
    1.1,
    'all'
))->enqueueOnAdmin()->add();

/* 
    Enqueue CSS styles 
    on specyfic ADMIN page 
*/
$adminSettingsStyles = (new EnqueStyle(
    'admin-settings',
    get_stylesheet_directory_uri() . '/assets/css/custom-admin-settings.css',
    [],
    1.1,
    'all'
))->enqueueOnAdmin()
    ->enqueueOnFront()
    ->enqueueOnSpecyficPage(["child_theme_admin-settings", "kontakt"])
    ->excludeFromSpecyficPage(["kontakt"])
    ->add();

/* 
    Enqueue CSS styles 
    on specyfic FRONT page 
*/
$kontaktStyles = new EnqueStyle(
    'kontakt',
    get_stylesheet_directory_uri() . '/assets/css/custom-kontakt.css',
    [],
    1.1,
    'all'
);
$kontaktStyles->enqueueOnSpecyficPage(["kontakt"])
    ->add();

/* 
    Enqueue CSS styles 
    on specyfic ADMIN page 
*/
$blogStepInPathStyles = (new EnqueStyle(
    'blogsteps',
    get_stylesheet_directory_uri() . '/assets/css/custom-blog--.css',
    [],
    1.1,
    'all'
))->enqueueOnSpecyficPage(["blog"]) // /blog
    ->enqueueIfStepInPath(["blog"]) // all posts prepend with /blog/xyz...
    ->add();

/* *********************************** JS ENQUEUE SCRIPTS *************************************** */
    
/* Enqueue js scripts */
(new EnqueScript(
    'script', // label
    get_stylesheet_directory_uri() . '/assets/js/custom.js', // link to a js file 
    ['jquery'],  // scripts to be loaded before
    1.0, // version
    true // 
))->setVersion(1.1)->setDisableOnAdmin()->enqueue();

/* *********************************** REGISTER USTOM POSTS *************************************** */
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

/* *********************************** ADD ADMIN SETTING PAGES *************************************** */
/* Add Top Level Admin Menu Page shared subpage */
$adminSubPage1 = new AdminMenuPage("Child Theme Admin", "$adminPage->slug", "Home", $adminPage->slug);

/* Add Sub Page Admin Menu - Level down Page and Content */
$adminSubPage2 = new AdminMenuPage("Theme Admin", "child_theme_admin-settings", "Basics", $adminPage->slug);

$adminSubPage2->renderPage(
    get_stylesheet_directory().'/View/Admin/main-setting-basics.php',
    [
        "enqueue" => [],
        "demoSlug" => $adminSubPage2->slug,
        "form" => new Form($adminSubPage2->slug)
    ]
);

/* *********************************** REGISTER SHORTCODES *************************************** */
/* Allow SVG Upload and Display */
new AllowSVG();

// Register shortcodes for settings options
new GetOptionShortcode("first_name");
new GetOptionShortcode("last_name");
new GetOptionShortcode("email");
new GetOptionShortcode("emailCC");
new GetOptionShortcode("phone");
new GetOptionShortcode("company_name");
new GetOptionShortcode("address1");
new GetOptionShortcode("address2");
new GetOptionShortcode("postcode");
new GetOptionShortcode("city");
new GetOptionShortcode("nip");
new GetOptionShortcode("bank_name");
new GetOptionShortcode("bank_account_num");