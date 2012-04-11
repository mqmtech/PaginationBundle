<?php

namespace MQM\Bundle\PaginationBundle\Pagination;

use MQM\Bundle\PaginationBundle\Helper\HelperInterface;
use MQMTech\ToolsBundle\Service\Utils;
/**
 * Description of WebPagination
 *
 * @author mqmtech
 */
class WebPagination implements PaginationInterface
{
    const DEF_PAGE_LENGTH = 10;
    const DEF_TOTAL_ITEMS = 0;
    const DEF_CURRENT_PAGE= 0;
    const DEF_CURRENT_OFFSET= 0;
    const REQUEST_QUERY_PARAM= 'page';
    const DEF_RANGE_PAGINATION = 3; //+ - 3
    
    /**
     *
     * @var integer $length
     */
    private $pageLength = self::DEF_PAGE_LENGTH;
    
    /**
     *
     * @var array $pages
     */
    private $pages;
    
    /**
     *
     * @var int $currentPageIndex
     */
    private $currentPageIndex = self::DEF_CURRENT_PAGE;
    
    /**
     *
     * @var integer $totalItems
     */
    private $totalItems = self::DEF_TOTAL_ITEMS;
    
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
     * @var string
     */
    private $responsePath;
    
    /**
     *
     * @var array
     */
    private $responseParameters;
    
    public function __construct(HelperInterface $helper, WebPageFactory $pageFactory, $responsePath=null, $responseParameters = null)
    {
        $this->setHelper($helper);
        $this->setPageFactory($pageFactory);
        $this->setResponsePath($responsePath);
        $this->setResponseParameters($responseParameters);
    }
    
    /**
     *
     * @param array $totalItems
     * @return WebPagination 
     */
    public function init($totalItems = null) 
    {
        if ($totalItems != null) {
            $this->setTotalItems($totalItems);
        }        
        $this->generatePages();
        $this->determineTheCurrentPage();
        
        return $this;        
    }
    
    private function generatePages()
    {
        $pagesCount = $this->getTotalItems() / $this->getPageLength();
        $pagesCount = floor($pagesCount);        
        if ($this->getTotalItems() > ($pagesCount * $this->getPageLength())) {
            $pagesCount+=1;
        }

        for ($pageIndex = 0; $pageIndex < $pagesCount; $pageIndex++) {
            $offset = $this->getPageLength() * $pageIndex;
            $limit = $offset + $this->getPageLength();
            if ($limit > $this->getTotalItems()) {
                $limit = $this->getTotalItems();
            }            
            $page = $this->pageFactory->buildPage();
            $page->setId($pageIndex);
            $page->setOffset($offset);
            $page->setLimit($limit);
            $page->setResponsePath($this->getResponsePath());
            $responseParameters = $this->getResponseParameters();
            if ($responseParameters == null) {
                $responseParameters = $this->getHelper()->getAllParametersFromRequestAndQuery();
            }
            $page->setResponseParameters($responseParameters);
            
            if ($this->pages == null) {
                $this->pages = array();
            }
            $this->pages[$pageIndex] = $page;            
        }
    }
    
    private function determineTheCurrentPage()
    {
        if ($this->getPagesQuantity() > 0) {
            //Grab Current WebPage from Request
            $query = $this->getHelper()->getParametersByRequestMethod();       
            $currentPage = $query->get(self::REQUEST_QUERY_PARAM) == null ? self::DEF_CURRENT_PAGE : $query->get(self::REQUEST_QUERY_PARAM);
            //End grabbing curent page from Request
            $lastPage = count($this->getPages()) -1;
            if ($currentPage > $lastPage) {
                $currentPage = $lastPage;
            }
            else if($currentPage <= 0){
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
             $array = array_slice($array, $currentPage->getOffset(), $this->getPageLength());
        }
        else if (is_a($array, 'Doctrine\ORM\PersistentCollection')) {
                $array->slice($currentPage->getOffset(), $this->getPageLength());
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
        
        if( $currentPageIndex >= count($this->getPages()) -1 ){
            return $this->pages[$currentPageIndex];
        }
        else{
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
        $start = $this->getCurrentPageIndex() - self::DEF_RANGE_PAGINATION;
        if ($start < 0 ) {
            $start = 0;
        }
        
        return $start;
    }
    
    public function getEndRange()
    {
        $start = $this->getStartRange();        
        $length = $this->getPagesQuantity();
        $end = $start + (self::DEF_RANGE_PAGINATION * 2);
        if ($end >= $length) {
            $end = $length - 1;
        }
        
        return $end;
    }
    
    /**
     * @return integer 
     */
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
    
    public function getResponsePath() 
    {
        return $this->responsePath;
    }

    public function setResponsePath($responsePath) 
    {
        $this->responsePath = $responsePath;
    }

    public function getResponseParameters() 
    {
        return $this->responseParameters;
    }

    public function setResponseParameters($responseParameters) 
    {
        $this->responseParameters = $responseParameters;
    }
    
    public function setPageLength($pageLength) 
    {
        $this->pageLength = $pageLength;
    }

    public function getPageLength() 
    {
        return $this->pageLength;        
    }
    
    public function getPages() 
    {
        return $this->pages;
    }    

    public function getTotalItems() 
    {
        return $this->totalItems;
    }

    public function setTotalItems($totalItems) 
    {
        $this->totalItems = $totalItems;
    }
    
    public function getCurrentPageIndex() 
    {
        return $this->currentPageIndex;
    }
    
    protected function setCurrentPageIndex($pageIndex) 
    {
        $this->currentPageIndex = $pageIndex;
    }

    protected function getHelper() {
        return $this->helper;
    }

    protected function setHelper($helper) {
        $this->helper = $helper;
    }
    
    protected function setPageFactory(PageFactoryInterface $pageFactory) 
    {
        $this->pageFactory = $pageFactory;
    }
}