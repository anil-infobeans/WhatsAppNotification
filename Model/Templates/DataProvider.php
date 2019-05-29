<?php

namespace Infobeans\WhatsApp\Model\Templates;

use \Infobeans\WhatsApp\Model\ResourceModel\Templates\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $templates = $this->collectionFactory->create()->getItems();
        $this->loadedData = [];
        foreach ($templates as $template) {
            $this->loadedData[$template->getId()] = $template->getData();
        }

        return $this->loadedData;
    }
}
