<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Model\Source\Product;

class Attribute implements \Magento\Framework\Data\OptionSourceInterface
{
    protected \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository;

    protected \Magento\Framework\Api\Search\SearchCriteriaFactory $searchCriteriaFactory;

    protected \Magento\Framework\Api\SortOrder $sortOrder;

    protected $options;

    public function __construct(
        \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository,
        \Magento\Framework\Api\Search\SearchCriteriaFactory $searchCriteriaFactory,
        \Magento\Framework\Api\SortOrder $sortOrder
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->searchCriteriaFactory = $searchCriteriaFactory;
        $this->sortOrder = $sortOrder;
    }

    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $options = [];

        foreach ($this->getAttributes() as $attribute) {
            $label = $attribute->getDefaultFrontendLabel();

            if (empty($label)) {
                continue;
            }

            $options[] = [
                'value' => $attribute->getAttributeId(),
                'label' => sprintf('%s (%s)', $label, $attribute->getAttributeCode())
            ];
        }

        array_unshift($options, ['value' => '', 'label' => __('-- None --')]);

        $this->options = $options;

        return $this->options;
    }

    protected function getAttributes()
    {
        $searchCriteria = $this->searchCriteriaFactory->create();
        $sortOrder = $this->sortOrder
            ->setField('frontend_label')
            ->setDirection(\Magento\Framework\Api\SortOrder::SORT_ASC);
        $searchCriteria->setSortOrders([$sortOrder]);

        return $this->attributeRepository
            ->getList(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE, $searchCriteria)
            ->getItems();
    }
}
