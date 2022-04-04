<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Model;

class Tab extends \Magento\Framework\Model\AbstractModel implements \MageSuite\ProductPageTabs\Api\Data\TabInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'productpagetabs';

    protected $_cacheTag = self::CACHE_TAG; //phpcs:ignore

    protected $_eventPrefix = self::CACHE_TAG; //phpcs:ignore

    protected function _construct()
    {
        $this->_init(\MageSuite\ProductPageTabs\Model\ResourceModel\Tab::class);
    }

    public function getTabId()
    {
        return (int)$this->_getData(self::TAB_ID);
    }

    public function setTabId(int $tabId)
    {
        return $this->setData(self::TAB_ID, $tabId);
    }

    public function getName()
    {
        return $this->_getData(self::NAME);
    }

    public function setName(string $name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function getAttributeId()
    {
        return $this->_getData(self::ATTRIBUTE_ID);
    }

    public function setAttributeId(int $attributeId)
    {
        return $this->setData(self::ATTRIBUTE_ID, $attributeId);
    }

    public function getSortOrder()
    {
        return $this->_getData(self::SORT_ORDER);
    }

    public function setSortOrder(int $sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    public function getCreatedAt()
    {
        return $this->_getData(self::CREATED_AT);
    }

    public function setCreatedAt(string $createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getStoreLabels()
    {
        if ($this->hasData('store_labels')) {
            return $this->_getData('store_labels');
        }

        $labels = $this->_getResource()->getStoreLabels($this);
        $this->setData('store_labels', $labels);

        return $labels;
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
