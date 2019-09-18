<?php

namespace Infobeans\WhatsApp\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Model\Templates\DataProvider;
use Infobeans\WhatsApp\Model\ResourceModel\Templates\CollectionFactory;
use Infobeans\WhatsApp\Model\ResourceModel\Templates\Collection;

/**
 * @covers \Infobeans\WhatsApp\Model\Templates\DataProvider
 */
class DataProviderTest extends TestCase
{
    public function setUp()
    {
        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        
        $this->collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems','getId','getData'])
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->dataProvider = $objectManager->getObject(
            DataProvider::class,
                [
                    "collectionFactory" => $this->collectionFactory,
                    "collection" =>  $this->collection
                ]
        );
    }
    
    public function testDataProvider()
    {
        $this->assertInstanceOf(DataProvider::class, $this->dataProvider);
    }
    
    public function testGetData()
    {
        $this->collectionFactory->expects($this->once())
                ->method('create')
                ->willReturn($this->collection);
        $this->collection->expects($this->any())
                ->method('getItems')
                ->willReturn($this->onConsecutiveCalls($this->collection));
        $this->collection->expects($this->any())
                ->method('getId')
                ->willReturnSelf();
        $this->collection->expects($this->any())
                ->method('getData')
                ->willReturnSelf();
        $this->dataProvider->getData();
    }
}