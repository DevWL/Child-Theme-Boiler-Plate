<?php

namespace My\Lib\Helpers\WpEnchancments;

use My\Lib\Helpers\BaseWpAbstarct;

/**
 * Allow for SVG upload
 */
class AllowSVG extends BaseWpAbstarct
{

    /** @var string MINWPVERSION Min WP version number allowe to upload SVG */
    const MINWPVERSION = "4.7.1";

    /**
     * Class constructor.
     */
    public function __construct()
    {

        global $wp_version;

        if (version_compare($wp_version, self::MINWPVERSION, '<=')) {
            trigger_error(
                "Can not use SVG upload for WP version below " . self::MINWPVERSION 
                . PHP_EOL . " Your version is {$wp_version}",
                E_USER_NOTICE
            );
        }

        $this->addFilter("wp_check_filetype_and_ext", "checkWPforSVG", 10, 4); 
        $this->addFilter("upload_mimes", "addMimeType"); 
        $this->addAction("admin_head", "fixSvgDisplay");
    }

    /**
     * Alter output of "wp_check_filetype_and_ext" wp filter
     *
     * @param [type] $data 
     * @param [type] $file 
     * @param [type] $filename 
     * @param [type] $mimes 
     * 
     * @return void
     */
    public function checkWPforSVG($data, $file, $filename, $mimes)
    {
        global $wp_version;

        if (version_compare($wp_version, self::MINWPVERSION, '<=')) {
            return $data;
        }
      
        $filetype = wp_check_filetype($filename, $mimes);
      
        return [
            'ext'             => $filetype['ext'],
            'type'            => $filetype['type'],
            'proper_filename' => $data['proper_filename']
        ];
    }

    /**
     * Add new mime type by modifying "upload_mimes" wp filter
     *
     * @param array $mimes 
     * 
     * @return void
     */
    public function addMimeType($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     * Fix SVG display by hooking in to "admin_head" wp action
     *
     * @return void
     */
    public function fixSvgDisplay()
    {
        echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
    }



}