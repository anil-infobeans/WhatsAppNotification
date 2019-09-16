<?php

namespace Infobeans\WhatsApp\Test\Unit\Controller\Adminhtml\Templates;

use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Infobeans\WhatsApp\Model\TemplatesFactory;
use Magento\Framework\App\RequestInterface;
use Infobeans\WhatsApp\Controller\Adminhtml\Templates\Edit;
use Infobeans\WhatsApp\Controller\Adminhtml\Templates\Index;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
/**
 * @covers \Magento\Customer\Controller\Adminhtml\Index\Edit
 */
class EditTest extends TestCase
{
    private $editObject;
    private $contexMock;
    private $resultPageFactory;
    private $templatesFactory;
    private $messageManager;

    public function setUp()
    {
        $this->contexMock = $this->getMockBuilder(Context::class)
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->request = $this->getMockBuilder(RequestInterface::class)
           ->setMethods([
               'getParam',
           ])
           ->getMockForAbstractClass();

        $this->contexMock->expects($this->any())
            ->method('getRequest','getMessageManager')
            ->willReturn($this->request);

        $this->Config = $this->getMockBuilder(\Magento\Framework\View\Page\Config::class)
                ->setMethods(['getTitle','prepend'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->resultPageFactory = $this->getMockBuilder(PageFactory::class)
                ->setMethods(['setActiveMenu','create','getConfig','setPath'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->templatesFactory = $this->getMockBuilder(TemplatesFactory::class)
                ->setMethods(['create','load','getId','getTitle'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->messageManager = $this->getMockBuilder(ManagerInterface::class)
            ->getMockForAbstractClass();

        $this->contexMock->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManager);

        $this->redirectFactory = $this->getMockBuilder(RedirectFactory::class)
                ->setMethods(['create', 'setPath'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->contexMock->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->redirectFactory);
       
       $this->editObject = new Edit($this->contexMock, $this->resultPageFactory, $this->templatesFactory);

        
    }

    public function testEditInstance()
    {
        $this->assertInstanceOf(Edit::class, $this->editObject);
    }

    
    /**
     * test Excute method and verify if it is going to return result page
     */
    public function testExecuteWithId()
    {
        /*
         * Need to mock enach method in order to create result page object
         */
        $post = ['id' => 1];
        $this->stubRequestPostData($post);

        $this->templatesFactory->expects($this->any())
                ->method('create')
                ->willReturn($this->templatesFactory);

        $this->templatesFactory->expects($this->any())
                ->method('load')
                ->willReturn($this->templatesFactory);

        $this->templatesFactory->expects($this->any())
                ->method('getId')
                ->willReturn(1);

        $this->templatesFactory->expects($this->any())
                ->method('getTitle')
                ->willReturn("Create Template");

        $this->templatesFactory->expects($this->any())
                ->method('getId')
                ->willReturn(1);

        $this->resultPageFactory->expects($this->any())
                ->method('create')
                ->willReturn($this->resultPageFactory);

         $this->resultPageFactory->expects($this->any())
                ->method('setActiveMenu')
                ->willReturn($this->resultPageFactory);

        $this->resultPageFactory->expects($this->any())
                ->method('getConfig')
                ->willReturn($this->Config);

        $this->Config->expects($this->any())
                ->method('getTitle')
                ->willReturn($this->Config);

        $this->Config->expects($this->any())
                ->method('prepend')
                ->willReturn($this->resultPageFactory);

        $this->assertEquals($this->resultPageFactory ,$this->editObject->execute());
    }
    
    /**
     * test Excute method and verify if it is going to return result page
     */
    public function testExecuteWithoutId()
    {
        /*
         * Need to mock enach method in order to create result page object
         */
        $post = ['id' => 1];
        $this->stubRequestPostData($post);
        
        $this->templatesFactory->expects($this->any())
                ->method('create')
                ->willReturn($this->templatesFactory);
        
        $this->templatesFactory->expects($this->any())
                ->method('load')
                ->willReturn($this->templatesFactory);
        
        $this->templatesFactory->expects($this->any())
                ->method('getId')
                ->willReturn(null);
        $this->messageManager->expects($this->any())
           ->method('addErrorMessage')
           ->with(__('This template no longer exists.'))
           ->willReturnSelf();
        
        $this->redirectFactory->expects($this->any())
                ->method('create')
                ->willReturn($this->redirectFactory);
        
        $this->redirectFactory->expects($this->any())
                ->method('setPath')
                ->with('*/*/')
                ->willReturnSelf();

        $this->assertEquals($this->redirectFactory ,$this->editObject->execute());
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
