<?php
/**
 * @package ScriptsEnqueue
 */
namespace My\Lib\ScriptsEnqueue;

/**
 * Enqueue CSS styles wrapper. Loading script in a quick
 * and easy way whenever you need to load them.
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

    private $_enqueueOnAdmin = false;
    private $_enqueueOnFront = false;
    private $_enqueueOnAdminPageSlug = [];
    private $_enqueueOnFrontPageSlug = [];
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
        bool $_enqueueOnAdmin = false,
        bool $_enqueueOnFront = false
    ) {
        $this->name = $name;
        $this->path = $path;
        $this->dependencies = $dependencies;
        $this->version = $version;
        $this->media = $media;
    }

    /**
     * Enque styles logic
     *
     * @return self
     */
    public function add()
    {
        // var_dump("<pre>", $this);die();

        $this->_getContextURL();

        $this->_hideOnPageSlug();

        $this->_enqueueIfStepInPath();

        $this->_enqueueOnAdminPageSlug();

        $this->_enqueueOnFrontPageSlug();

        $this->_enqueueOnAdmin();

        $this->_enqueueOnFront();

        // echo "<br>";
        // echo $this->name . ": ";
        // print_r(["_enqueueOnFrontPageSlug", $this->_enqueueOnFrontPageSlug]);

        return $this;
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
     * @param bool $_enqueueOnAdmin If true will not load in admin area
     *
     * @return self
     */
    public function setEnqueueOnAdmin($_enqueueOnAdmin = true)
    {
        $this->enqueueOnAdmin = $_enqueueOnAdmin;

        return $this;
    }

    /**
     * Set this to "true" if you wish to load script on frontend
     * NOTE! if defined dependencis will not be present in this area, styles will not load.
     *
     * @param bool $_enqueueOnFront If true will not load in front area
     *
     * @return self
     */
    public function setEnqueueOnFront($_enqueueOnFront = true)
    {
        $this->enqueueOnFront = $_enqueueOnFront;

        return $this;
    }

    /**
     * Define on which admin pages slug this style should to be loaded
     * NOTE! if defined dependencis will not be present in this area, styles will not load.
     *
     * @param string[] $enqueueOnPageSlug If true will not load in front area
     *
     * @return self
     */
    public function setEnqueueOnAdminPage($_enqueueOnAdminPageSlug = [])
    {
        if (is_string($_enqueueOnAdminPageSlug)) {
            $_enqueueOnAdminPageSlug = [$_enqueueOnAdminPageSlug];
        }
        $this->_enqueueOnAdminPageSlug = array_unique(array_merge($_enqueueOnAdminPageSlug, $this->_enqueueOnAdminPageSlug));
        // var_dump($this->_enqueueOnAdminPageSlug);die();
        return $this;
    }

    /**
     * Define on which front pages slug this css style should to be loaded
     * NOTE! if defined dependencis will not be present in this area, styles will not load.
     *
     * @param string|string[] $_enqueueOnFrontPageSlug If true will not load in front area
     *
     * @return self
     */
    public function setEnqueueOnFrontPage($_enqueueOnFrontPageSlug = [])
    {
        if (is_string($_enqueueOnFrontPageSlug)) {
            $_enqueueOnFrontPageSlug = [$_enqueueOnFrontPageSlug];
        }
        $this->_enqueueOnFrontPageSlug = array_unique(array_merge($_enqueueOnFrontPageSlug, $this->_enqueueOnFrontPageSlug));
        // var_dump("_enqueueOnFrontPageSlug", $this->_enqueueOnFrontPageSlug);die(); // shows OK
        return $this;
    }    

    /**
     * Exclude loading off css on pages with slugs in this array
     *
     * @param string|string[] $_hideOnPageSlug 
     *
     * @return self
     */
    public function setExcludeFromSpecyficPage($_hideOnPageSlug = [])
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
     * @param string|string[] $_enqueueIfStepInPath List of valid steps
     *                                              which will triger enqueue of css
     *
     * @return self
     */
    public function setEnqueueIfStepInPath($_enqueueIfStepInPath = [])
    {
        if (is_string($_enqueueIfStepInPath)) {
            $_enqueueIfStepInPath = [$_enqueueIfStepInPath];
        }
        $this->_enqueueIfStepInPath = array_unique(array_merge($_enqueueIfStepInPath, $this->_enqueueIfStepInPath));
        $this->_enqueueIfStepInPath = $_enqueueIfStepInPath;
        return $this;
    }

    /**
     * Return Page Url ($_SERVER['REQUEST_URI'])
     *
     * @return string
     */
    private function _getPageUrl()
    {
        /** @var string $pageSlug Page url */
        $this->pageSlug= $_SERVER['REQUEST_URI'];
        return $this->pageSlug;
    }

    /**
     * Check if page is admin based on page URL
     *
     * @param string $pageSlug URL string
     * 
     * @return boolean +sets $this->isAdmin
     */
    private function _checkIfIsAdmin()
    {
        /** @var boolean $this->isAdmin check if curently init on admin page looking at url */
        $this->isAdmin = (strpos($this->pageSlug, "/wp-admin/admin.php") > -1);
        return $this->isAdmin;
    }

    /**
     * Splits URL to array chunks (URL steps);
     *
     * @param string $pageSlug URL string
     * 
     * @return string[]
     */
    private function _getUrlParts()
    {
        /** @var string[] $urlArrParts An array of url sections (path steps) */
        $this->urlArrParts = explode("/", $this->pageSlug);
        return $this->urlArrParts;
    }

    private function _getContextURL()
    {
        /** @var string $pageSlug Page url */
        $this->_getPageUrl();

        /** @var string[] $urlArrParts An array of url sections (path steps) */
        $this->_getUrlParts();

        /** @var boolean $isAdmin check if curently init on admin page */
        $this->_checkIfIsAdmin();
    }

    private function _hideOnPageSlug()
    {
        $this->_getContextURL();
        // var_dump("_hideOnPageSlug", $this->_hideOnPageSlug); die();
        if (!empty($this->_hideOnPageSlug)) {

            foreach ($this->_hideOnPageSlug as $value) {
                $isAdmin = (strpos($this->pageSlug, "/wp-admin/admin.php") > -1);
                if ($isAdmin) {
                    $matches = null;

                    foreach ($this->urlArrParts as $v) {
                        if (preg_match("/$value/", $v)) {
                            return;
                        }
                    }
                } else {

                    foreach ($this->urlArrParts as $v) {
                        if (preg_match("/$value/", $v)) {
                            return;
                        }
                    }
                }
            }
        }
    }

    /**
     * Check if _enqueueIfStepInPath is not empty and enqueue the CSS styles
     *
     * @return void
     */
    private function _enqueueIfStepInPath()
    {
        $this->_getContextURL();

        if (!empty($this->_enqueueIfStepInPath)) {
            // var_dump("_enqueueIfStepInPath", $this->_enqueueIfStepInPath); die();
            foreach ($this->_enqueueIfStepInPath as $value) {

                if ($this->isAdmin) {
                    $matches = null;

                    /* split admin url by "-" and not by "_" for steps maping */
                    $adminPathUrlSteps = explode("-", $matches[0]);

                    foreach ($adminPathUrlSteps as $v) {
                        if ($value === $v) {
                            add_action('admin_enqueue_scripts', [$this, 'enqueue']);
                        }

                    }
                } else {

                    $urlArrPartsCoppy = $this->urlArrParts;

                    /* return non empty elements */
                    $urlPartsArrayWithoutEmpty = array_filter(
                        $urlArrPartsCoppy,
                        function ($v) {
                            if (!empty($v)) {
                                return $v;
                            }
                        }
                    );

                    /* remove last element - current page slug name */
                    array_pop($urlPartsArrayWithoutEmpty); // edited by reference
                    $urlPartsArrayWithoutEmptyMinusLast = $urlPartsArrayWithoutEmpty;

                    /* loop over site url steps and search for the value */
                    foreach ($urlPartsArrayWithoutEmptyMinusLast as $v) {
                        if (preg_match("/$value/", $v)) {

                            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
                        }
                    }
                }
            }
        }
    }

    private function _enqueueOnAdminPageSlug()
    {
        $this->_getContextURL();
        // var_dump($this->_enqueueOnAdminPageSlug); die();
        if (!empty($this->_enqueueOnAdminPageSlug && $this->isAdmin)) {
            foreach ($this->_enqueueOnAdminPageSlug as $value) {
                foreach ($this->urlArrParts as $v) {
                    if (preg_match("/$value/", $v)) {
                        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
                    }
                }
            }
        }
    }

    private function _enqueueOnFrontPageSlug()
    {
        $this->_getContextURL();
        
        if (!empty($this->_enqueueOnFrontPageSlug)) {
            // var_dump($this->_enqueueOnFrontPageSlug);die();
            foreach ($this->_enqueueOnFrontPageSlug as $value) {
                foreach ($this->urlArrParts as $v) {
                    var_dump("/$value/", "=========>", $this->urlArrParts, $v);
                    if (preg_match("/$value/", $v)) {
                        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
                    }
                }
            }
        }
    }    
    

    private function _enqueueOnAdmin()
    {
        if ($this->enqueueOnAdmin === true) {
            // var_dump($this->enqueueOnAdmin, $urlArrParts);
            add_action('admin_enqueue_scripts', [$this, 'enqueue']);
        }
    }

    private function _enqueueOnFront()
    {
        if ($this->enqueueOnFront === true) {
            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        }
    }

    /** TODO - allow to load styles for specyfic post template */
    public function setEnqueueForTempletParts($_v = [])
    {

    }

    /** TODO - allow to load styles for specyfic post type */
    public function setEnqueueforPostType($_v = [])
    {

    }

    /** TODO - allow to load styles for specyfic category */
    public function setEnqueueforPostCategory($_v = [])
    {

    }

    /** TODO - allow to load styles for specyfic XYZ */
    public function setEnqueueUrlRegex($_v = [])
    {

    }

    /** TODO - allow to load styles for specyfic XYZ */
    public function setEnqueueFrontUrlRegex($_v = [])
    {

    }

    /** TODO - allow to load styles for specyfic XYZ */
    public function setEnqueueAdminUrlRegex($_v = [])
    {

    }

    /**
     * TODO add loading script on page ID like = ([slug[], id[]])
     * Napraw setEnqueueOnFrontPageSlug loading... 
     *  https://wpplugin.dv/kontakt/ page shows empty array
     *      not passing set value to wp callback! ... 
     *      inspect "add" method -- see why it is not pronting the array
     * 
     * IMPLEMENT setEnqueueForTempletParts
     * IMPLEMENT setEnqueueforPostType
     * IMPLEMENT setEnqueueforPostCategory
     * IMPLEMENT setEnqueueUrlRegex
     * IMPLEMENT setEnqueueFrontUrlRegex
     * IMPLEMENT setEnqueueAdminUrlRegex
     */

}
