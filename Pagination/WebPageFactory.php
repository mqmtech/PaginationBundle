<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Helper\HelperInterface;
use Symfony\Component\Routing\Router;
/**
 * Description of WebPageFactory
 *
 * @author mqmtech
 */
class WebPageFactory implements PageFactoryInterface{

    /*
     * var HelperInterface $helper
     */
    private $helper;
    
    /**
     * var Router $router
     */
    private $router;
    
    /**
     *
     * @param Request $request
     * @param Router $router 
     */
    public function __construct(HelperInterface $helper, Router $router) {
        $this->setHelper($helper);
        $this->setRouter($router);
    }
    
    public function buildPage() {
        $page = new WebPage($this->getHelper(), $this->getRouter());
        return $page;
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
}

?>
