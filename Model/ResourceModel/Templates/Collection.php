<?php

namespace Infobeans\WhatsApp\Model\ResourceModel\Templates;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            \Infobeans\WhatsApp\Model\Templates::class,
            \Infobeans\WhatsApp\Model\ResourceModel\Templates::class
        );
    }
}
