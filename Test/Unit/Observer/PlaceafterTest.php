<?php

namespace Infobeans\WhatsApp\Test\Unit\Observer;

use PHPUnit\Framework\TestCase;
use Infobeans\WhatsApp\Helper\Data;
use Magento\Email\Model\Template\Filter;
use Infobeans\WhatsApp\Observer\Placeafter;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Customer;
use Infobeans\WhatsApp\Helper\Apicall;
use Magento\Framework\UrlInterface;

/**
 * 
 * @covers \Infobeans\WhatsApp\Controller\Adminhtml\Template\Delete
 */
class PlaceafterTest extends TestCase
{
    /**
    * @var \Infobeans\WhatsApp\Observer\Placeafter
    */
    protected $placeAfter;
    
    /**
     * @var \Infobeans\WhatsApp\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $helperdata;
    
    /**
    * @var \Magento\Framework\Event\Observer|\PHPUnit_Framework_MockObject_MockObject
    */
    protected $observerMock;
    
    /**
     * @var \Magento\Email\Model\Template\Filter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $emailfilter;
    
    /**
     * @var \Magento\Sales\Model\OrderFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $orderFactory;
    
    /**
     * @var \Magento\Customer\Model\CustomerFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerFactory;
    
    /**
     * @var \Infobeans\WhatsApp\Helper\Apicall|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $apiHelper;
    
    /**
     * @var \Magento\Framework\UrlInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlBuilder;
    
    public function setUp()
    {
        $this->helperdata = $this
            ->getMockBuilder(Data::class)
            ->setMethods(['isEnabled', 'isEnabledForOrder', 'getOrderPlaceTemplate'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->emailfilter = $this
            ->getMockBuilder(Filter::class)
            ->setMethods(['filter'])
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->apiHelper = $this
            ->getMockBuilder(Apicall::class)
            ->setMethods(['call'])
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->urlBuilder = $this
            ->getMockBuilder(UrlInterface::class)
            ->setMethods(['getUrl'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        
        $this->observerMock = $this
            ->getMockBuilder(\Magento\Framework\Event\Observer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->placeAfter = $objectManager->getObject(
            Placeafter::class,
            [
                'helperdata' => $this->helperdata,
                'emailfilter' => $this->emailfilter,
                'orderFactory' => $this->orderFactory(),
                'customerFactory' => $this->customerFactory(),
                'apiHelper' => $this->apiHelper,
                'urlBuilder' => $this->urlBuilder
            ]
        );
    }

    public function testPlaceafterInstance()
    {
        $this->assertInstanceOf(Placeafter::class, $this->placeAfter);
    }
    
    public function testExecute() 
    {
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('isEnabled')
            ->willReturn(true);
        
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('isEnabledForOrder')
            ->willReturn(true);
        
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('getOrderPlaceTemplate')
            ->willReturn('This is test');
        
        $this->emailfilter
            ->expects($this->atLeastOnce())
            ->method('filter')
            ->willReturn('This is test');
        
        $this->apiHelper
            ->expects($this->atLeastOnce())
            ->method('call')
            ->willReturn(true);
        
        $this->urlBuilder
            ->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn('sales/order/view/1');
        
        $this->observerMock
            ->expects($this->atLeastOnce())
            ->method('getData')
            ->willReturn(['0' => '11111']);        
        $this->assertTrue($this->placeAfter->execute($this->observerMock));
    }

    public function orderFactory()
    {
        $this->orderFactory = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->setMethods(['load','getBillingAddress', 'getTelephone', 'getCustomerId', 'getGrandTotal','formatPriceTxt'])
            ->getMock();
        
        $this->orderFactory->expects(static::any())
            ->method('load')
            ->willReturnSelf();
        
        $this->orderFactory->expects(static::any())
            ->method('getBillingAddress')
            ->willReturnSelf();
        
        $this->orderFactory->expects(static::any())
            ->method('getGrandTotal')
            ->willReturn(20);
        
        $this->orderFactory->expects(static::any())
            ->method('formatPriceTxt')
            ->willReturn(20);
        
        $this->orderFactory->expects(static::any())
            ->method('getCustomerId')
            ->willReturn(1);
        
        $this->orderFactory->expects(static::any())
            ->method('getTelephone')
            ->willReturn('121212');
        
        $orderLoad = $this->getMockBuilder(OrderFactory::class)
                ->disableOriginalConstructor()
                ->setMethods(['create'])
                ->getMock();

        $orderLoad->expects(static::any())
                ->method('create')
                ->willReturn($this->orderFactory);
        return $orderLoad;
    }
    
    public function customerFactory()
    {
        $this->customerFactory = $this->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->setMethods(['load'])
            ->getMock();
        
        $this->customerFactory->expects(static::any())
            ->method('load')
            ->willReturnSelf();
        
        $cuastomerLoad = $this->getMockBuilder(CustomerFactory::class)
                ->disableOriginalConstructor()
                ->setMethods(['create'])
                ->getMock();

        $cuastomerLoad->expects(static::any())
                ->method('create')
                ->willReturn($this->customerFactory);
        return $cuastomerLoad;
    }
}
