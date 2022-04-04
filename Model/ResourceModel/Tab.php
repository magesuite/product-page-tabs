<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Model\ResourceModel;

class Tab extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $labelsTable;

    protected function _construct(): void
    {
        $this->_init('product_page_tab', \MageSuite\ProductPageTabs\Api\Data\TabInterface::TAB_ID);
        $this->labelsTable = $this->getTable('product_page_tab_label');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $now = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

        if (!$object->getId()) {
            $object->setCreatedAt($now);
        }

        return parent::_beforeSave($object);
    }

    public function getStoreLabels(\MageSuite\ProductPageTabs\Model\Tab $tab): array
    {
        $select = $this->getConnection()->select()
            ->from($this->labelsTable, ['store_id', 'label'])
            ->where('tab_id = ?', $tab->getId());

        return $this->getConnection()->fetchPairs($select);
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->hasStoreLabels()) {
            return parent::_afterSave($object);
        }

        $labels = $object->getStoreLabels();
        $this->getConnection()->delete($this->labelsTable, ['tab_id = ?' => $object->getId()]);
        $data = [];

        foreach ($labels as $storeId => $label) {
            if (empty($label)) {
                continue;
            }
            $data[] = ['tab_id' => $object->getTabId(), 'store_id' => $storeId, 'label' => $label];
        }

        if (!empty($data)) {
            $this->getConnection()->insertMultiple($this->labelsTable, $data);
        }

        return parent::_afterSave($object);
    }
}
