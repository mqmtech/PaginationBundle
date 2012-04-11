<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Pagination\PageInterface;

/**
 * Description of PaginationInterface
 *
 * @author mqmtech
 */
interface PaginationInterface {
    
    /**
     * Initialize pagination
     * 
     * @param int $totalItems
     */
    public function init($totalItems = null);
    
    /**
     * @return array<PageInterface>
     */
    public function getPages();
    
    /**
     * @return PageInterface
     */
    public function getCurrentPage();
    
    /**
     * @param integer $pageLength
     */
    public function setPageLength($pageLength);
    
    /**
     * @return integer $pageLength
     */
    public function getPageLength();
    
    /**
     * @param integer $totalItems
     */
    public function setTotalItems($totalItems);
    
    /**
     * @return integer $totalItems
     */
    public function getTotalItems();
    
    /**
     * @return integer 
     */
    public function getPagesQuantity();
    
    /**
     *
     * @return PageInterface
     */
    public function getPrevPage();
    
    /**
     *
     * @return PageInterface
     */
    public function getNextPage();
    
    /**
     *
     * @return PageInterface
     */
    public function getFirstPage();
    
    /**
     *
     * @return PageInterface
     */
    public function getLastPage();
    
    /**
     * @param array $array
     * @return array
     */
    public function sliceArray($array);
}