<?php

namespace Infobeans\WhatsApp\Test\Unit\Controller\Adminhtml\Templates;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Controller\Adminhtml\Templates\NewAction;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;

use Infobeans\WhatsApp\Controller\Adminhtml\Templates\Edit;
use Magento\Framework\View\Result\PageFactory;
use Infobeans\WhatsApp\Model\TemplatesFactory;

/**
 * @covers \Infobeans\WhatsApp\Controller\Adminhtml\Templates\NewAction
 */
class NewActionTest extends TestCase
{
    private $newObject;
    private $editObject;
    private $resultForwardFactory;
    private $contexMock;

    public function setUp()
    {
        $this->contexMock = $this->getMockBuilder(Context::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->resultForwardFactory = $this->getMockBuilder(ForwardFactory::class)
                ->setMethods(['forward','create'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->newObject = new NewAction($this->contexMock, $this->resultForwardFactory);

        $this->resultPageFactory = $this->getMockBuilder(PageFactory::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->templatesFactory = $this->getMockBuilder(TemplatesFactory::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->editObject = new Edit($this->contexMock, $this->resultPageFactory, $this->templatesFactory);

    }
    
    public function testEditInstance()
    {
        $this->assertInstanceOf(NewAction::class, $this->newObject);
    }

    public function testexecute()
    {
        $this->resultForwardFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->resultForwardFactory);

        $this->resultForwardFactory->expects($this->once())
                ->method('forward')
                ->willReturn($this->editObject);

        $this->assertInstanceOf(get_class($this->editObject) ,$this->newObject->execute());

    }
}
