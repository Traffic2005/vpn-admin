<?php namespace app\Managers;

/**
 * Created by PhpStorm.
 * User: traffic2005
 * Date: 13.10.17
 * Time: 12:54
 */


class BaseManager
{

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }


}