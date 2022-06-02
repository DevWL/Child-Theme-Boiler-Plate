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

    /**
     * Undocumented function
     *
     * @param string $pageSlug Page slug requiered by other class methods which run wp core functions. 
     */
    public function __construct($pageSlug)
    {
        $this->setPageSlug($pageSlug);

        /* register settings */
        register_setting("my-settings-group", "first_name");
        register_setting("my-settings-group", "last_name");
        register_setting("my-settings-group", "email", [$this, "customEmailSanitizeMethod"]);
        register_setting("my-settings-group", "emailCC", [$this, "customEmailSanitizeMethod"]);
        register_setting("my-settings-group", "phone");
        register_setting("my-settings-group", "company_name");
        register_setting("my-settings-group", "address1");
        register_setting("my-settings-group", "address2");
        register_setting("my-settings-group", "postcode");
        register_setting("my-settings-group", "city");
        register_setting("my-settings-group", "nip");
        register_setting("my-settings-group", "bank_name");
        register_setting("my-settings-group", "bank_account_num");
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
        add_settings_field("my-settings-input-name", "Full name", [$this, "callbackAddInputFullName"], $this->pageSlug, "my-settings-options");
        // add_settings_field("my-settings-input-surname", "Last Name", [$this, "callbackAddInputSurname"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-email", "Email", [$this, "callbackAddInputEmail"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-emailCC", "Email CC", [$this, "callbackAddInputEmailCC"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-tel", "Telefon", [$this, "callbackAddInputPhone"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-address-company-name", __("Company name"), [$this, "callbackAddInputCompanyName"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-address1", __("Address Line 1"), [$this, "callbackAddInputAddress1"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-address2", __("Address Line 2"), [$this, "callbackAddInputAddress2"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-address-postcode", __("Post Code"), [$this, "callbackAddInputPostCode"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-address-city", __("City"), [$this, "callbackAddInputCity"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-address-nip", __("Nip"), [$this, "callbackAddInputNIP"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-address-bank-name", __("Bank name"), [$this, "callbackAddInputBankNumber"], $this->pageSlug, "my-settings-options");
        add_settings_field("my-settings-input-address-bank-account-num", __("Account number"), [$this, "callbackAddInputBankName"], $this->pageSlug, "my-settings-options");

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
    public function callbackAddInputFullName()
    {
        $firstName = esc_attr(get_option('first_name'));
        echo "<input type='text' name='first_name' value='".$firstName."' placeholder='First name' />";

        $lastName = esc_attr(get_option('last_name'));
        echo "<input type='text' name='last_name' value='".$lastName."' placeholder='".__("Surname")."' />
        <p class='description'>Hi! Whats you name?</p>";
    }

    public function callbackAddInputEmail()
    {
        $email = esc_attr(get_option('email'));
        echo "<input type='text' name='email' value='".$email."' placeholder='".__("Email")."' />
        <p class='description'>Symbol @ will be converted to [at]</p>";
    }

    public function callbackAddInputEmailCC()
    {
        $emailCC = esc_attr(get_option('emailCC'));
        echo "<input type='text' name='emailCC' value='".$emailCC."' placeholder='".__("Email CC")."' />";
    }

    public function callbackAddInputCompanyName()
    {
        $company_name = esc_attr(get_option('company_name'));
        echo "<input type='text' name='company_name' value='".$company_name."' placeholder='".__("Company name")."' />";
    }

    public function callbackAddInputPhone()
    {
        $phone = esc_attr(get_option('phone'));
        echo "<input type='text' name='phone' value='".$phone."' placeholder='".__("Telephone")."' />";
    }

    public function callbackAddInputAddress1()
    {
        $address1 = esc_attr(get_option('address1'));
        echo "<input type='text' name='address1' value='".$address1."' placeholder='".__("Addres line 1")."' />";
    }

    public function callbackAddInputAddress2()
    {
        $addres2 = esc_attr(get_option('addres2'));
        echo "<input type='text' name='addres2' value='".$addres2."' placeholder='".__("Addres line 2")."' />";
    }

    public function callbackAddInputPostCode()
    {
        $postcode = esc_attr(get_option('postcode'));
        echo "<input type='text' name='postcode' value='".$postcode."' placeholder='".__("Post Code")."' />";
    }

    public function callbackAddInputCity()
    {
        $city = esc_attr(get_option('city'));
        echo "<input type='text' name='city' value='".$city."' placeholder='".__("City")."' />";
    }

    public function callbackAddInputNIP()
    {
        $nip = esc_attr(get_option('nip'));
        echo "<input type='text' name='nip' value='".$nip."' placeholder='".__("NIP")."' />";
    }

    public function callbackAddInputBankNumber()
    {
        $bank_name = esc_attr(get_option('bank_name'));
        echo "<input type='text' name='bank_name' value='".$bank_name."' placeholder='".__("Bank name")."' />";
    }

    public function callbackAddInputBankName()
    {
        $bank_account_num = esc_attr(get_option('bank_account_num'));
        echo "<input type='text' name='bank_account_num' value='".$bank_account_num."' placeholder='".__("Bank account number")."' />";
    }
    
    /* ************************************************************************** */
    
    public function customEmailSanitizeMethod($userInput)
    {
        $sanitizeData = preg_replace('/[@]/', "[at]", $userInput);
        // $sanitizeData = preg_replace('/\[at\]/', "@", $userInput);
        return $sanitizeData;
    }


     /* ************************************************************************** */

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
