<?php

namespace Infobeans\WhatsApp\Unit\Test\Block\Adminhtml\Templates\Edit;

use Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit\BackButton;
use Magento\Backend\Block\Widget\Context;

/**
 * @covers Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit\BackButton
 */
class BackButtonTest extends \PHPUnit\Framework\TestCase
{
    public function testgetButtonData()
    {
        $contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $urlBuilderMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        
        $block = $objectManager->getObject(
            BackButton::class,
            [
                'context' => $contextMock,
            ]
        );
        $contextMock->expects($this->any())
            ->method('getUrlBuilder')
            ->willReturn($urlBuilderMock);
        
        $urlBuilderMock->expects($this->any())
            ->method('getUrl')
            ->willReturn('*/*/');
        $expected = [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", '*/*/'),
            'class' => 'back',
            'sort_order' => 10
        ];
        $this->assertEquals($expected, $block->getButtonData());
    }
}