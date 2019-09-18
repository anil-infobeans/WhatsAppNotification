<?php

namespace Infobeans\WhatsApp\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Model\Checkout\LayoutProcessorplugin;
use \Magento\Checkout\Block\Checkout\LayoutProcessor;

/**
 * @covers \Infobeans\WhatsApp\Model\Checkout\LayoutProcessorplugin
 */
class LayoutProcessorpluginTest extends TestCase
{
    public function setUp()
    {
        $this->layoutProcessor = $this->getMockBuilder(LayoutProcessor::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->layoutProcessorplugin = $objectManager->getObject(
            LayoutProcessorplugin::class,
                [
                    "layoutProcessor" => $this->layoutProcessor
                ]
        );
    }
    
    public function testLayoutProcessorplugin()
    {
        $this->assertInstanceOf(LayoutProcessorplugin::class, $this->layoutProcessorplugin);
    }
    
    public function testafterProcess()
    {
        $this->layoutProcessorplugin->afterProcess($this->layoutProcessor, []);
    }
}