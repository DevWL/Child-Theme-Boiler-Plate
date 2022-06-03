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
    public $enqueueOnAdmin = false;
    public $enqueueOnFront = false;

    private $_enqueueOnPageSlug = [];
    private $_hideOnPageSlug = [];
    private $_enqueueIfStepInPath = [];

    /**
     * Script Wrapper
     *
     * @param string           $name         Has to be unique!. Other way only the first declaration will be loaded
     * @param string           $path         Full Path to the css file
     * @param array            $dependencies Has to be fullfill for the script to load. Be sure to make dependencies accessible for that page.
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
        bool $enqueueOnAdmin = false,
        bool $enqueueOnFront = false
    ) {
        $this->name = $name;
        $this->path = $path;
        $this->dependencies = $dependencies;
        $this->version = $version;
        $this->media = $media;
    }

    /**
     * Enque styles
     *
     * @return void
     */
    public function add()
    {
        /* get page url */
        $pageSlug = $_SERVER['REQUEST_URI'];

        /* convert front url to path steps */
        $urlArrParts = explode("/", $pageSlug);

        /* check if curently init on admin page */
        $isAdmin = (strpos($pageSlug, "/wp-admin/admin.php") > -1);

        if (!empty($this->_hideOnPageSlug)) {
            foreach ($this->_hideOnPageSlug as $value) {
                $isAdmin = (strpos($pageSlug, "/wp-admin/admin.php") > -1);

                if ($isAdmin) {
                    $matches = null;

                    foreach ($urlArrParts as $v) {
                        if (preg_match("/$value/", $v)) {

                            return;
                        }
                    }
                } else {

                    foreach ($urlArrParts as $v) {
                        if (preg_match("/$value/", $v)) {

                            return;
                        }
                    }
                }
            }
        }

        if (!empty($this->_enqueueIfStepInPath)) {

            foreach ($this->_enqueueIfStepInPath as $value) {

                if ($isAdmin) {
                    $matches = null;

                    /* split admin url by "-" and not by "_" for steps maping */
                    $adminPathUrlSteps = explode("-", $matches[0]);

                    foreach ($adminPathUrlSteps as $v) {
                        if ($value === $v) {
                            add_action('admin_enqueue_scripts', [$this, 'enqueue']);
                        }

                    }
                } else {

                    $urlPartsArrayWithoutLast = $urlArrParts;

                    /* return non empty elements */
                    $urlPartsArrayWithoutLast = array_filter($urlPartsArrayWithoutLast,
                        function ($v) {
                            if (!empty($v)) {
                                return $v;
                            }
                        }
                    );

                    /* remove last element - current page slug name */
                    array_pop($urlPartsArrayWithoutLast);

                    /* loop over site url steps and search for the value */
                    foreach ($urlPartsArrayWithoutLast as $v) {
                        if (preg_match("/$value/", $v)) {

                            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
                        }
                    }
                }
            }
        }

        if (!empty($this->_enqueueOnPageSlug)) {
            foreach ($this->_enqueueOnPageSlug as $value) {

                if ($isAdmin) {

                    foreach ($urlArrParts as $v) {
                        if (preg_match("/$value/", $v)) {

                            add_action('admin_enqueue_scripts', [$this, 'enqueue']);
                        }
                    }
                } else {

                    foreach ($urlArrParts as $v) {
                        if (preg_match("/$value/", $v)) {

                            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
                        }
                    }
                }
            }
        }

        if ($this->enqueueOnAdmin === true) {
            var_dump($this->enqueueOnAdmin, $urlArrParts);
            add_action('admin_enqueue_scripts', [$this, 'enqueue']);
        }

        if ($this->enqueueOnFront === true) {
            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        }
    }

    /**
     * Register and andqueue styles
     *
     * @return void
     */
    public function enqueue()
    {
        wp_register_style(
            $this->name,
            $this->path,
            $this->dependencies,
            $this->version,
            $this->media
        );

        wp_enqueue_style(
            $this->name
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
     * Set this to "true" if you wish to load script on admin area
     * NOTE! if defined dependencis will not be present in this area, styles will not load.
     *
     * @param bool $enqueueOnAdmin If true will not load in admin area
     *
     * @return self
     */
    public function enqueueOnAdmin($enqueueOnAdmin = true)
    {
        $this->enqueueOnAdmin = $enqueueOnAdmin;

        return $this;
    }

    /**
     * Set this to "true" if you wish to load script on frontend
     * NOTE! if defined dependencis will not be present in this area, styles will not load.
     *
     * @param bool $enqueueOnFront If true will not load in front area
     *
     * @return self
     */
    public function enqueueOnFront($enqueueOnFront = true)
    {
        $this->enqueueOnFront = $enqueueOnFront;

        return $this;
    }

    /**
     * Define on which page sug this style should to be loaded
     * NOTE! if defined dependencis will not be present in this area, styles will not load.
     *
     * @param string[] $enqueueOnPageSlug If true will not load in front area
     *
     * @return self
     */
    public function enqueueOnSpecyficPage($_enqueueOnPageSlug = [])
    {
        if (is_string($_enqueueOnPageSlug)) {
            $_enqueueOnPageSlug = [$_enqueueOnPageSlug];
        }
        $this->_enqueueOnPageSlug = array_unique(array_merge($_enqueueOnPageSlug, $this->_enqueueOnPageSlug));
        $this->_enqueueOnPageSlug = $_enqueueOnPageSlug;
        return $this;
    }

    /**
     * Exclude loading off css on pages with slugs in this array
     *
     * @param array $_hideOnPageSlug
     *
     * @return self
     */
    public function excludeFromSpecyficPage($_hideOnPageSlug = [])
    {
        if (is_string($_hideOnPageSlug)) {
            $_hideOnPageSlug = [$_hideOnPageSlug];
        }
        $this->_hideOnPageSlug = array_unique(array_merge($_hideOnPageSlug, $this->_hideOnPageSlug));
        $this->_hideOnPageSlug = $_hideOnPageSlug;
        return $this;
    }

    /**
     * Enqueue css styles only if there is a step in a path 
     * for example /blog/xyz... where "blog" is a step 
     * which need to match to load css
     *
     * @param string[] $_enqueueIfStepInPath List of valid steps 
     *                                       which will triger enqueue of css
     * 
     * @return void 
     */
    public function enqueueIfStepInPath($_enqueueIfStepInPath = [])
    {
        if (is_string($_enqueueIfStepInPath)) {
            $_enqueueIfStepInPath = [$_enqueueIfStepInPath];
        }
        $this->_enqueueIfStepInPath = array_unique(array_merge($_enqueueIfStepInPath, $this->_enqueueIfStepInPath));
        $this->_enqueueIfStepInPath = $_enqueueIfStepInPath;
        return $this;
    }
}
