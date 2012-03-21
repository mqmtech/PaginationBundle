<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

/**
 * Description of PaginationInterface
 *
 * @author mqmtech
 */
interface PaginationInterface {
    
    /**
     * @return integer returns the index of array of pages
     */
    public function getCurrentPageIndex();
    
    /**
     * @param integer set the index in the array of pages
     */
    public function setCurrentPageIndex($pageIndex);

    /**
     * @return array<PageInterface>
     */
    public function getPages();
    
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
    
    /**
     * Initialize function
     * 
     * Recalc pagination
     * @param int $totalItems
     */
    public function calcPagination($totalItems=null);
    

}

?>
