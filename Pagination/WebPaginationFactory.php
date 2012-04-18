<?php

namespace MQM\PaginationBundle\Pagination;

use MQM\PaginationBundle\Helper\HelperInterface;
use Symfony\Component\Routing\RouterInterface;
use MQM\PaginationBundle\Pagination\WebPagination;
use MQM\PaginationBundle\Pagination\WebPage;

class WebPaginationFactory implements PaginationFactoryInterface
{
    private $helper;
    private $router;    
    
    public function __construct(HelperInterface $helper, RouterInterface $router)
    {
        $this->helper = $helper;
        $this->router = $router;
    }

    public function createPaginationManager($responsePath = null, array $responseParameters = null)
    {
        $paginationManager = new WebPagination($this->helper, $this, $this->router, $responsePath, $responseParameters);
        
        return $paginationManager;
    }
            
    public function createPage()
    {
        $page = new WebPage();
        
        return $page;
    }
}