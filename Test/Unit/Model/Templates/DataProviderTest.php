<?php

namespace Infobeans\WhatsApp\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Model\Templates\DataProvider;
use Infobeans\WhatsApp\Model\ResourceModel\Templates\CollectionFactory;
use Infobeans\WhatsApp\Model\ResourceModel\Templates\Collection;
use Infobeans\WhatsApp\Model\ResourceModel\Templates;

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
        $this->template = $this->getMockBuilder(Template::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems','getId','getData'])
            ->getMock();
        
        $this->collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems','getIterator'])
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
                ->willReturn($this->collection);
        
        $this->collection->expects($this->any())->method('getIterator')->will(
            $this->returnValue(new \ArrayIterator([$this->template]))
        );
        
        $this->template->expects($this->any())
                ->method('getId')
                ->willReturn(0);
        $this->template->expects($this->any())
                ->method('getData')
                ->willReturn("Test Data");
        $this->assertEquals(["Test Data"],$this->dataProvider->getData());
    }
}