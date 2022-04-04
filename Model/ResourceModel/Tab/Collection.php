<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Model\ResourceModel\Tab;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(\MageSuite\ProductPageTabs\Model\Tab::class, \MageSuite\ProductPageTabs\Model\ResourceModel\Tab::class);
    }
}
