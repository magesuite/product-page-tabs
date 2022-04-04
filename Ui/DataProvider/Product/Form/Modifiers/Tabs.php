<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Ui\DataProvider\Product\Form\Modifiers;

class Tabs extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    public const PRODUCT_TABS = 'product_tabs';

    protected \Magento\Catalog\Model\Locator\LocatorInterface $locator;

    protected \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository;

    protected \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder;

    protected \Magento\Framework\Api\FilterBuilder $filterBuilder;

    protected \MageSuite\ProductPageTabs\Model\ResourceModel\Tab\Value $valueResource;

    public function __construct(
        \Magento\Catalog\Model\Locator\LocatorInterface $locator,
        \MageSuite\ProductPageTabs\Api\TabRepositoryInterface $tabRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \MageSuite\ProductPageTabs\Model\ResourceModel\Tab\Value $valueResource
    ) {
        $this->locator = $locator;
        $this->tabRepository = $tabRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->valueResource = $valueResource;
    }

    public function modifyData(array $data): array
    {
        $modelId = $this->getProductId();
        $storeId = $this->getStoreId();

        if (!isset($data[$modelId])) {
            return $data;
        }

        $tabs = $this->valueResource->getTabValueForProduct($modelId, $storeId);

        foreach ($tabs as $tabId => $value) {
            $data[$modelId]['product']['product_tab'][$tabId] = $value;
        }

        if (!$storeId) {
            return $data;
        }

        foreach ($this->getTabList()->getItems() as $item) {
            $valueId = $this->valueResource->getTabValueId((int)$item->getTabId(), $modelId, $storeId);
            $data[$modelId]['product']['use_config_product_tab_' . $item->getTabId()] = $valueId ? '0' : '1';
        }

        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        $meta = array_replace_recursive(
            $meta,
            [
                self::PRODUCT_TABS => [
                    'children' => $this->getChildren(),
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Product Tabs'),
                                'dataScope' => self::DATA_SCOPE_PRODUCT,
                                'collapsible' => true,
                                'componentType' => \Magento\Ui\Component\Form\Fieldset::NAME,
                                'sortOrder' => 1000
                            ]
                        ]
                    ]
                ]
            ]
        );

        return $meta;
    }

    protected function getChildren(): array
    {
        $children = [];

        foreach ($this->getTabList()->getItems() as $tab) {
            $children['container_product_tab_' . $tab->getTabId()] = $this->getContainerConfig($tab);
        }

        return $children;
    }

    protected function getContainerConfig(\MageSuite\ProductPageTabs\Api\Data\TabInterface $tab): array
    {
        return [
            'arguments' => [
                'data' =>
                    [
                        'config' =>
                            [
                                'formElement' => 'container',
                                'componentType' => 'container',
                                'breakLine' => true,
                                'label' => $tab->getName(),
                                'component' => 'Magento_Ui/js/form/components/group',
                                'sortOrder' => $tab->getSortOrder(),
                            ]
                    ],
            ],
            'children' => [
                'product_tab_' . $tab->getTabId() => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => \Magento\Ui\Component\Form\Field::NAME,
                                'dataType' => \Magento\Ui\Component\Form\Element\DataType\Text::NAME,
                                'formElement' => \Magento\Ui\Component\Form\Element\Textarea::NAME,
                                'scopeLabel' => __('[STORE VIEW]'),
                                'dataScope' => 'product_tab.' . $tab->getTabId(),
                                'additionalClasses' => 'admin__field-default',
                                'imports' => [
                                    'disabled' =>
                                        'ns = ${ $.ns }, index = use_config_product_tab_' . $tab->getTabId() .':checked',
                                    '__disableTmpl' => ['disabled' => false, 'visible' => false],
                                ]
                            ]
                        ]
                    ]
                ],
                'use_config_product_tab_' . $tab->getTabId() => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'description' => __('Use Default Value'),
                                'dataType' => \Magento\Ui\Component\Form\Element\DataType\Number::NAME,
                                'formElement' => \Magento\Ui\Component\Form\Element\Checkbox::NAME,
                                'componentType' => \Magento\Ui\Component\Form\Field::NAME,
                                'dataScope' => 'use_config_product_tab_' . $tab->getTabId(),
                                'valueMap' => [
                                    'false' => '0',
                                    'true' => '1'
                                ],
                                'visible' => $this->getStoreId() > 0
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    protected function getTabList(): \Magento\Framework\Api\SearchResults
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilters(
            [
                $this->filterBuilder
                    ->setField('attribute_id')
                    ->setConditionType('null')
                    ->create(),
            ]
        );

        return $this->tabRepository->getList($searchCriteria->create());
    }

    protected function getStoreId(): int
    {
        return (int)$this->locator->getStore()->getId();
    }

    protected function getProductId(): int
    {
        return (int)$this->locator->getProduct()->getId();
    }
}
