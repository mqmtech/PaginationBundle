<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Pagination\PaginationInterface;
use MQM\Bundle\PaginationBundle\Pagination\PageInterface;

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