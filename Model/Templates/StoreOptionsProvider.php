<?php
/**
 * Infobeans_WhatsApp Module
 * @category    Infobeans
 * @package     Infobeans_WhatsApp
 * @version     1.0.0
 * @description StoreOptionsProvider Class
 * @author      Infobeans
 * @codingStandardsIgnoreStart
 */
namespace Infobeans\WhatsApp\Model\Templates;

class StoreOptionsProvider implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    private $store;

    /**
     * @param \Magento\Store\Model\System\Store $store
     */
    public function __construct(\Magento\Store\Model\System\Store $store)
    {
        $this->store = $store;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->store->getStoreValuesForForm();
    }
}
