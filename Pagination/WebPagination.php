<?php

namespace MQM\PaginationBundle\Pagination;

use MQM\PaginationBundle\Helper\HelperInterface;
use MQM\PaginationBundle\Pagination\WebPaginationFactory;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Locale\Exception\NotImplementedException;

class WebPagination implements PaginationInterface
{
    const PAGE_INDEX_DEFAULT = 0;

    private $paginationRange = 3;    
    private $requestParamNamespace = '';    
    private $requestPageIndexParamName = 'page';
    private $pageIndexDefault = self::PAGE_INDEX_DEFAULT;
    private $currentPage = null;
    private $limitPerPage = 10;
    private $pages = array();
    private $totalItems = 0;
    private $paginationFactory;
    private $helper;
    private $router;
    private $responsePath;
    private $responseParameters;
    
    public function __construct(HelperInterface $helper, WebPaginationFactory $paginationFactory, RouterInterface $router, $responsePath=null, $responseParameters = null)
    {
        $this->helper = $helper;
        $this->paginationFactory = $paginationFactory;
        $this->router = $router;
        $this->responsePath = $responsePath;
        $this->responseParameters = $responseParameters;        
    }
    
    public function paginateQuery($query)
    {
        throw new NotImplementedException('paginateQuery method is not implemented by WebPagination, use QueryPagination class instead');
    }
   
    public function init($totalItems) 
    {
        if ($totalItems != null) {
            $this->setTotalItems($totalItems);
        }        
        $this->generatePages();
        $this->determineCurrentPage();
        
        return $this;        
    }
    
    private function generatePages()
    {
        $pagesQuantity = $this->getTotalItems() / $this->getLimitPerPage();
        $pagesQuantity = floor($pagesQuantity);        
        if ($this->getTotalItems() > ($pagesQuantity * $this->getLimitPerPage())) {
            $pagesQuantity += 1;
        }
        for ($pageIndex = 0; $pageIndex < $pagesQuantity; $pageIndex++) {
            $page = $this->generatePageByPageIndex($pageIndex);            
            $this->pages[$pageIndex] = $page;            
        }
    }
    
    private function generatePageByPageIndex($pageIndex)
    {
        $offset = $this->getLimitPerPage() * $pageIndex;
        $limit = $offset + $this->getLimitPerPage();
        if ($limit > $this->getTotalItems()) {
            $limit = $this->getTotalItems();
        }            
        $page = $this->paginationFactory->createPage();
        $page->setId($pageIndex);
        $page->setOffset($offset);
        $page->setLimit($limit);
        $url = $this->generateURLByPageIndex($pageIndex);
        $page->setUrl($url);
        
        return $page;
    }
    
    private function generateURLByPageIndex($pageIndex)
    {
        $url = "no_url";
        $parameters = $this->responseParameters;
        if ($parameters == null) {
            $parameters = $this->helper->getAllParametersFromRequestAndQuery();
        }
        $parameters[$this->getRequestPageIndexParamWithNamespace()] = $pageIndex;
        if ($this->responsePath == null) {
            $path = $this->helper->getUri();
            $url = $path . $this->helper->toQueryString($parameters);
        }
        else {
            $url = $this->router->generate($this->responsePath, $parameters);
        }
        
        return $url;
    }
    
    private function determineCurrentPage()
    {
        if ($this->getPagesQuantity() > 0) {
            $query = $this->helper->getParametersByRequestMethod();       
            $currentPageIndex = $query->get($this->getRequestPageIndexParamWithNamespace()) == null ? $this->pageIndexDefault : $query->get($this->getRequestPageIndexParamWithNamespace());
            $lastPage = count($this->getPages()) -1;
            if ($currentPageIndex > $lastPage) {
                $currentPageIndex = $lastPage;
            }
            else if ($currentPageIndex <= 0) {
                $currentPageIndex = 0;
            }
            if (isset ($this->pages[$currentPageIndex]) && $this->getCurrentPage() == null) {
                $currentPage = $this->pages[$currentPageIndex];
                $this->setCurrentPage($currentPage);
            }
        }  
    }
    
