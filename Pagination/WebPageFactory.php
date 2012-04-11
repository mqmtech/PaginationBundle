<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Helper\HelperInterface;
use Symfony\Component\Routing\Router;

class WebPageFactory implements PageFactoryInterface
{

    public function buildPage()
    {
        $page = new WebPage();
        
        return $page;
    }
}