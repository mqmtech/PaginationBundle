<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Helper\HelperInterface;
use MQMTech\ToolsBundle\Service\Utils;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Locale\Exception\NotImplementedException;

class WebPagination implements PaginationInterface
{
    const PAGE_INDEX_DEFAULT = 0;

    private $paginationRange = 3;    
    private $requestParamNamespace = '';    
    private $requestPageInfoParam = 'page';
    private $pageIndexDefault = self::PAGE_INDEX_DEFAULT;
    private $currentPageIndex = self::PAGE_INDEX_DEFAULT;
    private $limitPerPage = 10;
    private $pages = array();
    private $totalItems = 0;
    private $pageFactory;
    private $helper;
    private $router;
    private $responsePath;
    private $responseParameters;
    
    public function __construct(HelperInterface $helper, WebPageFactory $pageFactory, RouterInterface $router, $responsePath=null, $responseParameters = null)
    {
        $this->setHelper($helper);
        $this->setPageFactory($pageFactory);
        $this->setResponsePath($responsePath);
        $this->setResponseParameters($responseParameters);
        $this->setRouter($router);
    }
    
    public function paginateQuery($query)
    {
        throw new NotImplementedException('paginateQuery method is not implemented by WebPagination, use QueryPagination concrete class instead');
    }
    
    public function paginate($totalItems) 
    {
        unset($this->pages);
        $this->pages = array();
        
        return $this->init($totalItems);        
    }
    
    private function init($totalItems) 
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
            $pagesQuantity+=1;
        }
        for ($pageIndex = 0; $pageIndex < $pagesQuantity; $pageIndex++) {
            $page = $this->generatePageByPageInfo($pageIndex);            
            $this->pages[$pageIndex] = $page;            
        }
    }
    
    private function generatePageByPageInfo($pageInfo)
    {
        $offset = $this->getLimitPerPage() * $pageInfo;
        $limit = $offset + $this->getLimitPerPage();
        if ($limit > $this->getTotalItems()) {
            $limit = $this->getTotalItems();
        }            
        $page = $this->pageFactory->buildPage();
        $page->setId($pageInfo);
        $page->setOffset($offset);
        $page->setLimit($limit);
        $url = $this->generateURLByPageInfo($pageInfo);
        $page->setURL($url);
        
        return $page;
    }
    
    private function generateURLByPageInfo($pageInfo)
    {
        $url = "no_url";
        $parameters = $this->getResponseParameters();
        if ($parameters == null) {
            $parameters = $this->getHelper()->getAllParametersFromRequestAndQuery();
        }
        $parameters[$this->getRequestPageIndexParamWithNamespace()] = $pageInfo;
        if ($this->getResponsePath() == null) {
            $path = $this->getHelper()->getURI();
            $url = $path . $this->getHelper()->toQueryString($parameters);
        }
        else {
            $url = $this->getRouter()->generate($this->getResponsePath(), $parameters);
        }
        
        return $url;
    }
    
    private function determineCurrentPage()
    {
        if ($this->getPagesQuantity() > 0) {
            $query = $this->getHelper()->getParametersByRequestMethod();       
            $currentPage = $query->get($this->getRequestPageIndexParamWithNamespace()) == null ? $this->pageIndexDefault : $query->get($this->getRequestPageIndexParamWithNamespace());
            $lastPage = count($this->getPages()) -1;
            if ($currentPage > $lastPage) {
                $currentPage = $lastPage;
            }
            else if ($currentPage <= 0) {
                $currentPage = 0;
            }
            if (isset ($this->pages[$currentPage])) {
                $this->pages[$currentPage]->setIsCurrent(true);
                $this->setCurrentPageIndex($currentPage);
            }
        }  
    }
    
    private function getRequestPageIndexParamWithNamespace()
    {
        $requestPageParamWithNamespace = $this->requestParamNamespace . $this->requestPageInfoParam;
        
        return $requestPageParamWithNamespace;
    }
    
    public function getPaginatedElements($array)
    {
        if ($array == null) {
            return null;
        }
        $currentPage = null;
        if (isset($this->pages[$this->getCurrentPageIndex()])) {
             $currentPage = $this->pages[$this->getCurrentPageIndex()];
        }
        else {
            return $array;
        }
        if (is_array($array)) {
             $array = array_slice($array, $currentPage->getOffset(), $this->getLimitPerPage());
        }
        else if (is_a($array, 'Doctrine\ORM\PersistentCollection')) {
                $array->slice($currentPage->getOffset(), $this->getLimitPerPage());
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
        $start = $this->getCurrentPageIndex() - $this->paginationRange;
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
    
    public function getPagesQuantity()
    {
        if ($this->pages == null) {
            return 0;
        }
        
        return count($this->pages);
    }
    
    public function getCurrentPage()
    {
        $currentPageIndex = $this->getCurrentPageIndex();
        if (isset($this->pages[$currentPageIndex])) {
            return $this->pages[$currentPageIndex];
        }
         
        return null;
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

    protected function getTotalItems() 
    {
        return $this->totalItems;
    }

    protected function setTotalItems($totalItems) 
    {
        $this->totalItems = $totalItems;
    }
    
    protected function getResponsePath() 
    {
        return $this->responsePath;
    }

    protected function setResponsePath($responsePath) 
    {
        $this->responsePath = $responsePath;
    }

    protected function getResponseParameters() 
    {
        return $this->responseParameters;
    }

    protected function setResponseParameters($responseParameters) 
    {
        $this->responseParameters = $responseParameters;
    }
    
    protected function getCurrentPageIndex() 
    {
        return $this->currentPageIndex;
    }
    
    protected function setCurrentPageIndex($pageIndex) 
    {
        $this->currentPageIndex = $pageIndex;
    }

    protected function getHelper()
    {
        return $this->helper;
    }

    protected function setHelper($helper)
    {
        $this->helper = $helper;
    }
    
    protected function setPageFactory(PageFactoryInterface $pageFactory) 
    {
        $this->pageFactory = $pageFactory;
    }
    
    protected function getRouter()
    {
        return $this->router;
    }

    protected function setRouter($router)
    {
        $this->router = $router;
    }
}