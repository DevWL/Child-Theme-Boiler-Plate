<?php 
namespace My\Lib\Helpers;

/**
 * Basic Model Interface
 */
interface ModelInterface
{
    /**
     * Query data
     *
     * @return array
     */
    public function queryData();

    /**
     * Return data from the last query
     *
     * @return void
     */
    public function getData();
}