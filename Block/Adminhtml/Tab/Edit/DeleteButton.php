<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Block\Adminhtml\Tab\Edit;

class DeleteButton extends GenericButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    public function getButtonData(): array
    {
        $tabId = $this->getTabId();

        if (!$tabId) {
            return [];
        }

        $data = [
            'label' => __('Delete'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\'' . __(
                'Are you sure you want to do this?'
            ) . '\', \'' . $this->urlBuilder->getUrl('*/*/delete', ['tab_id' => $tabId]) . '\', {data: {}})',
            'sort_order' => 20
        ];

        return $data;
    }
}
