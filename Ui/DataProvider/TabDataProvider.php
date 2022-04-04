<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Ui\DataProvider;

class TabDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \MageSuite\ProductPageTabs\Model\ResourceModel\Tab\Collection
     */
    protected $collection;

    protected \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor;

    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \MageSuite\ProductPageTabs\Model\ResourceModel\Tab\CollectionFactory $collectionFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
    }

    public function getData(): ?array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->getCollection()->getItems();

        /** @var $tab \MageSuite\ProductPageTabs\Model\Tab */
        foreach ($items as $tab) {
            $this->loadedData[$tab->getId()] = $tab->getData();
        }

        $data = $this->dataPersistor->get('productpagetabs_tab');

        if (!empty($data)) {
            $tab = $this->getCollection()->getNewEmptyItem();
            $tab->setData($data);
            $this->loadedData[$tab->getId()] = $tab->getData();
            $this->dataPersistor->clear('productpagetabs_tab');
        }

        return $this->loadedData;
    }
}
