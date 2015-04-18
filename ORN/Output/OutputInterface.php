<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/18/15
 * Time: 12:49 AM
 */

namespace ORN\Output;

interface OutputInterface {
    /**
     * @param array $route
     * @param array $params
     * @return bool
     */
    function prepare($route, $params);

    /**
     * @return bool
     */
    function out();
}
