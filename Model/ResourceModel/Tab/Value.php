<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Model\ResourceModel\Tab;

class Value extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected \Magento\Framework\Stdlib\StringUtils $string;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\StringUtils $string,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->string = $string;
    }

    protected function _construct(): void
    {
        $this->_init('product_page_tab_value', \MageSuite\ProductPageTabs\Api\Data\TabValueInterface::TAB_ID);
    }

    public function getTabValueForProduct(int $productId, $storeId = null): array
    {
        $select = $this->getConnection()->select()
            ->from(['main_table' => $this->getTable('product_page_tab')], ['tab_id'])
            ->join(
                ['t1' => $this->getMainTable()],
                'main_table.tab_id = t1.tab_id',
                ['t1.value']
            )
            ->where('t1.product_id = ?', $productId)
            ->where('t1.store_id = ?', \Magento\Store\Model\Store::DEFAULT_STORE_ID)
            ->order('main_table.sort_order ASC');

        if ($storeId) {
            $conditions = [
                'main_table.tab_id = t2.tab_id',
                't1.product_id = t2.product_id',
                $this->getConnection()->quoteInto('t2.store_id = ?', $storeId),
            ];
            $ifValueId = $this->getConnection()->getIfNullSql('t2.value', 't1.value');
            $select->joinLeft(
                ['t2' => $this->getMainTable()],
                implode(' AND ', $conditions),
                null
            )->reset(
                \Magento\Framework\DB\Select::COLUMNS
            )->columns([
                'main_table.tab_id',
                $ifValueId
            ]);
        }

        return $this->getConnection()->fetchPairs($select);
    }

    public function saveProductData(\Magento\Catalog\Api\Data\ProductInterface $product, array $tabs): self
    {
        $connection = $this->getConnection();
        $deleteData = [];
        $data = [];
        $productId = (int)$product->getId();
        $storeId = (int)$product->getStoreId();

        foreach ($tabs as $tabId => $value) {
            if ($storeId > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                && $this->string->strlen($value) === 0) {
                $deleteData[] = [
                    'tab_id=?' => $tabId,
                    'product_id=?' => $productId,
                    'store_id=?' => $storeId
                ];
            } else {
                $data[] = [
                    'tab_id' => $tabId,
                    'product_id' => $productId,
                    'store_id' => $storeId,
                    'value' => $this->string->strlen($value) ? $value : null
                ];

                if ($storeId > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    && !$this->getTabValueId($tabId, $productId, \Magento\Store\Model\Store::DEFAULT_STORE_ID)) {
                    $data[] = [
                        'tab_id' => $tabId,
                        'product_id' => $productId,
                        'store_id' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        'value' => null
                    ];
                }
            }
        }

        $connection->beginTransaction();

        try {
            if (!empty($deleteData)) {
                foreach ($deleteData as $deleteCondition) {
                    $connection->delete(
                        $this->getMainTable(),
                        $deleteCondition
                    );
                }
            }

            if (!empty($data)) {
                $connection->insertOnDuplicate(
                    $this->getMainTable(),
                    $data,
                    ['tab_id', 'product_id', 'store_id', 'value']
                );
            }
        } catch (\Exception $e) {
            $connection->rollBack();
            throw $e;
        }

        $connection->commit();

        return $this;
    }

    public function getTabValueId(int $tabId, int $productId, int $storeId): int
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), ['value_id'])
            ->where('tab_id = ?', $tabId)
            ->where('product_id = ?', $productId)
            ->where('store_id = ?', $storeId);

        return (int)$this->getConnection()->fetchOne($select);
    }
}
