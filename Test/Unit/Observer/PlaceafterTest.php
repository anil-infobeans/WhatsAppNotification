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
 * @covers \Infobeans\WhatsApp\Observer\Placeafter
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
        
        $this->order = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->setMethods(['load','getBillingAddress', 'getTelephone', 'getCustomerId', 'getGrandTotal','formatPriceTxt'])
            ->getMock();
        
        $this->orderFactory = $this->getMockBuilder(OrderFactory::class)
                ->disableOriginalConstructor()
                ->setMethods(['create'])
                ->getMock();

        $this->customer = $this->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->setMethods(['load'])
            ->getMock();

        $this->customerFactory = $this->getMockBuilder(CustomerFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->placeAfter = $objectManager->getObject(
            Placeafter::class,
            [
                'helperdata' => $this->helperdata,
                'emailfilter' => $this->emailfilter,
                'orderFactory' => $this->orderFactory,
                'customerFactory' => $this->customerFactory,
                'customer' => $this->customer,
                'apiHelper' => $this->apiHelper,
                'urlBuilder' => $this->urlBuilder
            ]
        );
    }

    public function testPlaceafterInstance()
    {
        $this->assertInstanceOf(Placeafter::class, $this->placeAfter);
    }
    
    public function testExecuteCustomer()
    {
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('isEnabled')
            ->willReturn(true);
        
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('isEnabledForOrder')
            ->willReturn(true);
        
        $this->observerMock
            ->expects($this->atLeastOnce())
            ->method('getData')
            ->willReturn(['0' => '11111']);  
        
        $this->orderFactory->expects(static::any())
            ->method('create')
            ->willReturn($this->order);
        
        $this->order->expects(static::any())
            ->method('load')
            ->willReturnSelf();
        $this->order->expects(static::any())
            ->method('getBillingAddress')
            ->willReturnSelf();
        $this->order->expects(static::any())
            ->method('getTelephone')
            ->willReturn('121212');
        $this->order->expects(static::any())
            ->method('getCustomerId')
            ->willReturn(1);
        
        $this->customerFactory->expects(static::any())
            ->method('create')
            ->willReturn($this->customer);
        
        $this->customer->expects(static::any())
            ->method('load')
            ->willReturnSelf();
        
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('getOrderPlaceTemplate')
            ->willReturn('This is test');
        
        $this->urlBuilder
            ->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn('sales/order/view/1');
        
        $this->order->expects(static::any())
            ->method('getGrandTotal')
            ->willReturn(20);
        
        $this->order->expects(static::any())
            ->method('formatPriceTxt')
            ->willReturn(20);
        
        $this->emailfilter
            ->expects($this->atLeastOnce())
            ->method('filter')
            ->willReturn('This is test');
        
        $this->apiHelper
            ->expects($this->atLeastOnce())
            ->method('call')
            ->willReturn(true);
        
        $this->assertTrue($this->placeAfter->execute($this->observerMock));
    }
    
    public function testExecuteNoCustomer()
    {
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('isEnabled')
            ->willReturn(true);
        
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('isEnabledForOrder')
            ->willReturn(true);
        
        $this->observerMock
            ->expects($this->atLeastOnce())
            ->method('getData')
            ->willReturn(['0' => '11111']);  
        
        $this->orderFactory->expects(static::any())
            ->method('create')
            ->willReturn($this->order);
        
        $this->order->expects(static::any())
            ->method('load')
            ->willReturnSelf();
        $this->order->expects(static::any())
            ->method('getBillingAddress')
            ->willReturnSelf();
        $this->order->expects(static::any())
            ->method('getTelephone')
            ->willReturn('121212');
        $this->order->expects(static::any())
            ->method('getCustomerId')
            ->willReturn(0);
        
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('getOrderPlaceTemplate')
            ->willReturn('This is test');
        
        $this->urlBuilder
            ->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn('sales/order/view/1');
        
        $this->order->expects(static::any())
            ->method('getGrandTotal')
            ->willReturn(20);
        
        $this->order->expects(static::any())
            ->method('formatPriceTxt')
            ->willReturn(20);
        
        $this->emailfilter
            ->expects($this->atLeastOnce())
            ->method('filter')
            ->willReturn('This is test');
        
        $this->apiHelper
            ->expects($this->atLeastOnce())
            ->method('call')
            ->willReturn(true);
        
        $this->assertTrue($this->placeAfter->execute($this->observerMock));
    }

    public function testExecuteException()
    {
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('isEnabled')
            ->willReturn(true);
        
        $this->helperdata
            ->expects($this->atLeastOnce())
            ->method('isEnabledForOrder')
            ->willReturn(true);
        
        $this->observerMock
            ->expects($this->atLeastOnce())
            ->method('getData')
            ->willThrowException(new \Exception);
        $this->assertTrue($this->placeAfter->execute($this->observerMock));
    }
}
