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
    public function __construct($page) {
        $this->setPage($page);
        $this->addAction("admin_init", "setCustomSettings");
    }

    public function setCustomSettings()
    {
        register_setting("my-settings-group", "first_name");
        add_settings_section("my-settings-options", "My Options", [$this, "callbackAddSettingsSection"], $this->page);
        add_settings_field("my-settings-input-name", "First Name", [$this, "callbackAddInputName"], $this->page, "my-settings-options");
        $this->render = $this->addForm();
    }

    public function callbackAddSettingsSection()
    {
        echo "Woooooooooooooooooooooo";
    }

    public function callbackAddInputName()
    {
        $firstName = esc_attr(get_option('first_name'));
        echo "<input type='text' name='first_name' value='".$firstName."' placeholder='First name' />";
    }


    public function renderForm()
    {
        return $this->render;
    }
    

    public function addForm()
    {
        echo '<form method="POST" actoin="options.php">';
            settings_errors();
            echo "render here ".  $this->page;
            settings_fields('my-settings-group');
            do_settings_sections($this->page);
            submit_button();
        echo '</form>';
    }

    public function setPage($page)
    {
        $this->page = $page;
    }




}
