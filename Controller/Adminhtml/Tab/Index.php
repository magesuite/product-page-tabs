<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Controller\Adminhtml\Tab;

class Index extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'MageSuite_ProductPageTabs::tab';

    protected \Magento\Framework\View\Result\PageFactory $pageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $rawFactory
    ) {
        $this->pageFactory = $rawFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Product Page Tabs'));

        return $resultPage;
    }
}
