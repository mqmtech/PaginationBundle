<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Pagination\PaginationInterface;

interface PageFactoryInterface
{   
    /**
     * @return PaginationInterface
     */
    public function buildPage();    
}