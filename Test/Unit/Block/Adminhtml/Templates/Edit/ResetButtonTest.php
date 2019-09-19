<?php

namespace Infobeans\WhatsApp\Unit\Test\Block\Adminhtml\Templates\Edit;

use Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit\ResetButton;
use Magento\Backend\Block\Widget\Context;

/**
 * @covers Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit\ResetButton
 */
class ResetButtonTest extends \PHPUnit\Framework\TestCase
{
    public function testgetButtonData()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        
        $block = $objectManager->getObject(
            ResetButton::class
        );
        
        $expected = [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30
        ];
        $this->assertEquals($expected, $block->getButtonData());
    }
}