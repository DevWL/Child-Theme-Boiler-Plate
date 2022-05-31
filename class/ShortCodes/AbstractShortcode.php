<?php

namespace My\Lib\ShortCodes;

use My\Lib\Helpers\BaseWpAbstarct;

class AbstractShortcode extends BaseWpAbstarct
{
    /**
     * Class constructor.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->register();
    }

    /**
     * Takes a colbac function which is registered as shortcode
     *
     * @param mixed $callback A function which will return shortcode output (string)
     * 
     * @return string
     */
    public function ShortCode()
    {
        return get_option($this->name);
    }

    /**
     * Register shortcode with shortcode name and anonymus function
     *
     * @return void
     */
    public function register()
    {
        $this->registerShortcode($this->name, "ShortCode");
    }


}
