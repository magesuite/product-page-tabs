<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Controller\Adminhtml\Tab;

class Edit extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'MageSuite_ProductPageTabs::tab';

    protected \Magento\Framework\View\Result\PageFactory $resultPageFactory;

    protected \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository;

    protected \Magento\Framework\Registry $coreRegistry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->tabRepository = $tabRepository;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('tab_id');

        if ($id) {
            $model = $this->tabRepository->getById($id);

            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This tab no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }

            $this->coreRegistry->register(\MageSuite\ProductPageTabs\Model\RegistryConstants::CURRENT_PRODUCT_PAGE_TAB, $model);
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Tab') : __('New Tab'),
            $id ? __('Edit Tab') : __('New Tab')
        );
        $resultPage->getConfig()->getTitle()
            ->prepend($id ?  __('Edit Tab') : __('New Tab'));

        return $resultPage;
    }
}
