<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQM\Bundle\PaginationBundle\Pagination;

/**
 *
 * @author mqmtech
 */
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
    
    /**
     * @param bool $isCurrent
     */
    public function setIsCurrent($isCurrent);
    
    /**
     * return bool
     */
    public function getIsCurrent();
    
}

?>
