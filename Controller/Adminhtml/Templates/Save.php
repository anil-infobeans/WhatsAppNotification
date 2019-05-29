<?php

namespace Infobeans\WhatsApp\Controller\Adminhtml\Templates;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action\Context;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Infobeans_WhatsApp::save';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Infobeans\WhatsApp\Model\TemplateFactory
     */
    private $templatesFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Infobeans\WhatsApp\Model\TemplatesFactory $templatesFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        \Infobeans\WhatsApp\Model\TemplatesFactory $templatesFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->templatesFactory= $templatesFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['id'])) {
                $data['id'] = null;
            }

            $model = $this->templatesFactory->create();

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                try {
                    $model = $model->load($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This page no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the page.'));
                return $this->processResultRedirect($model, $resultRedirect, $data);
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the page.'));
            }

            $this->dataPersistor->set('whatsapp_templates', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('page_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process result redirect
     * @param type $model
     * @param type $resultRedirect
     * @param type $data
     * @return type
     */
    private function processResultRedirect($model, $resultRedirect, $data)
    {
        if ($this->getRequest()->getParam('back', false) === 'duplicate') {
            $newPage = $this->templatesFactory->create(['data' => $data]);
            $newPage->setId(null);
            $identifier = $model->getIdentifier() . '-' . uniqid();
            $newPage->setIdentifier($identifier);
            $newPage->setIsActive(false);
            $model->save($newPage);
            $this->messageManager->addSuccessMessage(__('You duplicated the page.'));
            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'page_id' => $newPage->getId(),
                    '_current' => true
                ]
            );
        }
        $this->dataPersistor->clear('whatsapp_templates');
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
