<?php
/**
 * Infobeans_WhatsApp Module
 * @category   Infobeans
 * @package    Infobeans_WhatsApp
 * @version    1.0.0
 * @description Apicall Class
 * @author     Infobeans
 * @codingStandardsIgnoreStart
 */
namespace Infobeans\WhatsApp\Helper;

use Magento\Store\Model\ScopeInterface;
use Twilio\Rest\Client;

class Apicall extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_SID ='whatsapp/apisetting/sid';
    const XML_PATH_TOKEN = 'whatsapp/apisetting/token';
    const XML_PATH_SENDER_ID = 'whatsapp/apisetting/sender_id';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface 
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {

        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getSID()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SID,
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    public function getToken()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_TOKEN,
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    public function getSenderId()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SENDER_ID,
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    public function call($mobilenumber, $finalmessage)
    {
        $sid    = $this->getSID();
        $token  = $this->getToken();
        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create(
            "whatsapp:+".$mobilenumber,
            [
               "from" => "whatsapp:+".$this->getSenderId(),
               "body" => $finalmessage
            ]
        );
        return $message->sid;
    }
}
