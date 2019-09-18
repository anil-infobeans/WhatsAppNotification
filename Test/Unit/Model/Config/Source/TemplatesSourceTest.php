<?php

namespace Infobeans\WhatsApp\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Model\Config\Source\Templates;
use Infobeans\WhatsApp\Model\ResourceModel\Templates\CollectionFactory;
use Infobeans\WhatsApp\Model\ResourceModel\Templates\Collection;

/**
 * @covers \Infobeans\WhatsApp\Model\Config\Source\Templates
 */
class TemplatesSourceTest extends TestCase
{
    public function setUp()
    {
        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        
        $this->collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId','getTitle'])
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->templates = $objectManager->getObject(
            Templates::class,
                [
                    "collectionFactory" => $this->collectionFactory,
                    "collection" =>  $this->collection
                ]
        );
    }
    
    public function testTemplates()
    {
        $this->assertInstanceOf(Templates::class, $this->templates);
    }
    
  
    public function testToOptionArray()
    {
        $this->collectionFactory->expects($this->any())
                ->method('create')
                ->willReturn($this->onConsecutiveCalls($this->collection));
        
        $this->collection->expects($this->any())
                ->method('getId')
                ->willReturn($this->collection);
        
        $this->collection->expects($this->any())
                ->method('getTitle')
                ->willReturn($this->collection);
        
        $this->templates->toOptionArray();
    }

}