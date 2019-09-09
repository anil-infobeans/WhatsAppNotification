<?php

namespace Infobeans\WhatsApp\Test\Unit\Controller\Adminhtml\Templates;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Controller\Adminhtml\Templates\Index;
use Infobeans\WhatsApp\Controller\Adminhtml\Templates\Save;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Backend\App\Action\Context;
use Infobeans\WhatsApp\Model\TemplatesFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;

/**
 * @covers \Magento\Customer\Controller\Adminhtml\Index\Index
 */
class SaveTest extends TestCase
{
    private $object;
    private $dataPersistorInterface;
    private $contexMock;
    private $templatesFactory;

    public function setUp()
    {
        $this->contexMock = $this->getMockBuilder(Context::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->dataPersistorInterface = $this->getMockBuilder(DataPersistorInterface::class)
                ->setMethods(['clear','set'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $this->templatesFactory = $this->getMockBuilder(TemplatesFactory::class)
                ->setMethods(['create','load','setData'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->request = $this->getMockBuilder(RequestInterface::class)
           ->setMethods([
               'getPostValue',
               'getParam'
           ])
           ->getMockForAbstractClass();

        $this->contexMock->expects($this->any())
                ->method('getRequest')
                ->willReturn($this->request);

        $this->redirectFactory = $this->getMockBuilder(RedirectFactory::class)
                ->setMethods([
                    'create','setPath'
                ])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $this->contexMock->expects($this->any())
                ->method('getResultRedirectFactory')
                ->willReturn($this->redirectFactory);

        $this->messageManager = $this->getMockBuilder(ManagerInterface::class)
            ->getMockForAbstractClass();

        $this->contexMock->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManager);

        $this->saveObject = new Save($this->contexMock, $this->dataPersistorInterface, $this->templatesFactory);

    }

    public function testEditInstance()
    {
        $this->assertInstanceOf(Save::class, $this->saveObject);
    }
    
    
    public function testExecutePost()
    {
        $postData = [
            'id' => 1,
            'store_id' => 1,
            'title' => 'Ths is test title',
            'identifier' => 'unique_title_123',
            'is_active' => true,
            'content' => 'This is Test content',
            'back' => false
        ];
        $this->request->expects($this->any())->method('getPostValue')->willReturn($postData);

        $this->redirectFactory->expects($this->any())
                ->method('create')
                ->willReturn($this->redirectFactory);

        $this->templatesFactory->expects($this->any())
                ->method('create')
                ->willReturn($this->templatesFactory);

        $this->request->expects($this->any())->method('getParam')->willReturn(['id'=>1]);

        $templates = $this->getMockBuilder(\Infobeans\WhatsApp\Model\Templates::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->templatesFactory->expects($this->any())
                ->method('load')
                ->willReturn($templates);

        $templates->expects($this->any())->method('setData')->with($postData);

        $templates->expects($this->any())->method('save')->willReturn($templates);

        $this->messageManager->expects($this->any())
           ->method('addErrorMessage')
           ->with(__('You saved the page.'))
           ->willReturnSelf();

        //processResultRedirect
        $this->request->expects($this->any())->method('getParam')->willReturn(['back' => false]);

        $this->dataPersistorInterface->expects($this->any())
                ->method('set')
                ->with('whatsapp_templates',$postData)
                ->willReturnSelf();

        $this->redirectFactory->expects($this->any())
                ->method('create')
                ->willReturn($this->redirectFactory);

        $this->redirectFactory->expects($this->any())
                ->method('setPath')
                ->with('*/*/edit')
                ->willReturn($this->redirectFactory);

        $this->assertEquals($this->redirectFactory ,$this->saveObject->execute());

    }
}
