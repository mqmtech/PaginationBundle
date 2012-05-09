<?php

namespace MQM\PaginationBundle\Entity;


use MQM\PaginationBundle\Pagination\PaginationInterface;
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
        if (!is_a($query, 'Doctrine\ORM\Query')){
            throw new \Exception('Type of query not supported, it must be of type Doctrine\ORM\Query');
        }
        $totalItems = Paginate::getTotalQueryResults($query);
        $this->pagination->init($totalItems);
        if($totalItems > 0) {            
            $page = $this->pagination->getCurrentPage();
            $length = $page->getLimit() - $page->getOffset();
            //$query = Paginate::getPaginateQuery($query, $page->getOffset(), $length); // Simple alternative with no join querys: $paginateQuery = $query->setFirstResult($page->getOffset())->setMaxResults($length);
            return $query->setFirstResult($page->getOffset())->setMaxResults($length);
        }
        
        return $query;
    }    
    
    public function init($totalItems)
    {
        $this->pagination->init($totalItems);
        
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

    public function paginateArray($array)
    {
        return $this->pagination->paginateArray($array);
    }
}
