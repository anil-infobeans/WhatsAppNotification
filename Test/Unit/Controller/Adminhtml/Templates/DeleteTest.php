<?php

namespace Infobeans\WhatsApp\Test\Unit\Controller\Adminhtml\Templates;

use PHPUnit\Framework\TestCase;

use Infobeans\WhatsApp\Controller\Adminhtml\Templates\Delete;
use Magento\Backend\App\Action\Context;
use Infobeans\WhatsApp\Model\TemplatesFactory;
use Infobeans\WhatsApp\Model\Templates;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
/**
 * 
 * @covers \Infobeans\WhatsApp\Controller\Adminhtml\Templates\Delete
 */
class DeleteTest extends TestCase
{
    public function setUp()
    {
        $this->contexMock = $this->getMockBuilder(Context::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->templatesFactory = $this->getMockBuilder(TemplatesFactory::class)
                ->setMethods(['create'])
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->redirectFactory = $this->getMockBuilder(RedirectFactory::class)
                ->setMethods(['create','setPath'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->contexMock->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->redirectFactory);

        $this->request = $this->getMockBuilder(RequestInterface::class)
                ->setMethods(['getParam'])
                ->getMockForAbstractClass();

        $this->contexMock->expects($this->any())
            ->method('getRequest','getMessageManager')
            ->willReturn($this->request);

        $this->messageManager = $this->getMockBuilder(ManagerInterface::class)
            ->setMethods(['addSuccessMessage','addErrorMessage'])
            ->getMockForAbstractClass();

        $this->contexMock->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManager);

        $this->exception = $this->getMockBuilder(\Exception::class)
                ->disableOriginalConstructor()
                ->setMethods(['getMessage'])
                ->getMock();

        $this->deleteObject = new Delete($this->contexMock, $this->templatesFactory);
    }

    public function testDeleteInstance()
    {
        $this->assertInstanceOf(Delete::class, $this->deleteObject);
    }

    public function testExecutePost()
    {
        $templates = $this->getMockBuilder(Templates::class)
                ->disableOriginalConstructor()
                ->getMock();
        $this->redirectFactory->expects($this->any())->method('create')->willReturn($this->redirectFactory);
        
        $post = ['id' => 1];
        $this->stubRequestPostData($post);
        
        $this->templatesFactory->expects($this->any())
                ->method('create')
                ->willReturn($templates);
        
        $templates->expects($this->any())
                ->method('load')
                ->willReturn($templates);
        
        $templates->expects($this->any())
                ->method('delete')
                ->willReturnSelf();
        
        $this->messageManager->expects($this->any())
           ->method('addSuccessMessage')
           ->with(__('You deleted the Template.'))
           ->willReturnSelf();

        $this->redirectFactory->expects($this->any())->method('setPath')
                ->with('*/*/')
                ->willReturnSelf();
        
        $this->assertEquals($this->redirectFactory ,$this->deleteObject->execute());

    }

    public function testExecutePostWithoutPost()
    {
        $templates = $this->getMockBuilder(Templates::class)
                ->disableOriginalConstructor()
                ->getMock();
        $this->redirectFactory->expects($this->any())->method('create')->willReturn($this->redirectFactory);

        $post = ['id' => 0];
        $this->stubRequestPostData($post);

        $this->messageManager->expects($this->any())
           ->method('addErrorMessage')
           ->with(__('We can\'t find a Template to delete.'))
           ->willReturnSelf();

        $this->redirectFactory->expects($this->any())->method('setPath')
                ->with('*/*/')
                ->willReturnSelf();

        $this->assertEquals($this->redirectFactory ,$this->deleteObject->execute());

    }

    public function testExecuteException()
    {
        $errorMsg = 'Can\'t delete the Template';
        $templates = $this->getMockBuilder(Templates::class)
                ->disableOriginalConstructor()
                ->getMock();
        $this->redirectFactory->expects($this->any())->method('create')->willReturn($this->redirectFactory);

        $post = ['id' => 1];
        $this->stubRequestPostData($post);

        $this->templatesFactory->expects($this->any())
                ->method('create')
                ->willReturn($templates);

        $templates->expects($this->any())
                ->method('load')
                ->willReturn($templates);

        $templates->expects($this->any())
                ->method('delete')
                ->willThrowException(new \Exception(__($errorMsg)));

        $this->messageManager->expects($this->any())
            ->method('addErrorMessage')
            ->with($errorMsg)
            ->willReturnSelf();

        $this->exception->expects($this->any())
                ->method('getMessage')
                ->willReturn($errorMsg);

        $this->redirectFactory->expects($this->any())->method('setPath')
                ->with('*/*/edit', ['id' => 1])
                ->willReturnSelf();
        $this->assertEquals($this->redirectFactory ,$this->deleteObject->execute());

    }
    
    /**
    * @param array $post
    */
    private function stubRequestPostData($post)
    {
        $this->request->method('getParam')->willReturnCallback(
            function ($key) use ($post) {
                return $post[$key];
            }
        );
    }
}
