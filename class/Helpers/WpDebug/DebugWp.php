<?php

namespace My\Lib\Helpers\WpDebug;

use My\Lib\Helpers\BaseWpAbstarct;

/**
 * Anables debug!
 */
class DebugWp extends BaseWpAbstarct
{

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->addAction("wp_head", "showQuery", PHP_INT_MAX);
    }

    /**
     * On debuging if not defined in wp-config
     *
     * @return void
     */
    public function on()
    {
        // Enable WP_DEBUG mode
        if(!defined('WP_DEBUG')) define('WP_DEBUG', true);

        // Enable Debug logging to the /wp-content/debug.log file
        if(!defined('WP_DEBUG_LOG')) define('WP_DEBUG_LOG', true);

        // Disable display of errors and warnings
        if(!defined('WP_DEBUG_DISPLAY')) define('WP_DEBUG_DISPLAY', true);
        @ini_set('display_errors', 1);
        @ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
        if(!defined('SCRIPT_DEBUG')) define('SCRIPT_DEBUG', true);
        if(!defined('SAVEQUERIES')) define('SAVEQUERIES', true);

        return $this;
    }

    /**
     * Off Error raporting in php ini if ebabled
     *
     * @return void
     */
    public function off()
    {
        @ini_set('display_errors', 0);
        @ini_set('display_startup_errors', 0);
        error_reporting(0);

        return $this;
    }

    /**
     * Show last SQL queries
     *
     * @return void
     */
    public function showQuery()
    {
        global $wpdb;
        echo "<pre style='background: white;'>";
        var_dump($wpdb->num_queries, $wpdb->queries);
        echo "</pre>";
    }
}
