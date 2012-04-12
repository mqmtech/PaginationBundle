<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Helper\HelperInterface;
use MQMTech\ToolsBundle\Service\Utils;
use Symfony\Component\Routing\RouterInterface;

class WebPagination implements PaginationInterface
{
    const REQUEST_QUERY_PARAM= 'page';
    const PAGINATION_RANGE = 3;
    const CURRENT_PAGE_INDEX_DEFAULT = 0;
    const LIMIT_PER_PAGE_DEFAULT = 10;
    
    /**
     *
     * @var integer $currentPageIndex
     */
    private $currentPageIndex = self::CURRENT_PAGE_INDEX_DEFAULT;
    
    /**
     *
     * @var integer $length
     */
    private $limitPerPage = self::LIMIT_PER_PAGE_DEFAULT;
    
    /**
     *
     * @var array $pages
     */
    private $pages = array();    
    
    /**
     *
     * @var integer $totalItems
     */
    private $totalItems = 0;
    
    /**
     *
     * @var WebPageFactory
     */
    private $pageFactory;
    
    /**
     *
     * @var HelperInterface
     */
    private $helper;
    
    /**
     *
     * @var RouterInterface
     */
    private $router;
    
    /**
     *
     * @var string
     */
    private $responsePath;
    
    /**
     *
     * @var array
     */
    private $responseParameters;
    
    public function __construct(HelperInterface $helper, WebPageFactory $pageFactory, RouterInterface $router, $responsePath=null, $responseParameters = null)
    {
        $this->setHelper($helper);
        $this->setPageFactory($pageFactory);
        $this->setResponsePath($responsePath);
        $this->setResponseParameters($responseParameters);
        $this->setRouter($router);
    }
    
    public function update($totalItems) 
    {
        unset($this->pages);
        $this->pages = array();
        
        return $this->init($totalItems);        
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
            $pagesQuantity+=1;
        }
        for ($pageIndex = 0; $pageIndex < $pagesQuantity; $pageIndex++) {
            $page = $this->generatePageByPageId($pageIndex);            
            $this->pages[$pageIndex] = $page;            
        }
    }
    
    private function generatePageByPageId($pageId)
    {
        $offset = $this->getLimitPerPage() * $pageId;
        $limit = $offset + $this->getLimitPerPage();
        if ($limit > $this->getTotalItems()) {
            $limit = $this->getTotalItems();
        }            
        $page = $this->pageFactory->buildPage();
        $page->setId($pageId);
        $page->setOffset($offset);
        $page->setLimit($limit);
        $url = $this->generateURLByPageId($pageId);
        $page->setURL($url);
        
        return $page;
    }
    
    private function generateURLByPageId($pageId)
    {
        $url = "no_url";
        $parameters = $this->getResponseParameters();
        if ($parameters == null) {
            $parameters = $this->getHelper()->getAllParametersFromRequestAndQuery();
        }
        $parameters[WebPagination::REQUEST_QUERY_PARAM] = $pageId;
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
            $currentPage = $query->get(self::REQUEST_QUERY_PARAM) == null ? self::CURRENT_PAGE_INDEX_DEFAULT : $query->get(self::REQUEST_QUERY_PARAM);
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
    
    public function slicearray($array)
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
        $start = $this->getCurrentPageIndex() - self::PAGINATION_RANGE;
        if ($start < 0 ) {
            $start = 0;
        }
        
        return $start;
    }
    
    public function getEndRange()
    {
        $start = $this->getStartRange();        
        $length = $this->getPagesQuantity();
        $end = $start + (self::PAGINATION_RANGE * 2);
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