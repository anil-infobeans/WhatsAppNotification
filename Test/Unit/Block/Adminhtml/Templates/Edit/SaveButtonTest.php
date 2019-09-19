<?php

namespace Infobeans\WhatsApp\Unit\Test\Block\Adminhtml\Templates\Edit;

use Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit\SaveButton;
use Magento\Ui\Component\Control\Container;

/**
 * @covers Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit\SaveButton
 */
class SaveButtonTest extends \PHPUnit\Framework\TestCase
{
    public function testgetButtonData()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        
        $block = $objectManager->getObject(
            SaveButton::class
        );
        
        $expected = [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'whatsapp_templates_edit.whatsapp_templates_edit',
                                'actionName' => 'save',
                                'params' => [
                                    false
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'class_name' => Container::SPLIT_BUTTON,
            'options' => [
                [
                'id_hard' => 'save_and_close',
                'label' => __('Save & Close'),
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'whatsapp_templates_edit.whatsapp_templates_edit',
                                    'actionName' => 'save',
                                    'params' => [
                                        true
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]
            ],
            'sort_order' => 90,
        ];
        $this->assertEquals($expected, $block->getButtonData());
    }
}