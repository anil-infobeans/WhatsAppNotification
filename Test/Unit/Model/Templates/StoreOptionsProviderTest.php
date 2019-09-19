<?php

namespace Infobeans\WhatsApp\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Model\Templates\StoreOptionsProvider;
use Magento\Store\Model\System\Store;

/**
 * @covers \Infobeans\WhatsApp\Model\Templates\StoreOptionsProvider
 */
class StoreOptionsProviderTest extends TestCase
{
    public function setUp()
    {
        $this->store = $this->getMockBuilder(Store::class)
            ->setMethods(['getStoreValuesForForm'])
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->storeOptionsProvider = $objectManager->getObject(
            StoreOptionsProvider::class
        );
    }
    
    public function testtoOptionArray()
    {
        $this->store->expects($this->any())
            ->method('getStoreValuesForForm')
            ->willReturn(NULL);
        $this->assertNull($this->storeOptionsProvider->toOptionArray());
    }
}