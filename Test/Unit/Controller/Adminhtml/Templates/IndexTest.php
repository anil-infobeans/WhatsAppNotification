<?php

namespace Infobeans\WhatsApp\Test\Unit\Controller\Adminhtml\Templates;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Controller\Adminhtml\Templates\Index;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * @covers \Infobeans\WhatsApp\Controller\Adminhtml\Templates\Index
 */
class IndexTest extends TestCase
{
    private $object;
    private $resultPageFactory;
    private $contexMock;

    public function setUp()
    {
        $this->contexMock = $this->getMockBuilder(Context::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->Config = $this->getMockBuilder(\Magento\Framework\View\Page\Config::class)
                ->setMethods(['getTitle','prepend'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->resultPageFactory = $this->getMockBuilder(PageFactory::class)
                ->setMethods(['setActiveMenu','create','addBreadcrumb','getConfig'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->object = new Index($this->contexMock, $this->resultPageFactory);

    }

    public function testEditInstance()
    {
        $this->assertInstanceOf(Index::class, $this->object);
    }

    /**
     * test Excute method and verify if it is going to return result page
     */
    public function testExecute()
    {
        /*
         * Need to mock enach method in order to create resultpage object
         */
        $this->resultPageFactory->expects($this->once())
                ->method('create')
                ->willReturn($this->resultPageFactory);

        $this->resultPageFactory->expects($this->once())
                ->method('setActiveMenu')
                ->willReturn($this->resultPageFactory);

        $this->resultPageFactory->expects($this->any())
                ->method('addBreadcrumb')
                ->willReturn($this->resultPageFactory);

        $this->resultPageFactory->expects($this->any())
                ->method('getConfig')
                ->willReturn($this->Config);

        $this->Config->expects($this->any())
                ->method('getTitle')
                ->willReturn($this->Config);

        $this->Config->expects($this->any())
                ->method('prepend')
                ->willReturn($this->resultPageFactory);

        $this->assertInstanceOf(get_class($this->resultPageFactory) ,$this->object->execute());

    }
}
