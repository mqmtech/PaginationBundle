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
        //$paginateQuery = Paginate::getPaginateQuery($query, $page->getOffset(), $length); // Step 2 and 3
        //$result = $paginateQuery->getResult();
        $result = $query->setFirstResult($page->getOffset())->setMaxResults($length)->getResult(); // Step 2
        
        return $result;
    }

    public function getCurrentPage()
    {
        return $this->pagination->getCurrentPage();
    }
    
    public function getStartRange()
    {
        return $this->pagination->getStartRange();
    }

    public function getEndRange()
    {
        return $this->pagination->getEndRange();
    }

    public function getFirstPage()
    {
        return $this->pagination->getFirstPage();
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
        return $this->pagination->getPrevPage();
    }

    public function init($totalItems)
    {
        $this->pagination->init($totalItems);
        
        return $this;
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
        $this->pagination->update($totalItems);
        
        return $this;
    }
}
