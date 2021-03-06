<?php

namespace MQM\PaginationBundle\Pagination;





class WebPage implements PageInterface
{    
    private $id = 0;
    private $offset = null;
    private $limit = null;
    private $url;
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function setUrl($url)
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
    
    
    public function getLimit()
    {
        return $this->limit;
    }
    
    public function getOffset()
    {
        return $this->offset;
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
