<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use MQM\Bundle\PaginationBundle\Helper\HelperInterface;

class WebPage implements PageInterface
{    
    private $id = 0;
    private $offset = null;
    private $limit = null;
    private $isCurrent = false;
    private $url;
    
    public function getURL()
    {
        return $this->url;
    }
    
    public function setURL($url)
    {
        $this->url = $url;
    }
    
    public function getRange()
    {
        return array(
            'offset' => $this->getOffset(),
            'limit' => $this->getLimit(),
            'length' => ( $this->getLimit() - $this->getOffset() )
        );
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getIsCurrent()
    {
        return $this->isCurrent;
    }
    
    public function getLimit()
    {
        return $this->limit;
    }
    
    public function getOffset()
    {
        return $this->offset;
    }
    
    public function setIsCurrent($isCurrent)
    {
        $this->isCurrent=$isCurrent;
    }
    
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
    
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }
}
