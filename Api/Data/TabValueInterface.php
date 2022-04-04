<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Api\Data;

interface TabValueInterface
{
    const VALUE_ID = 'value_id';
    const TAB_ID = 'tab_id';
    const PRODUCT_ID = 'product_id';
    const STORE_ID = 'store_id';
    const VALUE = 'value';

    /**
     * @return int
     */
    public function getValueId();

    /**
     * @param int $valueId
     * @return TabValueInterface
     */
    public function setValueId(int $valueId);

    /**
     * @return int
     */
    public function getTabId();

    /**
     * @param int $tabId
     * @return TabValueInterface
     */
    public function setTabId(int $tabId);

    /**
     * @return int
     */
    public function getProductId();

    /**
     * @param int $productId
     * @return TabValueInterface
     */
    public function setProductId(int $productId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param int $storeId
     * @return TabValueInterface
     */
    public function setStoreId(int $storeId);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return TabValueInterface
     */
    public function setValue(string $value);
}
