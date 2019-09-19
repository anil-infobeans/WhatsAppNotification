<?php
/**
 * Infobeans_WhatsApp Module
 * @category   Infobeans
 * @package    Infobeans_WhatsApp
 * @version    1.0.0
 * @description Collection Class
 * @author     Infobeans
 * @codingStandardsIgnoreStart
 */
namespace Infobeans\WhatsApp\Model\ResourceModel\Templates;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     * @codeCoverageIgnoreStart
     */
    protected function _construct()
    {
        $this->_init(
            \Infobeans\WhatsApp\Model\Templates::class,
            \Infobeans\WhatsApp\Model\ResourceModel\Templates::class
        );
    }
}
