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

        /* register settings */
        register_setting("my-settings-group", "first_name");
        register_setting("my-settings-group", "last_name");
        register_setting("my-settings-group", "email");
        register_setting("my-settings-group", "phone");
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
        /* add setting section */
        add_settings_section("my-settings-options", "My Options", [$this, "callbackAddSettingsSection"], $this->pageSlug);

        /* add setting fields */
        add_settings_field("my-settings-input-name", "First Name", [$this, "callbackAddInputName"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-surname", "Last Name", [$this, "callbackAddInputSurname"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-email", "Email", [$this, "callbackAddInputEmail"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-tel", "Telefon", [$this, "callbackAddInputPhone"], $this->pageSlug, "my-settings-options");

        /* render the form */
        return $this->addForm();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function callbackAddSettingsSection()
    {
        echo "Podaj podstawowe dane kontaktowe. Te dane będą wyświetlane na stronie poprzez shordcode";
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
        echo "<input type='text' name='last_name' value='".$lastName."' placeholder='".__("Surname")."' />";
    }

    public function callbackAddInputEmail()
    {
        $email = esc_attr(get_option('email'));
        echo "<input type='text' name='email' value='".$email."' placeholder='E-mail' />";
    }

    public function callbackAddInputPhone()
    {
        $phone = esc_attr(get_option('phone'));
        echo "<input type='text' name='phone' value='".$phone."' placeholder='".__("Telephone")."' />";
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
