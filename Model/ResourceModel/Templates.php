<?php
/**
 * Infobeans_WhatsApp Module
 * @category   Infobeans
 * @package    Infobeans_WhatsApp
 * @version    1.0.0
 * @description Templates Class
 * @author     Infobeans
 * @codingStandardsIgnoreStart
 */
namespace Infobeans\WhatsApp\Model\ResourceModel;

use Infobeans\WhatsApp\Api\Data\TemplatesInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\LocalizedException;

class Templates extends AbstractDb
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';
 
    /**
     * Construct.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        StoreManagerInterface $storeManager,
        MetadataPool $metadataPool,
        $resourcePrefix = null
    ) {

        $this->storeManager = $storeManager;
        $this->metadataPool = $metadataPool;
        parent::__construct($context, $resourcePrefix);
    }
    
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('whatsapp_templates', 'id');
    }

    /**
     * @inheritDoc
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(TemplatesInterface::class)->getEntityConnection();
    }

    /**
     * Check for unique of identifier of template to selected store(s).
     *
     * @param AbstractModel $object
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsUniqueTemplateToStores(AbstractModel $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(TemplatesInterface::class);

        $select = $this->getConnection()->select()
            ->from(['cb' => $this->getMainTable()])
            ->where('cb.identifier = ?', $object->getData('identifier'))
            ->where('cb.store_id = ?', $object->getData('store_id'));

        if ($object->getId()) {
            $select->where('cb.' . $entityMetadata->getIdentifierField() . ' <> ?', $object->getId());
        }

        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }

        return true;
    }
    
    /**
     *  Check whether page identifier is valid
     *
     * @param AbstractModel $object
     * @return bool
     */
    protected function isValidTemplateIdentifier(AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }
    
    /**
     *  Check whether page identifier is numeric
     *
     * @param AbstractModel $object
     * @return bool
     */
    protected function isNumericTemplateIdentifier(AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }
    
    /**
     * Process page data before saving
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (!$this->isValidTemplateIdentifier($object)) {
            throw new LocalizedException(
                __(
                    "The page URL key can't use capital letters or disallowed symbols. "
                    . "Remove the letters and symbols and try again."
                )
            );
        }

        if ($this->isNumericTemplateIdentifier($object)) {
            throw new LocalizedException(
                __("The page URL key can't use only numbers. Add letters or words and try again.")
            );
        }
        if (!$this->getIsUniqueTemplateToStores($object)) {
            throw new LocalizedException(
                __('A Template identifier with the same properties already exists in the selected store.')
            );
        }
        return parent::_beforeSave($object);
    }
}
