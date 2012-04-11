<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

interface QueryPaginationInterface
{
    /**
     * @return mixed 
     */
    public function paginateQuery($query);
}
