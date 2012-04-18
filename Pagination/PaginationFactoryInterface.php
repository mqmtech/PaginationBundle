<?php

namespace MQM\PaginationBundle\Pagination;

use MQM\PaginationBundle\Pagination\PaginationInterface;
use MQM\PaginationBundle\Pagination\PageInterface;

interface PaginationFactoryInterface
{   
    /**
     * @return PaginationInterface
     */
    public function createPaginationManager($responsePath = null, array $responseParameters = null);
    
    /**
     * @return PageInterface
     */
    public function createPage();    
}