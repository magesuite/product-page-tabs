<?php

namespace MageSuite\ProductPageTabs\Model;

class TabRepository implements \MageSuite\ProductPageTabs\Api\TabRepositoryInterface
{
    /**
     * @var \MageSuite\ProductPageTabs\Api\Data\TabInterface[]
     */
    protected $instancesById = [];

    protected \MageSuite\ProductPageTabs\Model\TabFactory $tabFactory;

    protected \MageSuite\ProductPageTabs\Model\ResourceModel\Tab $tabResource;

    protected \MageSuite\ProductPageTabs\Model\ResourceModel\Tab\CollectionFactory $collectionFactory;

    protected \MageSuite\ProductPageTabs\Api\Data\TabSearchResultsInterfaceFactory $searchResultsFactory;

    protected \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor;

    public function __construct(
        \MageSuite\ProductPageTabs\Model\TabFactory $tabFactory,
        \MageSuite\ProductPageTabs\Model\ResourceModel\Tab $tabResource,
        \MageSuite\ProductPageTabs\Model\ResourceModel\Tab\CollectionFactory $collectionFactory,
        \MageSuite\ProductPageTabs\Api\Data\TabSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
    ) {
        $this->tabFactory = $tabFactory;
        $this->tabResource = $tabResource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param int $tabId
     * @return \MageSuite\ProductPageTabs\Api\Data\TabInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($tabId)
    {
        if (!isset($this->instancesById[$tabId])) {
            $tab = $this->tabFactory->create();
            $tab->load($tabId);
            $this->instancesById[$tabId] = $tab;
        }

        if (!$this->instancesById[$tabId]->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The tab with the "%1" ID doesn\'t exist.', $tabId)
            );
        }

        return $this->instancesById[$tabId];
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \MageSuite\ProductPageTabs\Api\Data\TabSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \MageSuite\ProductPageTabs\Model\ResourceModel\Tab\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);

        foreach ($collection->getItems() as $tab) {
            $this->instancesById[$tab->getId()] = $tab;
        }

        /** @var \MageSuite\ProductPageTabs\Api\Data\TabSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->count());

        return $searchResults;
    }

    /**
     * @param \MageSuite\ProductPageTabs\Api\Data\TabInterface $tab
     * @return \MageSuite\ProductPageTabs\Api\Data\TabInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\MageSuite\ProductPageTabs\Api\Data\TabInterface $tab)
    {
        try {
            $this->tabResource->save($tab);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not save the tab: %1', $exception->getMessage()),
                $exception
            );
        }

        return $tab;
    }

    /**
     * @param \MageSuite\ProductPageTabs\Api\Data\TabInterface $tab
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function delete(\MageSuite\ProductPageTabs\Api\Data\TabInterface $tab)
    {
        try {
            $this->tabResource->delete($tab);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not delete the tab: %1', $exception->getMessage())
            );
        }

        return true;
    }

    /**
     * @param int $tabId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($tabId)
    {
        return $this->delete($this->getById($tabId));
    }
}
