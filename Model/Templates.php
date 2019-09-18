<?php
/**
 * Infobeans_WhatsApp Module
 * @category    Infobeans
 * @package     Infobeans_WhatsApp
 * @version     1.0.0
 * @description Templates Class
 * @author      Infobeans
 * @codingStandardsIgnoreStart
 * @codeCoverageIgnoreStart
 */
namespace Infobeans\WhatsApp\Model;

use Infobeans\WhatsApp\Api\Data\TemplatesInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

class Templates extends AbstractModel implements TemplatesInterface, IdentityInterface
{
    const CACHE_TAG = 'whtsapp_template';
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Infobeans\WhatsApp\Model\ResourceModel\Templates::class);
    }
    
    /**
     * Get ID
     * @codeCoverageIgnoreStart
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ID);
    }

    /**
     * Get identifier
     * @codeCoverageIgnoreStart
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * Get title
     * @codeCoverageIgnoreStart
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Get content
     * @codeCoverageIgnoreStart
     * @return string
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Get creation time
     * @codeCoverageIgnoreStart
     * @return string
     */
    public function getCreatedAT()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Get update time
     * @codeCoverageIgnoreStart
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set ID
     * @codeCoverageIgnoreStart
     * @param int $id
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Set identifier
     * @codeCoverageIgnoreStart
     * @param string $identifier
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Set title
     * @codeCoverageIgnoreStart
     * @param string $title
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set content
     * @codeCoverageIgnoreStart
     * @param string $content
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Set creation time
     * @codeCoverageIgnoreStart
     * @param string $createdAt
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get identities
     * @codeCoverageIgnoreStart
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Set updated time
     * @codeCoverageIgnoreStart
     * @param type $updatedAt
     * @return type
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
