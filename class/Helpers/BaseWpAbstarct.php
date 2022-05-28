<?php 
namespace My\Lib\Helpers;

/**
 * Provide basic commonly used WordPress functionality
 */
class BaseWpAbstarct
{
    /**
     * Trigger WP add_action function in a class wrapper
     *
     * @param string $wpHook
     * @param string $methodName
     * 
     * @return void
     */
    public function addAction(string $wpHook, string $methodName, int $priority = 10, int $argsNum = 1)
    {
        add_action($wpHook, [$this, $methodName], $priority, $argsNum);
    }

    /**
     * Trigger WP add_filter function in a class wrapper
     *
     * @param string $wpFilter
     * @param string $methodName
     * 
     * @return void
     */
    public function addFilter(string $wpFilter, string $methodName, int $priority = 10, int $argsNum = 1)
    {
        add_filter($wpFilter, [$this, $methodName], $priority, $argsNum);
    }
}
