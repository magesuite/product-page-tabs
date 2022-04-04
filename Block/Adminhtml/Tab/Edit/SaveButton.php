<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Block\Adminhtml\Tab\Edit;

class SaveButton extends GenericButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    public function getButtonData(): array
    {
        $data = [
            'label' => __('Save'),
            'class' => 'save primary',
            'on_click' => '',
        ];

        return $data;
    }
}
