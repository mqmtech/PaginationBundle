<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Pagination\PageInterface;

interface PaginationInterface
{    
    /**
     * Initialize pagination
     * 
     * @param int $totalItems
     */
    public function init($totalItems);
    
    /**
     * Updates pagination
     * 
     * @param int $totalItems
     */
    public function update($totalItems);
    
    /**
     * @return array<PageInterface>
     */
    public function getPages();
    
    /**
     * @return PageInterface
     */
    public function getCurrentPage();
    
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
    
    /**
     * @param array $array
     * @return array
     */
    public function sliceArray($array);
}