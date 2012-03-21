<?php

namespace MQM\Bundle\PaginationBundle\Helper;
/**
 *
 * @author mqmtech
 */
interface HelperInterface {
    
    /**
     *
     * @param array $array
     * @return string 
     */
    public function toQueryString($array);
    
    /**
     *
     * @return string 
     */
    public function getURI();
    
    /**
     *
     * @return ParameterBag
     */
    public function getParametersByRequestMethod();
    
    /**
     *
     * @return array 
     */
    public function getAllParametersFromRequestAndQuery();    
}

?>
