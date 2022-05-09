<?php
/**
 * @package ScriptsEnqueue
 */
namespace My\Lib\ScriptsEnqueue;

/**
 * Enqueue styles wrapper
 */
class EnqueStyle
{
    /** 
     * This is const
     * 
     * @const string OPTIONS_MEDIA_ALL Default value for $media 
     */
    const OPTIONS_MEDIA_ALL = 'all';

    public $name;
    public $path;
    public $dependencies;
    public $version;
    public $media;
    public $disableOnAdmin = false;
    public $disableOnFront = false;

    /**
     * Script Wrapper
     *
     * @param string           $name 
     * @param string           $path  
     * @param array            $dependencies 
     * @param string|bool|null $version 
     * @param string           $media        Use Class OPTIONS_MEDIA const array 
     * 
     * @see WordPress wp_enqueue_style() function documentation
     */
    public function __construct(
        string $name,
        string $path = '',
        array $dependencies = [],
        $version = false,
        string $media = 'all',
        bool $disableOnAdmin = false,
        bool $disableOnFront = false
    ) {
        $this->name = $name;
        $this->path = $path;
        $this->dependencies = $dependencies;
        $this->version = $version;
        $this->media = $media;
        $this->disableOnAdmin = $disableOnAdmin;
        $this->disableOnFront = $disableOnFront;

        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    /**
     * Runs WordPress function wp_enqueue_style()
     *
     * @return void
     */
    public function enqueue()
    {
        
        if ($this->disableOnAdmin && is_admin()) {
            return;
        }

        if ($this->disableOnFront && !is_admin()) {
            return;
        } 

        wp_enqueue_style(
            $this->name,
            $this->path,
            $this->dependencies,
            $this->version,
            $this->media
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
     * Set the value of media
     *
     * @param string $media 
     * 
     * @see WordPress wp_enqueue_style() function documentation for all available options
     * 
     * @return void
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Set the value of disableOnAdmin 
     * 
     * @param bool $disableOnAdmin If true will not load in admin area
     *
     * @return  self
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
     * @return  self
     */ 
    public function setDisableOnFront($disableOnFront = true)
    {
        $this->disableOnFront = $disableOnFront;

        return $this;
    }
}