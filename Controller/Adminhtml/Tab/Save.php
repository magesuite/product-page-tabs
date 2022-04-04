<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Controller\Adminhtml\Tab;

class Save extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'MageSuite_ProductPageTabs::tab';

    protected \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor;

    protected \MageSuite\ProductPageTabs\Api\Data\TabInterfaceFactory $tabFactory;

    protected \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \MageSuite\ProductPageTabs\Api\Data\TabInterfaceFactory $tabFactory,
        \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->tabFactory = $tabFactory;
        $this->tabRepository = $tabRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $this->setNullForEmptyValues($data);
            $id = (int)$this->getRequest()->getParam('tab_id');
            $model = $this->tabFactory->create();

            if ($id) {
                try {
                    $model = $this->tabRepository->getById($id);
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This tab no longer exists.'));
                    return $resultRedirect->setPath('*/*/index');
                }
            }

            $model->addData($data);

            try {
                $this->tabRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the tab.'));
                $this->dataPersistor->clear('productpagetabs_tab');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['tab_id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/index');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the tab.'));
            }

            $this->dataPersistor->set('productpagetabs_tab', $data);

            return $resultRedirect->setPath('*/*/edit', ['tab_id' => $id]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    protected function setNullForEmptyValues(array &$data): void
    {
        $fields = [
            \MageSuite\ProductPageTabs\Api\Data\TabInterface::TAB_ID,
            \MageSuite\ProductPageTabs\Api\Data\TabInterface::ATTRIBUTE_ID
        ];

        foreach ($fields as $field) {
            if (!empty($data[$field])) {
                continue;
            }

            $data[$field] = null;
        }
    }
}
