<?php

namespace My\Lib\ScriptsEnqueue;

/**
 * Enqueue script wrapper
 */
class EnqueScript
{
    public $name;
    public $path;
    public $dependencies;
    public $version;
    public $inFooter;
    public $disableOnAdmin = false;
    public $disableOnFront = false;

    /**
     * Script Wrapper
     *
     * @param string           $name 
     * @param string           $path  
     * @param array            $dependencies 
     * @param string|bool|null $version 
     * @param bool             $inFooter     Load script in footer
     * 
     * @see WordPress wp_enqueue_script() function documentation
     */
    public function __construct(
        string $name,
        string $path = '',
        array $dependencies = [],
        $version = false,
        bool $inFooter = false,
        bool $disableOnAdmin = false,
        bool $disableOnFront = false
    ) {
        $this->name = $name;
        $this->path = $path;
        $this->dependencies = $dependencies;
        $this->version = $version;
        $this->inFooter = $inFooter;
        $this->disableOnAdmin = $disableOnAdmin;
        $this->disableOnFront = $disableOnFront;

        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    /**
     * Runs WordPress function wp_enqueue_script()
     *
     * @return void
     */
    public function enqueue()
    {

        /* stop function if true and is admin area */
        if ($this->disableOnAdmin && is_admin()) {
            return;
        }

        /* stop function if true and is not admin area */
        if ($this->disableOnFront && !is_admin()) {
            return;
        } 

        wp_enqueue_script( 
            $this->name,
            $this->path,
            $this->dependencies,
            $this->version,
            $this->inFooter
        );
    }

    /**
     * Set the value of name
     * 
     * @param string $name Label name of a script. Can be used for defining dependencies.
     *
     * @return self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the value of path 
     * 
     * @param string $path - Path to a script
     * 
     * @return self
     */ 
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set the value of path 
     * 
     * @param array $dependencies - List names of all dependencies in an array
     * 
     * @return self
     */ 
    public function setDependencies($dependencies)
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * Set the value of version
     * 
     * @param mixed $version Add Version num. Name convention for changes tracking and cache
     * 
     * @return self
     */ 
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Set the value of inFooter
     *
     * @param string $inFooter 
     * 
     * @return void
     */
    public function setinFooter($inFooter)
    {
        $this->inFooter = $inFooter;

        return $this;
    }

    /**
     * Set the value of disableOnAdmin 
     * 
     * @param bool $disableOnAdmin If true will not load in admin area
     *
     * @return self
     */ 
    public function setDisableOnAdmin($disableOnAdmin = true)
    {
        $this->disableOnAdmin = $disableOnAdmin;

        return $this;
    }

    /**
     * Set the value of disableOnFront 
     * 
     * @param bool $disableOnFront If true will not load in front area
     *
     * @return self
     */ 
    public function setDisableOnFront($disableOnFront = true)
    {
        $this->disableOnFront = $disableOnFront;

        return $this;
    }
}