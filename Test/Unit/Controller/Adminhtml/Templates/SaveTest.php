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
use Magento\Framework\Exception\LocalizedException;

/**
 * @covers \Infobeans\WhatsApp\Controller\Adminhtml\Templates\Save
 */
class SaveTest extends TestCase
{
    private $saveObject;
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
        
        $this->templates = $this->getMockBuilder(\Infobeans\WhatsApp\Model\Templates::class)
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

        $this->templates = $this->getMockBuilder(\Infobeans\WhatsApp\Model\Templates::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->templatesFactory->expects($this->any())
                ->method('load')
                ->willReturn($this->templates);

        $this->templates->expects($this->any())->method('setData')->with($postData);

        $this->templates->expects($this->any())->method('save')->willReturn($this->templates);

        $this->messageManager->expects($this->any())
           ->method('addSuccessMessage')
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
    
    public function testprocessResultRedirect(){
        
        $testMethod = new \ReflectionMethod(
                \Infobeans\WhatsApp\Controller\Adminhtml\Templates\Save::class, 'processResultRedirect'
        );
        $testMethod->setAccessible(true);
        
        $this->dataPersistorInterface->expects($this->any())
                ->method('clear')
                ->with('whatsapp_templates')
                ->willReturnSelf();
        $this->redirectFactory->expects($this->any())
                ->method('setPath')
                ->with('*/*/')
                ->willReturn($this->redirectFactory);
        $this->assertEquals($this->redirectFactory, $testMethod->invoke($this->saveObject, $this->templates, $this->redirectFactory, []));
    }
    public function testExecutePostExep()
    {
        $postData = [
            'id' => null,
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
        $this->templatesFactory->expects($this->any())
                ->method('load')
                ->willThrowException(new LocalizedException(__('This page no longer exists.')));

        $this->messageManager->expects($this->any())
           ->method('addErrorMessage')
           ->with(__('This page no longer exists.'))
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
                ->with('*/*/')
                ->willReturn($this->redirectFactory);

        $this->assertEquals($this->redirectFactory ,$this->saveObject->execute());

    }
    public function testExecutePostLocalExpSave()
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

        $this->templates = $this->getMockBuilder(\Infobeans\WhatsApp\Model\Templates::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->templatesFactory->expects($this->any())
                ->method('load')
                ->willReturn($this->templates);

        $this->templates->expects($this->any())->method('setData')->with($postData);

        $this->templates->expects($this->any())->method('save')
                ->willThrowException(new LocalizedException(__('could not save template.')));

        $this->messageManager->expects($this->any())
           ->method('addExceptionMessage')
           ->with(new LocalizedException(__('could not save template.')))
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
    public function testExecutePostExpSave()
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

        $this->templates = $this->getMockBuilder(\Infobeans\WhatsApp\Model\Templates::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->templatesFactory->expects($this->any())
                ->method('load')
                ->willReturn($this->templates);

        $this->templates->expects($this->any())->method('setData')->with($postData);

        $this->templates->expects($this->any())->method('save')
                ->willThrowException(new \Exception);

        $this->messageManager->expects($this->any())
           ->method('addExceptionMessage')
           ->with(new \Exception)
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
    
    public function testExecuteBlankData()
    {
        $postData = [];
        $this->request->expects($this->any())->method('getPostValue')->willReturn($postData);

        $this->redirectFactory->expects($this->any())
                ->method('create')
                ->willReturn($this->redirectFactory);

        $this->redirectFactory->expects($this->any())
                ->method('setPath')
                ->with('*/*/')
                ->willReturn($this->redirectFactory);

        $this->assertEquals($this->redirectFactory ,$this->saveObject->execute());

    }
}
