<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Ui\Component\Listing\Column;

class TabActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected \Magento\Framework\UrlInterface $urlBuilder;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['tab_id'])) {
                continue;
            }

            $editUrlPath = $this->getData('config/editUrlPath') ?: '#';
            $deleteUrlPath = $this->getData('config/deleteUrlPath') ?: '#';
            $tabParamName = 'tab_id';
            $title = $item[$tabParamName];
            $item[$this->getData('name')] = [
                'edit' => [
                    'href' => $this->urlBuilder->getUrl(
                        $editUrlPath,
                        [
                            $tabParamName => $item[$tabParamName]
                        ]
                    ),
                    'label' => __('Edit')
                ],
                'delete' => [
                    'href' => $this->urlBuilder->getUrl(
                        $deleteUrlPath,
                        [
                            $tabParamName => $item[$tabParamName]
                        ]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete record #%1', $title),
                        'message' => __('Are you sure you want to delete a record #%1?', $title)
                    ],
                    'post' => true
                ]
            ];
        }

        return $dataSource;
    }
}
