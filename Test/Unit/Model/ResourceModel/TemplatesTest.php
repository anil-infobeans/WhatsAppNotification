<?php

namespace Infobeans\WhatsApp\Test\Unit\Model\ResourceModel;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Model\ResourceModel\Templates;
use Magento\Framework\EntityManager\MetadataPool;
use Infobeans\WhatsApp\Api\Data\TemplatesInterface;
use Magento\Framework\EntityManager\EntityMetadataInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * @covers \Infobeans\WhatsApp\Model\ResourceModel\Templates
 */
class TemplatesTest extends TestCase
{
    public function setUp()
    {
        $this->abstarctModel = $this
            ->getMockBuilder(AbstractModel::class)
            ->setMethods(['getId','getData'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        
        $this->metadataPool = $this
            ->getMockBuilder(MetadataPool::class)
            ->setMethods(['getMetadata'])
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->entityMetadataInterface = $this
            ->getMockBuilder(EntityMetadataInterface::class)
            ->setMethods(['getEntityConnection'])
            ->getMockForAbstractClass();
        
        $this->adapterInterface = $this
            ->getMockBuilder(AdapterInterface::class)
            ->setMethods(['select', 'from', 'where','fetchRow'])
            ->getMockForAbstractClass();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->templates = $objectManager->getObject(
            Templates::class,
            [
                'metadataPool' => $this->metadataPool,
                'abstarctModel' => $this->abstarctModel
                
            ]
        );
    }
    
    public function testPlaceafterInstance()
    {
        $this->assertInstanceOf(Templates::class, $this->templates);
    }
    
    public function testGetConnection()
    {
        $this->metadataPool
                ->expects($this->any())
                ->method('getMetadata')
                ->with(TemplatesInterface::class)
                ->willReturn($this->entityMetadataInterface);
        
        $this->entityMetadataInterface
                ->expects($this->any())
                ->method('getEntityConnection')
                ->willReturnSelf();

        $this->assertInstanceOf(get_class($this->entityMetadataInterface), $this->templates->getConnection());
    }
    
    public function testGetIsUniqueTemplateToStoresTrue()
    {
        $this->metadataPool
                ->expects($this->any())
                ->method('getMetadata')
                ->with(TemplatesInterface::class)
                ->willReturn($this->entityMetadataInterface);

        $this->entityMetadataInterface
                ->expects($this->any())
                ->method('getEntityConnection')
                ->willReturn($this->adapterInterface);

        $this->adapterInterface
                ->expects($this->any())
                ->method('select')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('from')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('where')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('fetchRow')
                ->willReturn([]);
        $this->assertTrue($this->templates->getIsUniqueTemplateToStores($this->abstarctModel));
    }
    
    public function testGetIsUniqueTemplateToStoresFalse()
    {
        $this->metadataPool
                ->expects($this->any())
                ->method('getMetadata')
                ->with(TemplatesInterface::class)
                ->willReturn($this->entityMetadataInterface);

        $this->entityMetadataInterface
                ->expects($this->any())
                ->method('getEntityConnection')
                ->willReturn($this->adapterInterface);

        $this->adapterInterface
                ->expects($this->any())
                ->method('select')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('from')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('where')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('fetchRow')
                ->willReturn(1);
        $this->assertFalse($this->templates->getIsUniqueTemplateToStores($this->abstarctModel));
    }
    
    public function testGetIsUniqueTemplateToStoresTrueWId()
    {
        $this->metadataPool
                ->expects($this->any())
                ->method('getMetadata')
                ->with(TemplatesInterface::class)
                ->willReturn($this->entityMetadataInterface);

        $this->entityMetadataInterface
                ->expects($this->any())
                ->method('getEntityConnection')
                ->willReturn($this->adapterInterface);

        $this->adapterInterface
                ->expects($this->any())
                ->method('select')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('from')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('where')
                ->willReturnSelf();
        $this->abstarctModel
                ->expects($this->any())
                ->method('getId')
                ->willReturn(1);
        $this->adapterInterface
                ->expects($this->any())
                ->method('fetchRow')
                ->willReturn(1);
        $this->assertFalse($this->templates->getIsUniqueTemplateToStores($this->abstarctModel));
    }
    
    public function testIsValidTemplateIdentifier()
    {
        $expectedResult = true;
        $testMethod = new \ReflectionMethod(
                \Infobeans\WhatsApp\Model\ResourceModel\Templates::class, 'isValidTemplateIdentifier'
        );
        $testMethod->setAccessible(true);
        $this->abstarctModel
                ->expects($this->any())
                ->method('getData')
                ->with('identifier')
                ->willReturn('new_order');
        
        $this->assertEquals($expectedResult, $testMethod->invoke($this->templates, $this->abstarctModel));
    }
    public function testIsNumericTemplateIdentifier()
    {
        $expectedResult = true;
        $testMethod = new \ReflectionMethod(
                \Infobeans\WhatsApp\Model\ResourceModel\Templates::class, 'isNumericTemplateIdentifier'
        );
        $testMethod->setAccessible(true);
        $this->abstarctModel
                ->expects($this->any())
                ->method('getData')
                ->with('identifier')
                ->willReturn(1);
        
        $this->assertEquals($expectedResult, $testMethod->invoke($this->templates, $this->abstarctModel));
    }

    public function testBeforeSaveInvalidTemplateindentifier()
    {
        $testMethod = new \ReflectionMethod(
                \Infobeans\WhatsApp\Model\ResourceModel\Templates::class, '_beforeSave'
        );
        $testMethod->setAccessible(true);
        
        $this->abstarctModel
                ->expects($this->any())
                ->method('getData')
                 ->withConsecutive(['identifier'],['identifier'],['identifier'],['store_id'])
                ->willReturnOnConsecutiveCalls('new_order2','new_order2','new_order2', 1);
  
        $this->metadataPool
                ->expects($this->any())
                ->method('getMetadata')
                ->with(TemplatesInterface::class)
                ->willReturn($this->entityMetadataInterface);

        $this->entityMetadataInterface
                ->expects($this->any())
                ->method('getEntityConnection')
                ->willReturn($this->adapterInterface);

        $this->adapterInterface
                ->expects($this->any())
                ->method('select')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('from')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('where')
                ->willReturnSelf();

        $this->adapterInterface
                ->expects($this->any())
                ->method('fetchRow')
                ->willReturn(NULL);
        $this->assertEquals(Templates::class,get_class($testMethod->invoke($this->templates, $this->abstarctModel)));
    }
}
