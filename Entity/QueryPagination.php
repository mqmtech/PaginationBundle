<?php

namespace MQM\Bundle\PaginationBundle\Entity;

use MQM\Bundle\PaginationBundle\Pagination\QueryPaginationInterface;
use MQM\Bundle\PaginationBundle\Pagination\PaginationInterface;
use DoctrineExtensions\Paginate\Paginate;

class QueryPagination implements PaginationInterface
{
    private $pagination;
            
    public function __construct(PaginationInterface $pagination)
    {
        $this->pagination = $pagination;
    }
    
    public function paginateQuery($query)
    {
        $totalItems = Paginate::getTotalQueryResults($query);
        $this->pagination->paginate($totalItems);
        if($totalItems > 0) {            
            $page = $this->pagination->getCurrentPage();
            $length = $page->getLimit() - $page->getOffset();
            $query = Paginate::getPaginateQuery($query, $page->getOffset(), $length); // Simple alternative with no join querys: $paginateQuery = $query->setFirstResult($page->getOffset())->setMaxResults($length);
        }
        
        return $query;
    }    
    
    public function paginate($totalItems)
    {
        $this->pagination->paginate($totalItems);
        
        return $this;
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


    public function setLimitPerPage($limitPerPage)
    {
        return $this->pagination->setLimitPerPage($limitPerPage);
    }

    public function getPaginatedElements($array)
    {
        return $this->pagination->getPaginatedElements($array);
    }
}
