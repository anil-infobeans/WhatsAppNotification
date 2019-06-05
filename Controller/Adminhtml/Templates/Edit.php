<?php
/**
 * Infobeans_WhatsApp Module
 * @category   Infobeans
 * @package    Infobeans_WhatsApp
 * @version    1.0.0
 * @description Edit Class
 * @author     Infobeans
 * @codingStandardsIgnoreStart
 */
namespace Infobeans\WhatsApp\Controller\Adminhtml\Templates;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Infobeans\WhatsApp\Model\TemplatesFactory;

/**
 * @codingStandardsIgnoreStart
 */
class Edit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Infobeans_WhatsApp::save';
    /**
     * @var \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @var \Infobeans\PushNotification\Model\TemplatesFactory $templateFactory
     */
    protected $templateFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        TemplatesFactory $templateFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->templateFactory = $templateFactory;
        parent::__construct($context);
    }

    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Infobeans_WhatsApp::templates');
        return $resultPage;
    }
   
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->templateFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This template no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        
        $resultPage->getConfig()->getTitle()->prepend(__('Templates'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Create Template'));
        return $resultPage;
    }
}
