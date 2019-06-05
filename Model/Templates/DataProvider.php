<?php
/**
 * Infobeans_WhatsApp Module
 * @category   Infobeans
 * @package    Infobeans_WhatsApp
 * @version    1.0.0
 * @description DataProvider Class
 * @author     Infobeans
 * @codingStandardsIgnoreStart
 */
namespace Infobeans\WhatsApp\Model\Templates;

use Infobeans\WhatsApp\Model\ResourceModel\Templates\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var \Infobeans\WhatsApp\Model\ResourceModel\Templates\CollectionFactory 
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface 
     */
    protected $storeManager;

    /**
     * @param type $name
     * @param type $primaryFieldName
     * @param type $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param CollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct(
                $name, 
                $primaryFieldName,
                $requestFieldName,
                $reporting,
                $searchCriteriaBuilder,
                $request,
                $filterBuilder,
                $meta,
                $data
            );
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
