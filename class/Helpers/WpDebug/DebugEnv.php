<?php

namespace My\Lib\Helpers\WpDebug;


class DebugEnv
{
    /**
     * Class constructor.
     */
    public function __construct($search = null)
    {

    }

    /**
     * echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>'
     *
     * @param [type] $search
     * @return void
     */
    public static function getDeclaredVars($search = null)
    {
        echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
    }

    public static function getDeclaredClasses($search = null)
    {

        $data = get_declared_classes();
        if($search){
            $data = array_filter($data, function($v){
                if (strpos(strtolower($v), 'form') !== false) return $v;
            });
        }

        echo '<pre>' . print_r($data, true) . '</pre>';

    }

    public static function getDefinedFunctions($search = null)
    {
        $data = get_defined_functions();
        if($search){
            $data = array_filter($data, function($v){
                if (strpos(strtolower($v), 'form') !== false) return $v;
            });
        }

        echo '<pre>' . print_r($data, true) . '</pre>';
    }

    public static function getDeclaredConstants($search = null)
    {
        $data = get_defined_constants();
        if($search){
            $data = array_filter($data, function($v){
                if (strpos(strtolower($v), 'form') !== false) return $v;
            });
        } 
        echo '<pre>' . print_r($data, true) . '</pre>';
    }

    public static function getLoadedExtension($search = null)
    {
        $data = get_loaded_extensions();
        if($search){
            $data = array_filter($data, function($v){
                if (strpos(strtolower($v), 'form') !== false) return $v;
            });
        }                
        echo '<pre>' . print_r($data, true) . '</pre>';
    }

}
