<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Helper\HelperInterface;
use Symfony\Component\Routing\RouterInterface;
use MQM\Bundle\PaginationBundle\Pagination\WebPagination;
use MQM\Bundle\PaginationBundle\Pagination\WebPage;

class WebPageFactory implements PageFactoryInterface
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