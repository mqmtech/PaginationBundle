<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Test\Sort;

use MQM\Bundle\PaginationBundle\Pagination\WebPageFactory;
use MQM\Bundle\PaginationBundle\Pagination\WebPagination;

class PaginationTest extends \PHPUnit_Framework_TestCase
{   
    public function testMockObject()
    {
        $spec = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request');
        $mock = $spec->getMock();
        $this->assertTrue($mock instanceof \Symfony\Component\HttpFoundation\Request);
    }
    
    public function testHelperMockObject()
    {
        $helperMock = $this->mockHelper();
        
        $this->assertTrue($helperMock instanceof \MQM\Bundle\PaginationBundle\Helper\Helper);
        $this->assertEquals($helperMock->getUri(), '/path/mock');
        $this->assertEquals($helperMock->toQueryString(array('a' => 'b')), '?query=value_mock');
        
    }
    
    public function testWebPagination()
    {
        $webPaginationManager = $this->getWebPagination();
        $this->assertNotNull($webPaginationManager);
        
        $webPaginationManager->paginate(30);    

        $this->assertEquals(0, $webPaginationManager->getCurrentPage()->getId());
        $this->assertEquals(10, $webPaginationManager->getLimitPerPage());
        
        $webPaginationManager->setLimitPerPage(6);
        $firstPage = $webPaginationManager->getFirstPage();
        $this->assertEquals(0, $firstPage->getOffset());
        
        $nextPage = $webPaginationManager->getNextPage();
        $this->assertEquals(10, $nextPage->getOffset());
        $this->assertFalse($nextPage->getIsCurrent());
        $this->assertTrue($firstPage->getIsCurrent());
    }
    
    public function getWebPagination()
    {
        $helper = $this->mockHelper();
        $router = $this->mockRouter();

        $pageFactory = new WebPageFactory($helper, $router);
        $webPagination = new WebPagination($helper, $pageFactory, $router);
        
        return $webPagination;
    }
    
    public function mockHelper()
    {
        // Mock object
        $spec = $this->getMockBuilder('\MQM\Bundle\PaginationBundle\Helper\Helper')
                ->disableOriginalConstructor();
        $helperMock = $spec->getMock();
                
        // Mock methods
        $helperMock->expects($this->any())
                    ->method('getUri')
                    ->will($this->returnValue('/path/mock'));
        
        $helperMock->expects($this->any())
                    ->method('toQueryString')
                    ->will($this->returnValue('?query=value_mock'));
        
        $helperMock->expects($this->any())
                    ->method('getParametersByRequestMethod')
                    ->will($this->returnValue(new \Symfony\Component\HttpFoundation\ParameterBag()));
        
        return $helperMock;
    }
    
    public function mockRouter()
    {        
        $spec = $this->getMockBuilder('\Symfony\Bundle\FrameworkBundle\Routing\Router')
                ->disableOriginalConstructor();
        $mock = $spec->getMock();

        return $mock;        
    }
}
