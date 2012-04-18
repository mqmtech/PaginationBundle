<?php

namespace MQM\PaginationBundle\Helper;
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
    public function getUri();
    
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