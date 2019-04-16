<?php 
namespace Infobeans\WhatsApp\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    // GENERAL Configuration
    const XML_PATH_ENABLED ='whatsapp/apisetting/enable';

    // USER TEMPLATE configuration
    const XML_SMS_USER_ORDER_PLACE_ENABLE = 'whatsapp/orderplace/enable';
    const XML_SMS_USER_USER_ORDER_PLACE_TEXT = 'whatsapp/orderplace/template';

    protected $storeManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Infobeans\WhatsApp\Model\TemplatesFactory $templateFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->storeManager = $storeManager;
        $this->templateFactory = $templateFactory;
        parent::__construct($context);
    }

    public function getStoreid()
    {
        return $this->storeManager->getStore()->getId();
    }

    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
                self::XML_PATH_ENABLED,
                ScopeInterface::SCOPE_STORE,
                $this->getStoreid()
            );
    }
    public function isEnabledForOrder()
    {
        return $this->scopeConfig->getValue(
                self::XML_SMS_USER_ORDER_PLACE_ENABLE,
                ScopeInterface::SCOPE_STORE,
                $this->getStoreid()
            );
    }

    public function getOrderPlaceTemplate() {
        $templateId = $this->scopeConfig->getValue(
                self::XML_SMS_USER_USER_ORDER_PLACE_TEXT,
                ScopeInterface::SCOPE_STORE,
                $this->getStoreid()
            );
        $template = $this->templateFactory->create();
        return $template->load($templateId)->getContent();
    }

    public function isEnabledForShipment()
    {
        return $this->scopeConfig->getValue(
                self::XML_SMS_USER_ORDER_PLACE_ENABLE,
                ScopeInterface::SCOPE_STORE,
                $this->getStoreid()
            );
    }

    public function getOrderShipTemplate() {
        $templateId = $this->scopeConfig->getValue(
                self::XML_SMS_USER_USER_ORDER_PLACE_TEXT,
                ScopeInterface::SCOPE_STORE,
                $this->getStoreid()
            );
        $template = $this->templateFactory->create();
        return $template->load($templateId)->getContent();
    }

    public function isEnabledForOrderCancel()
    {
        return $this->scopeConfig->getValue(
                self::XML_SMS_USER_ORDER_PLACE_ENABLE,
                ScopeInterface::SCOPE_STORE,
                $this->getStoreid()
            );
    }

    public function getOrderCancelTemplate() {
        $templateId = $this->scopeConfig->getValue(
                self::XML_SMS_USER_USER_ORDER_PLACE_TEXT,
                ScopeInterface::SCOPE_STORE,
                $this->getStoreid()
            );
        $template = $this->templateFactory->create();
        return $template->load($templateId)->getContent();
    }

}