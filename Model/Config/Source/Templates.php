<?php

namespace Infobeans\WhatsApp\Model\Config\Source;

use Infobeans\WhatsApp\Model\ResourceModel\Templates\CollectionFactory;

/**
 * @api
 * @since 100.0.2
 */
class Templates implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        CollectionFactory $collectionFactory
        
    ) {
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];
        $templates = $this->getCollection();
        foreach ($templates as $template) {
            $result[] = ['value' => $template->getId(), 'label' => __($template->getTitle())];
        }
        return $result;
//        return [
//            ['value' => 1, 'label' => __('Yes')],
//            ['value' => 0, 'label' => __('No')],
//            ['value' => 2, 'label' => __('Specified')]
//        ];
    }
    
    public function getCollection(){
        return $collection = $this->collectionFactory->create();
    }
    
    
}
