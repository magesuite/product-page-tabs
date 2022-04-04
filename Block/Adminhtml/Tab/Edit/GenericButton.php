<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Block\Adminhtml\Tab\Edit;

class GenericButton
{
    protected \Magento\Backend\Block\Widget\Context $context;

    protected \Magento\Framework\UrlInterface $urlBuilder;

    protected \Magento\Framework\Registry $registry;

    protected \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository
    ) {
        $this->context = $context;
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
        $this->tabRepository = $tabRepository;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

    public function getTabId()
    {
        $id = (int)$this->context->getRequest()->getParam('tab_id');

        try {
            return $this->tabRepository->getById($id)->getId();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) { //phpcs:ignore
        }

        return null;
    }
}
