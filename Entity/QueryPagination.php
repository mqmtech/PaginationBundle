<?php

namespace MQM\Bundle\PaginationBundle\Entity;

use MQM\Bundle\PaginationBundle\Pagination\QueryPaginationInterface;
use MQM\Bundle\PaginationBundle\Pagination\PaginationInterface;
use DoctrineExtensions\Paginate\Paginate;

class QueryPagination implements PaginationInterface, QueryPaginationInterface
{
    private $pagination;
            
    public function __construct(PaginationInterface $pagination)
    {
        $this->pagination = $pagination;
    }
    
    public function paginateQuery($query)
    {
        $totalItems = Paginate::getTotalQueryResults($query); // Step 1
        $this->pagination->update($totalItems);
        $page = $this->pagination->getCurrentPage();
        $length = $page->getLimit() - $page->getOffset();
        $paginateQuery = Paginate::getPaginateQuery($query, $page->getOffset(), $length); // Step 2 and 3
        $result = $paginateQuery->getResult();
        
        return $result;
    }

    public function getCurrentPage()
    {
        return $this->pagination->getCurrentPage();
    }

    public function getEndRange()
    {
        return $this->pagination->getEndRange();
    }

    public function getFirstPage()
    {
        return $this->pagination->getEndRange();
    }

    public function getLastPage()
    {
        return $this->pagination->getLastPage();
    }

    public function getLimitPerPage()
    {
        return $this->pagination->getLimitPerPage();
    }

    public function getNextPage()
    {
        return $this->pagination->getNextPage();
    }

    public function getPages()
    {
        return $this->pagination->getPages();
    }

    public function getPrevPage()
    {
        return $this->getPrevPage();
    }

    public function getStartRange()
    {
        return $this->getStartRange();
    }

    public function init($totalItems)
    {
        return $this->pagination->init($totalItems);
    }

    public function setLimitPerPage($limitPerPage)
    {
        return $this->pagination->setLimitPerPage($limitPerPage);
    }

    public function sliceArray($array)
    {
        return $this->pagination->sliceArray($array);
    }

    public function update($totalItems)
    {
        return $this->pagination->update($totalItems);
    }
}
