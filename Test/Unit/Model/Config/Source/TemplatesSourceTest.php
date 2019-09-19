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
        $this->templateModel = $this->getMockBuilder(TemplateModel::class)
            ->setMethods(['getTitle', 'getId'])
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
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
                ->willReturn($this->collection);
        
        $this->collection->expects($this->any())->method('getIterator')->will(
            $this->returnValue(new \ArrayIterator([$this->templateModel]))
        );
       
        $this->templateModel->expects($this->any())
                ->method('getId')
                ->willReturn(1);
        
        $this->templateModel->expects($this->any())
                ->method('getTitle')
                ->willReturn(__("Test Template"));

        $this->assertEquals([['value' =>1, "label" => __("Test Template")]],$this->templates->toOptionArray());
    }

}