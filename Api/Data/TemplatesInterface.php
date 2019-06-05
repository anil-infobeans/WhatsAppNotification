<?php
/**
 * Infobeans_WhatsApp Module
 * @category   Infobeans
 * @package    Infobeans_WhatsApp
 * @version    1.0.0
 * @description TemplatesInterface Class
 * @author     Infobeans
 * @codingStandardsIgnoreStart
 */
namespace Infobeans\WhatsApp\Api\Data;

/**
 * WhatsApp Template interface.
 * @api
 * @since 100.0.2
 */
interface TemplatesInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID                       = 'id';
    const IDENTIFIER               = 'identifier';
    const TITLE                    = 'title';
    const CONTENT                  = 'content';
    const CREATED_AT               = 'created_at';
    const UPDATED_AT               = 'updated_at';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreatedAT();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setId($id);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set title
     *
     * @param string $title
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setTitle($title);

    /**
     * Set content
     *
     * @param string $content
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setContent($content);

    /**
     * Set creation time
     *
     * @param string $createdAt
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Set update time
     *
     * @param string $updatedAt
     * @return \Infobeans\WhatsApp\Api\Data\TemplatesInterface
     */
    public function setUpdatedAt($updatedAt);
}
