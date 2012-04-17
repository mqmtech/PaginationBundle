<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Pagination\PaginationInterface;
use MQM\Bundle\PaginationBundle\Pagination\PageInterface;

interface PageFactoryInterface
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