<?php

namespace MQM\PaginationBundle\Pagination;

use MQM\PaginationBundle\Pagination\PageInterface;

interface PaginationInterface
{    
    /**
     * @param int $totalItems
     */
    public function init($totalItems);    
        
    /**
     * @param string
     * @return query 
     */
    public function paginateQuery($query);
    
    /**
     * @param array $array
     * @return array
     */
    public function paginateArray($array);
    
    /**
     * @return array<PageInterface>
     */
    public function getPages();
    
    /**
     * @return PageInterface
     */
    public function getCurrentPage();

    /**
     * @param PageInterface $page
     * @return PaginationInterface
     */
    public function setCurrentPage(PageInterface $page);
    
    /**
     * @param integer $limitPerPage
     */
    public function setLimitPerPage($limitPerPage);
    
    /**
     * @return integer $limitPerPage
     */
    public function getLimitPerPage();
    
    /**
     * @return integer 
     */
    public function getStartRange();
            
    /**
     * @return integer 
     */
    public function getEndRange();
    
    /**
     * @return PageInterface
     */
    public function getPrevPage();
    
    /**
     * @return PageInterface
     */
    public function getNextPage();
    
    /**
     * @return PageInterface
     */
    public function getFirstPage();
    
    /**
     * @return PageInterface
     */
    public function getLastPage();
}