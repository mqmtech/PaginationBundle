<?php

namespace MQM\PaginationBundle\Helper;

use MQM\PaginationBundle\Helper\HelperInterface as PaginationHelperInterface;
use Symfony\Component\HttpFoundation\Request;

class Helper implements PaginationHelperInterface
{    
    private $request;

    function __construct(Request $request) {
        $this->request = $request;
    }

    public function toQueryString($array)
    {        
        if ($array == null) {
            return null;
        }        
        $querystring = "";        
        $count = 0;
        foreach ($array as $key => $value) {
            if($count == 0){
                $querystring.="?";
            }
            else{
                $querystring.="&";
            }            
            $querystring .=$key ."=".$value;                    
            $count++;
        }
        
        return $querystring;
    }
    
    public function getUri()
    {
        $path = $this->request->getPathInfo();
        $path = $this->request->getUriForPath($path);
        
        return $path;
    }
    
    public function getParametersByRequestMethod()
    {
        if ($this->request  == null) {
            return null;
        }
        
        $method = $this->request->getMethod();
        $query = null;
        if ($method == 'POST') {
            $query = $this->request->request;
        }
        else {
            $query = $this->request->query;
        }
        
        return $query;
    }
    
    public function getAllParametersFromRequestAndQuery()
    {
        if ($this->request  == null) {
            return null;
        }        
        $parameters = array();
        $paramRequest = $this->request->request->all();
        $paramQuery = $this->request->query->all();
        $parameters = array_merge($paramQuery, $paramRequest);        
        
        return $parameters;
    }
}
