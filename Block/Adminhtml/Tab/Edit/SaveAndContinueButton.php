<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Block\Adminhtml\Tab\Edit;

class SaveAndContinueButton extends GenericButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    public function getButtonData(): array
    {
        $data = [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'on_click' => '',
            'sort_order' => 90,
        ];

        return $data;
    }
}