    private function getRequestPageIndexParamWithNamespace()
    {
        $requestPageParamWithNamespace = $this->requestParamNamespace . $this->requestPageIndexParamName;
        
        return $requestPageParamWithNamespace;
    }
    
    public function paginateArray($array)
    {
        if ($array == null) {
            return null;
        }
        $currentPage = $this->getCurrentPage();
        if ($currentPage == null) {
            return $array;
        }
        if (is_array($array)) {
            $length = $currentPage->getLimit() - $currentPage->getOffset();
            $array = array_slice($array, $currentPage->getOffset(), $length);
        }
        else if (is_a($array, 'Doctrine\ORM\PersistentCollection')) {
                $array = $array->slice($currentPage->getOffset(), $currentPage->getLimit());
             }
            else {
                //Do nothing                
            }
                 
        return $array;
    }

    public function getPrevPage()
    {
        $currentPageIndex = $this->getCurrentPageIndex();
        if ($currentPageIndex <= 0 ) {
            return $this->pages[$currentPageIndex];
        }
        else {
            return $this->pages[$currentPageIndex - 1];
        }
    }
    
    public function getNextPage()
    {
        $currentPageIndex = $this->getCurrentPageIndex();
        if ($currentPageIndex >= $this->getPagesQuantity() -1) {
            return $this->pages[$currentPageIndex];
        }
        else {
            return $this->pages[$currentPageIndex + 1];
        }
    }

    private function getCurrentPageIndex() {
        $currentPageIndex = 0;
        $currentPage = $this->getCurrentPage();
        for ($i = 0 ; $i < count($this->pages) ; $i++) {
            $aPage = $this->pages[$i];
            if ($aPage == $currentPage) {
                $currentPageIndex = $i;
                break;
            }
        }

        return $currentPageIndex;
    }
    
    public function getFirstPage()
    {
        return $this->pages[0];
    }
    
    public function getLastPage()
    {
       $length = $this->getPagesQuantity();
       return $this->pages[$length -1];
    }
    
    public function getStartRange()
    {
        $currentPageIndex = $this->getCurrentPageIndex();
        $start = $currentPageIndex - $this->paginationRange;
        if ($start < 0 ) {
            $start = 0;
        }
        
        return $start;
    }
    
    public function getEndRange()
    {
        $start = $this->getStartRange();        
        $length = $this->getPagesQuantity();
        $end = $start + ($this->paginationRange * 2);
        if ($end >= $length) {
            $end = $length - 1;
        }
        
        return $end;
    }

    public function setCurrentPage(PageInterface $page)
    {
        $this->currentPage = $page;

        return $this;
    }
    
    public function getCurrentPage()
    {        
        return $this->currentPage;
    }
    
    public function setLimitPerPage($limitPerPage) 
    {
        $this->limitPerPage = $limitPerPage;
    }

    public function getLimitPerPage() 
    {
        return $this->limitPerPage;        
    }
    
    public function getPages() 
    {
        return $this->pages;
    }
    
    public function getPagesQuantity()
    {
        if ($this->pages == null) {
            return 0;
        }
        
        return count($this->pages);
    }    
    
    public function setPaginationRange($paginationRange) {
        $this->paginationRange = $paginationRange;
    }

    public function setRequestParamNamespace($requestParamNamespace) {
        $this->requestParamNamespace = $requestParamNamespace;
    }

    public function setRequestPageIndexParamName($requestPageIndexParamName) {
        $this->requestPageIndexParamName = $requestPageIndexParamName;
    }
    
    public function getTotalItems() 
    {
        return $this->totalItems;
    }

    protected function setTotalItems($totalItems) 
    {
        $this->totalItems = $totalItems;
    }
}