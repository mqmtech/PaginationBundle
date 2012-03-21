<?php

namespace MQM\Bundle\PaginationBundle\Helper;

use MQM\Bundle\PaginationBundle\Helper\HelperInterface as PaginationHelperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class Helper implements PaginationHelperInterface{
    
    /**
     *
     * @var Request $request
     */
    private $request;


    function __construct(Request $request) {
        $this->request = $request;
    }

    public function toQueryString($array)
    {        
        if($array == NULL){
            return NULL;
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
    
    public function getURI()
    {
        $path = $this->getRequest()->getPathInfo();
        $path = $this->getRequest()->getUriForPath($path);
        
        return $path;
    }
    
    public function getParametersByRequestMethod()
    {
        $request = $this->getRequest();
        
        if ($request  == null) {
            return null;
        }
        
        $method = $request->getMethod();
        $query = null;
        if ($method == 'POST') {
            $query = $request->request;
        }
        else {
            $query = $request->query;
        }
        
        return $query;
    }
    
    public function getAllParametersFromRequestAndQuery()
    {
        $request = $this->getRequest();
        
        if ($request  == null) {
            return null;
        }
        
        $parameters = array();
        $paramRequest = $request->request->all();
        $paramQuery = $request->query->all();
        $parameters = array_merge($paramQuery, $paramRequest);        
        
        return $parameters;
    }
    
    public function getRequest() {
        return $this->request;
    }

    public function setRequest($request) {
        $this->request = $request;
    }



}
