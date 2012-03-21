<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use MQM\Bundle\PaginationBundle\Helper\HelperInterface;
/**
 * Description of WebPage
 *
 * @author mqmtech
 */
class WebPage implements PageInterface{
    
    const DEF_ID = 0;
    const DEF_OFFSET = 0;
    const DEF_LIMIT = 0;
    const DEF_IS_CURRENT = false;
    
    private $id = self::DEF_ID;
    
    private $offset = self::DEF_OFFSET;
    
    private $limit = self::DEF_LIMIT;
    
    private $isCurrent = self::DEF_IS_CURRENT;
    
    private $responseParameters;
    
    private $responsePath;    
    
    /**
     *
     * @var HelperInterface
     */
    private $helper;
    
    private $router;
    
    public function __construct(HelperInterface $helper, Router $router, $responsePath=null, $responseParameters=null) {
        $this->setHelper($helper);
        $this->setRouter($router);
        $this->setResponsePath($responsePath);
        $this->setResponseParameters($responseParameters);
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getIsCurrent() {
        return $this->isCurrent;
    }
    public function getLimit() {
        return $this->limit;
    }
    public function getOffset() {
        return $this->offset;
    }
    public function setIsCurrent($isCurrent) {
        $this->isCurrent=$isCurrent;
    }
    public function setLimit($limit) {
        $this->limit = $limit;
    }
    public function setOffset($offset) {
        $this->offset = $offset;
    }
    
    public function getResponseParameters() {
        return $this->responseParameters;
    }

    public function setResponseParameters($responseParameters) {
        $this->responseParameters = $responseParameters;
    }

    public function getResponsePath() {
        return $this->responsePath;
    }

    public function setResponsePath($responsePath) {
        $this->responsePath = $responsePath;
    }
    
    public function getHelper() {
        return $this->helper;
    }

    public function setHelper($helper) {
        $this->helper = $helper;
    }

    public function getRouter() {
        return $this->router;
    }

    public function setRouter($router) {
        $this->router = $router;
    }
    
    public function getURL(){
        
        $url = "no_url";
        
        $parameters = null;
        if($this->getResponseParameters() != null){
            $parameters = $this->getResponseParameters();
        }
        else{
            $parameters = $this->getHelper()->getAllParametersFromRequestAndQuery();
        }

        $parameters[WebPagination::REQUEST_QUERY_PARAM]= $this->getId();

        if($this->getResponsePath() == null){
            $path = $this->getHelper()->getURI();
            $url = $path . $this->getHelper()->toQueryString($parameters);
        }
        else {
            $url = $this->getRouter()->generate($this->getResponsePath(), $parameters);
        }
        
        return $url;
    }
}

?>
