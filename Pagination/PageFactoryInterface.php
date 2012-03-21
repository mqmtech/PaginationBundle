<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

/**
 *
 * @author mqmtech
 */
interface PageFactoryInterface {
   
    /**
     * @return PaginationInterface
     */
    public function buildPage();    
}

?>
