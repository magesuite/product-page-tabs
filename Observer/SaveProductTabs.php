<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Observer;

class SaveProductTabs implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\ProductPageTabs\Model\ResourceModel\Tab\Value $valueResource;

    public function __construct(\MageSuite\ProductPageTabs\Model\ResourceModel\Tab\Value $valueResource)
    {
        $this->valueResource = $valueResource;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        $controller = $observer->getController();
        $product = $observer->getProduct();
        $productData = $controller->getRequest()->getParam('product');

        if (!array_key_exists('product_tab', $productData) || !is_array($productData['product_tab'])) {
            return;
        }

        foreach ($productData['product_tab'] as $tabId => $value) {
            $key = 'use_config_product_tab_' . $tabId;

            if (!isset($productData[$key]) || !$productData[$key]) {
                continue;
            }

            $productData['product_tab'][$tabId] = '';
        }

        $this->valueResource->saveProductData($product, $productData['product_tab']);
    }
}
