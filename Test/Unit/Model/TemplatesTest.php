<?php

namespace Infobeans\WhatsApp\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Model\Templates;

/**
 * @covers \Infobeans\WhatsApp\Model\Templates
 */
class TemplatesTest extends TestCase
{
    public function setUp()
    {
        

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->templates = $objectManager->getObject(
            Templates::class
        );
    }
    
    public function testTemplateInstance()
    {
        $this->assertInstanceOf(Templates::class, $this->templates);
    }

}
