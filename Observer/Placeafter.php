<?php

namespace Infobeans\WhatsApp\Observer;

use Magento\Framework\Event\ObserverInterface;
use Infobeans\WhatsApp\Logger\Logger;

class Placeafter implements ObserverInterface {

    protected $objectManager;
    protected $helperdata;
    protected $customerFactory;
    protected $emailfilter;
    /**
     * @var \ICC\Lms\Logger\Logger
     */
    protected $logger;

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Infobeans\WhatsApp\Helper\Data $helperdata,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Email\Model\Template\Filter $filter,
        \Infobeans\WhatsApp\Helper\Apicall $apiHelper,
        Logger $logger
        
    ) {
        $this->orderFactory = $orderFactory;
        $this->helperdata = $helperdata;
        $this->customerFactory = $customerFactory;
        $this->emailfilter = $filter;
        $this->apiHelper = $apiHelper;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            if ($this->helperdata->isEnabled() && $this->helperdata->isEnabledForOrder()) {
                $order_ids = $observer->getData('order_ids');
                $order = $this->orderFactory->create();

                foreach ($order_ids as $key => $order_id) {
                    $order_information = $order->load($order_id);
                    $billingAddress = $order_information->getBillingAddress();
                    $mobilenumber = $billingAddress->getTelephone();
                    if ($order->getCustomerId() > 0) {
                        $customer = $this->customerFactory->create()->load($order_information->getCustomerId());
                        $this->emailfilter->setVariables([
                            'order' => $order,
                            'customer' => $customer,
                            'order_total' => $order->formatPriceTxt($order->getGrandTotal()),
                            'mobilenumber' => $mobilenumber
                        ]);
                    } else {
                        $this->emailfilter->setVariables([
                            'order' => $order,
                            'order_total' => $order->formatPriceTxt($order->getGrandTotal()),
                            'mobilenumber' => $mobilenumber
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
}
