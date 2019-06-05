<?php
/**
 * Infobeans_WhatsApp Module
 * @category    Infobeans
 * @package     Infobeans_WhatsApp
 * @version     1.0.0
 * @description Placeafter Class
 * @author      Infobeans
 * @codingStandardsIgnoreStart
 */
namespace Infobeans\WhatsApp\Observer;

use Magento\Framework\Event\ObserverInterface;
use Infobeans\WhatsApp\Logger\Logger;

class Placeafter implements ObserverInterface
{
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $emailfilter;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Infobeans\WhatsApp\Helper\Data
     */
    protected $helperdata;

    /**
     * @var \Infobeans\WhatsApp\Helper\Apicall
     */
    protected $apiHelper;

    /**
     * @var \ICC\Lms\Logger\Logger
     */
    protected $logger;

    /**
     * URL builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Infobeans\WhatsApp\Helper\Data $helperdata,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Email\Model\Template\Filter $filter,
        \Infobeans\WhatsApp\Helper\Apicall $apiHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        Logger $logger
    ) {
        $this->orderFactory = $orderFactory;
        $this->helperdata = $helperdata;
        $this->customerFactory = $customerFactory;
        $this->_urlBuilder = $urlBuilder;;
        $this->emailfilter = $filter;
        $this->apiHelper = $apiHelper;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            if ($this->helperdata->isEnabled() && $this->helperdata->isEnabledForOrder()) {
                $orderIds = $observer->getData('order_ids');
                $order = $this->orderFactory->create();

                foreach ($orderIds as $key => $orderId) {
                    $orderInformation = $order->load($orderId);
                    $billingAddress = $orderInformation->getBillingAddress();
                    $mobilenumber = $billingAddress->getTelephone();
                    if ($order->getCustomerId() > 0) {
                        $customer = $this->customerFactory->create()->load($orderInformation->getCustomerId());
                        $this->emailfilter->setVariables([
                            'order' => $order,
                            'customer' => $customer,
                            'order_total' => $order->formatPriceTxt($order->getGrandTotal()),
                            'mobilenumber' => $mobilenumber,
                            'view_url' => $this->getViewUrl($order)
                        ]);
                    } else {
                        $this->emailfilter->setVariables([
                            'order' => $order,
                            'order_total' => $order->formatPriceTxt($order->getGrandTotal()),
                            'mobilenumber' => $mobilenumber,
                            'view_url' => $this->getViewUrl($order)
                        ]);
                    }
                    $message = $this->helperdata->getOrderPlaceTemplate();
                    $finalmessage = $this->emailfilter->filter($message);
                    $this->logger->info("Finalmessage : " . $finalmessage);
                    $result  = $this->apiHelper->call($mobilenumber, $finalmessage);
                    $this->logger->info("WhatsApp Number :". $mobilenumber ." Result : " . $result);
                }
            }
        } catch (\Exception $e) {
            $this->logger->info("Error : ".$e->getMessage());
            return true;
        }
    }
    
    /**
     * @param object $order
     * @return string
     */
    private function getViewUrl($order)
    {
        return $this->_urlBuilder->getUrl('sales/order/view', ['order_id' => $order->getId()]);
    }
}
