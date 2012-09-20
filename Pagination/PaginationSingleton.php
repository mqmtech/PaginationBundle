<?php

namespace MQM\PaginationBundle\Pagination;

class PaginationSingleton
{
    private static $singleton = null;

    public static function getInstance()
    {
        return self::$singleton;
    }

    public static function setInstance(PaginationInterface $pagination)
    {
        self::$singleton = $pagination;
    }
}
