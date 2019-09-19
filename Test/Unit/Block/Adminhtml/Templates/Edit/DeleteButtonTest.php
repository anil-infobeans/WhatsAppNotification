<?php

namespace Infobeans\WhatsApp\Unit\Test\Block\Adminhtml\Templates\Edit;

use Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit\DeleteButton;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;


/**
 * @covers Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit\DeleteButton
 */
class DeleteButtonTest extends \PHPUnit\Framework\TestCase {

    protected function setUp() {
        
        $this->urlBuilder = $this->getMockForAbstractClass(\Magento\Framework\UrlInterface::class);
        
        $this->request = $this->getMockForAbstractClass(\Magento\Framework\App\RequestInterface::class);
        $this->blockRepository = $this->getMockBuilder(\Magento\Cms\Api\BlockRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $objectManagerHelper = new ObjectManagerHelper($this);

        $this->deleteButton = $objectManagerHelper->getObject(
            DeleteButton::class,
            [
                'urlBuilder' => $this->urlBuilder,
                'request' => $this->request,
                'blockRepository' => $this->blockRepository,
            ]
        );
    }
    
    public function testGetButtonData()
    {
        $templateId = 1;
        
        $this->request->expects($this->atLeastOnce())->method('getParam')->with('id')
            ->willReturn($templateId);
        
        $blockMock = $this->getMockBuilder(
            \Magento\Cms\Model\Block::class
        )->disableOriginalConstructor()->setMethods(['getId'])->getMock();
        
        $this->blockRepository->expects($this->any())
            ->method('getById')
            ->with($templateId)
            ->willReturn($blockMock);
        
        $blockMock->expects($this->any())
            ->method('getId')
            ->willReturn($templateId);
     
        $this->urlBuilder->expects($this->atLeastOnce())->method('getUrl')
            ->with(
               '*/*/delete',
                [ 'id' => $templateId]
            )->willReturn('url');

        $buttonData = $this->deleteButton->getButtonData();
        $this->assertEquals('Delete Template', (string)$buttonData['label']);
    }

}
