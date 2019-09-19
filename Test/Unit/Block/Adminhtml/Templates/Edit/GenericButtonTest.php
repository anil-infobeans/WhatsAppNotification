<?php

namespace Infobeans\WhatsApp\Unit\Test\Block\Adminhtml\Templates\Edit;

use Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit\GenericButton;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Framework\Exception\NoSuchEntityException;


/**
 * @covers Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit\GenericButton
 */
class GenericButtonTest extends \PHPUnit\Framework\TestCase {

    protected function setUp() {
        
        $this->urlBuilder = $this->getMockForAbstractClass(\Magento\Framework\UrlInterface::class);
        
        $this->request = $this->getMockForAbstractClass(\Magento\Framework\App\RequestInterface::class);
        $this->blockRepository = $this->getMockBuilder(\Magento\Cms\Api\BlockRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->context = $this->getMockBuilder(\Magento\Backend\Block\Widget\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $objectManagerHelper = new ObjectManagerHelper($this);

        $this->genericButton = $objectManagerHelper->getObject(
            GenericButton::class,
            [
                'urlBuilder' => $this->urlBuilder,
                'request' => $this->request,
                'blockRepository' => $this->blockRepository,
                'context' => $this->context
            ]
        );
    }
    
    public function testgetTemplateId()
    {
        $templateId = 1;
        
        $this->context->expects($this->atLeastOnce())->method('getRequest')
            ->willReturn($this->request);
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
        
        $this->assertEquals(1, (int)$this->genericButton->getTemplateId());
    }
    
    public function testGetUrl() {
        $this->context->expects($this->atLeastOnce())->method('getUrlBuilder')
            ->willReturn($this->urlBuilder);
        $this->urlBuilder->expects($this->atLeastOnce())->method('getUrl')->with('*/*/delete', ['id'=>1])
            ->willReturn("https://testurl.com");
        $this->assertEquals('https://testurl.com',$this->genericButton->getUrl('*/*/delete', ['id'=>1]));
    }
    
    
    public function testgetTemplateIdWExp(){
        $templateId = 0;
        
        $this->context->expects($this->atLeastOnce())->method('getRequest')
            ->willReturn($this->request);
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
            ->willThrowException(new NoSuchEntityException);
        
        $this->assertNull($this->genericButton->getTemplateId());
    }

}
