<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Controller\Adminhtml\Tab;

class Delete extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'MageSuite_ProductPageTabs::tab';

    protected \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository
    ) {
        $this->tabRepository = $tabRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = (int)$this->getRequest()->getParam('tab_id');

        if ($id) {
            try {
                $this->tabRepository->deleteById($id);
                $this->messageManager->addSuccess(__('You deleted the tab.'));

                return $resultRedirect->setPath('*/*/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['tab_id' => $id]);
            }
        }

        $this->messageManager->addError(__('We can\'t find a tab to delete.'));

        return $resultRedirect->setPath('*/*/index');
    }
}
