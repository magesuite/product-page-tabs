<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\ViewModel;

class Product implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected $productTabs = null;

    protected \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository;

    protected \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder;

    protected \Magento\Framework\Api\SortOrder $sortOrder;

    protected \Magento\Framework\Registry $registry;

    protected \MageSuite\ProductPageTabs\Model\ResourceModel\Tab\Value $valueResource;

    public function __construct(
        \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrder $sortOrder,
        \Magento\Framework\Registry $registry,
        \MageSuite\ProductPageTabs\Model\ResourceModel\Tab\Value $valueResource
    ) {
        $this->tabRepository = $tabRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrder = $sortOrder;
        $this->registry = $registry;
        $this->valueResource = $valueResource;
    }

    public function getTabs(): array
    {
        if ($this->productTabs !== null) {
            return $this->productTabs;
        }

        $tabs = [];
        $product = $this->getProduct();
        $storeId = $product->getStoreId();
        $tabValues = $this->valueResource->getTabValueForProduct((int)$product->getId(), $storeId);

        /** @var \MageSuite\ProductPageTabs\Api\Data\TabInterface $tab */
        foreach ($this->getTabList()->getItems() as $tab) {
            $storeLabels = $tab->getStoreLabels();

            if (!isset($storeLabels[$storeId])) {
                continue;
            }

            $label = $storeLabels[$storeId];
            $value = null;

            if (!$tab->getAttributeId() && isset($tabValues[$tab->getTabId()])) {
                $value = $tabValues[$tab->getTabId()];
            } elseif ($tab->getAttributeId()) {
                $attribute = $product->getResource()->getAttribute($tab->getAttributeId());
                $value = $product->getData($attribute->getAttributeCode());
            }

            if (empty($value)) {
                continue;
            }

            $tabs[$label] = $value;
        }

        $this->productTabs = $tabs;

        return $tabs;
    }

    protected function getTabList(): \Magento\Framework\Api\SearchResults
    {
        $sortOrder = $this->sortOrder
            ->setField(\MageSuite\ProductPageTabs\Api\Data\TabInterface::SORT_ORDER)
            ->setDirection(\Magento\Framework\Api\SortOrder::SORT_ASC);
        $searchCriteria = $this->searchCriteriaBuilder->setSortOrders([$sortOrder]);

        return $this->tabRepository->getList($searchCriteria->create());
    }

    public function getProduct(): ?\Magento\Catalog\Model\Product
    {
        return $this->registry->registry('current_product');
    }
}
