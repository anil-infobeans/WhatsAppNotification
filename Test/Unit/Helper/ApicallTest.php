<?php

namespace Infobeans\WhatsApp\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Helper\Apicall;
use Magento\Framework\App\Helper\Context;
use Infobeans\WhatsApp\Model\TemplatesFactory;
use Infobeans\WhatsApp\Model\Templates;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Twilio\Rest\Client;
/**
 * @covers \Infobeans\WhatsApp\Helper\Data
 */
class ApicallTest extends TestCase
{
    public function setUp()
    {
        $this->contexMock = $this->getMockBuilder(Context::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->templatesFactory = $this->getMockBuilder(TemplatesFactory::class)
                ->setMethods(['create','load'])
                ->disableOriginalConstructor()
                ->getMock();
        
         $this->templates = $this->getMockBuilder(Templates::class)
                ->setMethods(['getContent'])
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->storeManager = $this->getMockBuilder(StoreManagerInterface::class)
                ->setMethods(['getStore','getId'])
                ->getMockForAbstractClass();

        $this->resultPageFactory = $this->getMockBuilder(PageFactory::class)
                ->setMethods(['setActiveMenu','create','addBreadcrumb','getConfig'])
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->scopeConfig = $this->getMockBuilder(ScopeConfigInterface::class)
                ->setMethods(['getValue'])
                ->getMockForAbstractClass();
        
        $this->contexMock->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->scopeConfig);

        $this->client = $this->getMockBuilder(Client::class)
                ->setMethods(['getMessages','create'])
                //->setConstructorArgs(["Test1","Test2"])
                ->disableOriginalConstructor()
                ->getMock();

        $this->apiObject = new Apicall($this->contexMock, $this->storeManager);

    }

    public function testApiInstance()
    {
        $this->assertInstanceOf(Apicall::class, $this->apiObject);
    }

    public function testGetStoreid(){
        $storeId = 1;
        $this->storeManager->expects($this->any())
                ->method('getStore')
                ->willReturnSelf();

        $this->storeManager->expects($this->any())
                ->method('getId')
                ->willReturn(1);
        $this->assertEquals($storeId, $this->apiObject->getStoreid());
    }

    public function testGetSID()
    {
        $sid = "qwerty";
        $this->storeManager->expects($this->any())
                ->method('getStore')
                ->willReturnSelf();

        $this->storeManager->expects($this->any())
                ->method('getId')
                ->willReturn(1);

        $this->scopeConfig->expects($this->any())
                ->method('getValue')
                ->willReturn($sid);

        $this->assertSame($sid, $this->apiObject->getSID());
    }
    
    public function testGetToken()
    {
        $token = "asdfghjkl";
        $this->storeManager->expects($this->any())
                ->method('getStore')
                ->willReturnSelf();

        $this->storeManager->expects($this->any())
                ->method('getId')
                ->willReturn(1);

        $this->scopeConfig->expects($this->any())
                ->method('getValue')
                ->willReturn($token);

        $this->assertSame($token, $this->apiObject->getToken());
    }
    
    public function testGetSenderId()
    {
        $senderId = 1;
        $this->storeManager->expects($this->any())
                ->method('getStore')
                ->willReturnSelf();

        $this->storeManager->expects($this->any())
                ->method('getId')
                ->willReturn(1);

        $this->scopeConfig->expects($this->any())
                ->method('getValue')
                ->willReturn($senderId);

        $this->assertSame($senderId, $this->apiObject->getSenderId());
    }
    
    /**
     * Hold this testcase as it contain third party API call
     */
    public function holdtestcall()
    {
        $sid = "qwerty";
        $this->storeManager->expects($this->any())
                ->method('getStore')
                ->willReturnSelf();

        $this->storeManager->expects($this->any())
                ->method('getId')
                ->willReturn(1);

        $this->scopeConfig->expects($this->any())
                ->method('getValue')
                ->willReturn($sid);

        $this->client->expects($this->any())
                ->method('getMessages')
                ->willReturnSelf();
        
        $this->client->expects($this->any())
                ->method('create')
                ->with([
                    "whatsapp:+919405429857",
                    [
                       "from" => "whatsapp:+919405429857",
                       "body" => "This is Test Message"
                    ]
                ])
                ->willReturn(true);

        //print_r($this->apiObject->call('9405429857', "This is Test Message"));

        $this->assertTrue($this->apiObject->call('9405429857', "This is Test Message"));
    }
}
