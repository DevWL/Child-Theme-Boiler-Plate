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
     * @param string $hook
     * @param string $methodName
     * 
     * @return void
     */
    public function addAction(string $hook, string $methodName)
    {
        add_action($hook, [$this, $methodName]);
    }
}
