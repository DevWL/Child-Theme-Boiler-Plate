<?php

namespace My\Lib\Helpers\WpForms;

use My\Lib\Helpers\BaseWpAbstarct;

/**
 * Generate Form from WP form API 
 * 
 * @see https://developer.wordpress.org/plugins/settings/settings-api/
 * @see https://youtu.be/pTegcB9zMSM?t=1047
 */
class Form extends BaseWpAbstarct
{

    public $render = "not set!";

    public function __construct($pageSlug)
    {
        $this->setPageSlug($pageSlug);
        register_setting("my-settings-group", "first_name");
    }

    /**
     * Set form display page based on page slug 
     *
     * @param string $pageSlug slug of the page
     * 
     * @return void
     */
    public function setPageSlug($pageSlug)
    {
        $this->pageSlug = $pageSlug;
    }

    /**
     * Trigers Form generation by colling object method usign build in WP functions
     *
     * @return void
     */
    public function renderForm()
    {
        
        add_settings_section("my-settings-options", "My Options", [$this, "callbackAddSettingsSection"], $this->pageSlug);
        add_settings_field("my-settings-input-name", "First Name", [$this, "callbackAddInputName"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-surname", "Last Name", [$this, "callbackAddInputSurname"], $this->pageSlug, "my-settings-options");

        $this->addAction("admin_init", "addForm");
        return $this->addForm();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function callbackAddSettingsSection()
    {
        echo "Woooooooooooooooooooooo";
    }


    /*************************************************************************** */
    public function callbackAddInputName()
    {
        $firstName = esc_attr(get_option('first_name'));
        echo "<input type='text' name='first_name' value='".$firstName."' placeholder='First name' />";
    }

    public function callbackAddInputSurname()
    {
        $lastName = esc_attr(get_option('last_name'));
        echo "<input type='text' name='first_name' value='".$lastName."' placeholder='Last name' />";
    }
    /*************************************************************************** */

    public function addForm()
    {
        ob_start();
        echo '<form method="POST" action="options.php">';
            settings_errors();
            echo "render here ".  $this->pageSlug;
            settings_fields('my-settings-group');
            do_settings_sections($this->pageSlug);
            submit_button();
        echo '</form>';
        $this->render = ob_get_contents(); 
        ob_clean();
        return $this->render;
    }
}
