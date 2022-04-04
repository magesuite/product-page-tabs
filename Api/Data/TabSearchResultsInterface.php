<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Api\Data;

interface TabSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \MageSuite\ProductPageTabs\Api\Data\TabInterface[]
     */
    public function getItems();

    /**
     * @return \MageSuite\ProductPageTabs\Api\Data\TabInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
