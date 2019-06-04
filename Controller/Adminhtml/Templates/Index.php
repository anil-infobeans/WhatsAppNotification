<?php
 
namespace Infobeans\WhatsApp\Controller\Adminhtml\Templates;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * @codingStandardsIgnoreStart
 */
class Index extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Infobeans_WhatsApp::templates';
 
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {

        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Default customer account page
     *
     * @return void
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Infobeans_WhatsApp::whatsapp');
        $resultPage->addBreadcrumb(__('Templates'), __('Templates'));
        $resultPage->addBreadcrumb(__('Templates'), __('Templates'));
        $resultPage->getConfig()->getTitle()->prepend(__('Templates'));
 
        return $resultPage;
    }
}
