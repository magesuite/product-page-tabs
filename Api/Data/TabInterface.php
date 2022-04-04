<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Api\Data;

interface TabInterface
{
    const TAB_ID = 'tab_id';
    const NAME = 'name';
    const ATTRIBUTE_ID = 'attribute_id';
    const SORT_ORDER = 'sort_order';
    const CREATED_AT = 'created_at';

    /**
     * @return int
     */
    public function getTabId();

    /**
     * @param int $tabId
     * @return TabInterface
     */
    public function setTabId(int $tabId);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return TabInterface
     */
    public function setName(string $name);

    /**
     * @return int
     */
    public function getAttributeId();

    /**
     * @param int $attributeId
     * @return TabInterface
     */
    public function setAttributeId(int $attributeId);

    /**
     * @return int
     */
    public function getSortOrder();

    /**
     * @param int $sortOrder
     * @return TabInterface
     */
    public function setSortOrder(int $sortOrder);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return TabInterface
     */
    public function setCreatedAt(string $createdAt);
}
