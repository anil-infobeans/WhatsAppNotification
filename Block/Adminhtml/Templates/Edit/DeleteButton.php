<?php
/**
 * Infobeans_WhatsApp Module
 * @category   Infobeans
 * @package    Infobeans_WhatsApp
 * @version    1.0.0
 * @description DeleteButton Class
 * @author     Infobeans
 * @codingStandardsIgnoreStart
 */
namespace Infobeans\WhatsApp\Block\Adminhtml\Templates\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 * @package Magento\Customer\Block\Adminhtml\Edit
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getTemplateId()) {
            $data = [
                'label' => __('Delete Template'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * URL to send delete requests to.
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['id' => $this->getTemplateId()]);
    }
}
