<?php

namespace Infobeans\WhatsApp\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Helper\Data;
use Magento\Framework\App\Helper\Context;
use Infobeans\WhatsApp\Model\TemplatesFactory;
use Infobeans\WhatsApp\Model\Templates;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @covers \Infobeans\WhatsApp\Helper\Data
 */
class DataTest extends TestCase
{
    public function setUp()
    {
        $this->contexMock = $this->getMockBuilder(Context::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->templatesFactory = $this->getMockBuilder(TemplatesFactory::class)
                ->setMethods(['create','load'])
                ->disableOriginalConstructor()
                ->getMock();
        
         $this->templates = $this->getMockBuilder(Templates::class)
                ->setMethods(['getContent'])
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->storeManager = $this->getMockBuilder(StoreManagerInterface::class)
                ->setMethods(['getStore','getId'])
                ->getMockForAbstractClass();
        
        $this->scopeConfig = $this->getMockBuilder(ScopeConfigInterface::class)
                ->setMethods(['getValue'])
                ->getMockForAbstractClass();
        
        $this->contexMock->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->scopeConfig);
        
        $this->dataObject = new Data($this->contexMock, $this->templatesFactory, $this->storeManager);

    }

    public function testEditInstance()
    {
        $this->assertInstanceOf(Data::class, $this->dataObject);
    }

    public function testGetStoreid(){
        $storeId = 1;
        $this->storeManager->expects($this->any())
                ->method('getStore')
                ->willReturnSelf();

        $this->storeManager->expects($this->any())
                ->method('getId')
                ->willReturn(1);
        $this->assertEquals($storeId, $this->dataObject->getStoreid());
    }

    public function testIsEnabled()
    {
        $this->storeManager->expects($this->any())
                ->method('getStore')
                ->willReturnSelf();

        $this->storeManager->expects($this->any())
                ->method('getId')
                ->willReturn(1);

        $this->scopeConfig->expects($this->any())
                ->method('getValue')
                ->willReturn(true);

        $this->assertTrue($this->dataObject->isEnabled());
    }

    public function testIsEnabledForOrder()
    {
        $this->storeManager->expects($this->any())
                ->method('getStore')
                ->willReturnSelf();

        $this->storeManager->expects($this->any())
                ->method('getId')
                ->willReturn(1);

        $this->scopeConfig->expects($this->any())
                ->method('getValue')
                ->willReturn(true);

        $this->assertTrue($this->dataObject->isEnabledForOrder());
    }

    public function testGetOrderPlaceTemplate()
    {
        $templateContent = 'Test Contenet';
        $this->storeManager->expects($this->any())
                ->method('getStore')
                ->willReturnSelf();

        $this->storeManager->expects($this->any())
                ->method('getId')
                ->willReturn(1);

        $this->scopeConfig->expects($this->any())
                ->method('getValue')
                ->willReturn(1);

        $this->templatesFactory->expects($this->any())
                ->method('create')
                ->willReturnSelf();

        $this->templatesFactory->expects($this->once())
                ->method('load')
                ->willReturn($this->templates);

        $this->templates->expects($this->any())
                ->method('getContent')
                ->willReturn($templateContent);

        $this->assertEquals($templateContent, $this->dataObject->getOrderPlaceTemplate());
    }
}
