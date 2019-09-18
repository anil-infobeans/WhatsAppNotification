<?php

namespace Infobeans\WhatsApp\Ui\Component\Listing\Column;

use Infobeans\WhatsApp\Ui\Component\Listing\Column\TemplateActions;

/**
 * Test for Infobeans\WhatsApp\Ui\Component\Listing\Column\TemplateActions class.
 */
class TemplateActionsTest extends \PHPUnit\Framework\TestCase
{
    public function testPrepareItemsByPageId()
    {
        $templateId = 1;
        // Create Mocks and SUT
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        /** @var \PHPUnit_Framework_MockObject_MockObject $urlBuilderMock */
        $urlBuilderMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\ContextInterface::class)
            ->getMockForAbstractClass();
        $processor = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\Processor::class)
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock->expects($this->never())->method('getProcessor')->willReturn($processor);

        /** @var \Magento\Cms\Ui\Component\Listing\Column\PageActions $model */
        $model = $objectManager->getObject(
            \Infobeans\WhatsApp\Ui\Component\Listing\Column\TemplateActions::class,
            [
                'urlBuilder' => $urlBuilderMock,
                'context' => $contextMock,
            ]
        );

        // Define test input and expectations
        $title = 'template title';
        $items = [
            'data' => [
                'items' => [
                    [
                        'id' => $templateId,
                        'title' => $title
                    ]
                ]
            ]
        ];
        $name = 'item_name';
        $expectedItems = [
            [
                'id' => $templateId,
                'title' => $title,
                $name => [
                    'edit' => [
                        'href' => 'test/url/edit',
                        'label' => __('Edit'),
                    ],
                    'delete' => [
                        'href' => 'test/url/delete',
                        'label' => __('Delete'),
                        'confirm' => [
                            'message' => __('Are you sure you want to delete a %1 record?', $templateId)
                        ],
                        'post' => true,
                    ],
                ],
            ],
        ];

        // Configure mocks and object data
        $urlBuilderMock->expects($this->any())
            ->method('getUrl')
            ->willReturnMap(
                [
                    [
                        TemplateActions::URL_PATH_EDIT,
                        [
                            'id' => $templateId
                        ],
                        'test/url/edit',
                    ],
                    [
                        TemplateActions::URL_PATH_DELETE,
                        [
                            'id' => $templateId
                        ],
                        'test/url/delete',
                    ],
                ]
            );

        $model->setName($name);
        $items = $model->prepareDataSource($items);
        // Run test
        $this->assertEquals($expectedItems, $items['data']['items']);
    }
}