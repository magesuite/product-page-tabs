<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Api;

interface TabRepositoryInterface
{
    /**
     * @param int $tabId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return \MageSuite\ProductPageTabs\Api\Data\TabInterface
     */
    public function getById($tabId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MageSuite\ProductPageTabs\Api\Data\TabSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \MageSuite\ProductPageTabs\Api\Data\TabInterface $tab
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return bool
     */
    public function save(\MageSuite\ProductPageTabs\Api\Data\TabInterface $tab);

    /**
     * @param \MageSuite\ProductPageTabs\Api\Data\TabInterface $tab
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return bool
     */
    public function delete(\MageSuite\ProductPageTabs\Api\Data\TabInterface $tab);

    /**
     * @param int $tabId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return bool
     */
    public function deleteById($tabId);
}
