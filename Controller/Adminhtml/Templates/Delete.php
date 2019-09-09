<?php
/**
 * Infobeans_WhatsApp Module
 * @category   Infobeans
 * @package    Infobeans_WhatsApp
 * @version    1.0.0
 * @description Delete Class
 * @author     Infobeans
 * @codingStandardsIgnoreStart
 */
namespace Infobeans\WhatsApp\Controller\Adminhtml\Templates;

use Magento\Backend\App\Action;
use Infobeans\WhatsApp\Model\TemplatesFactory;

/**
 * @codingStandardsIgnoreStart
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Infobeans_WhatsApp::save';
    /**
     * @var \Magento\Backend\Model\View\Result\Forward
     */
    protected $resultForwardFactory;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Action\Context $context,
        TemplatesFactory $templateFactory
    ) {
        $this->templateFactory = $templateFactory;
        parent::__construct($context);
    }
    /**
     * Forward to edit
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $templateId = $this->getRequest()->getParam('id');
        if ($templateId) {
            try {
                $template = $this->templateFactory->create();
                $template->load($templateId)->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Template.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $templateId]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Template to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
