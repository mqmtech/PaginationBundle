<?php

namespace MQM\PaginationBundle\Pagination;

interface PageInterface {
    
    /**
     * @param string $id
     */
    public function setId($id);
    
    /**
     * @return $string
     */
    public function getId();
    
    /**
     * @return array 
     */
    public function getRange();
    
    /**
     * @param int $offset
     */
    public function setOffset($offset);
    
    /**
     * @return int
     */
    public function getOffset();
    
    /**
     * @param int $limit
     */
    public function setLimit($limit);
    
    /**
     * @return int
     */
    public function getLimit();

}