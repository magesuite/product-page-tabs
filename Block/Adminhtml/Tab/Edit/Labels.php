<?php
declare(strict_types=1);

namespace MageSuite\ProductPageTabs\Block\Adminhtml\Tab\Edit;

class Labels extends \Magento\Backend\Block\Widget\Form\Generic implements
    \Magento\Ui\Component\Layout\Tabs\TabInterface
{
    protected $_nameInLayout = 'store_view_labels'; //phpcs:ignore

    protected \MageSuite\ProductPageTabs\Model\TabFactory $tabFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \MageSuite\ProductPageTabs\Model\TabFactory $tabFactory,
        array $data = []
    ) {
        $this->tabFactory = $tabFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    public function getTabClass()
    {
        return null;
    }

    public function getTabUrl()
    {
        return null;
    }

    public function isAjaxLoaded()
    {
        return false;
    }

    public function getTabLabel()
    {
        return __('Labels');
    }

    public function getTabTitle()
    {
        return __('Labels');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    protected function _prepareForm()
    {
        $tab = $this->_coreRegistry->registry(\MageSuite\ProductPageTabs\Model\RegistryConstants::CURRENT_PRODUCT_PAGE_TAB);

        if (!$tab) {
            $id = $this->getRequest()->getParam('tab_id');
            $tab = $this->tabFactory->create();
            $tab->load($id);
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('tab_');
        $labels = $tab->getStoreLabels();
        $fieldset = $this->_createStoreSpecificFieldset($form, $labels);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _createStoreSpecificFieldset(\Magento\Framework\Data\Form $form, array $labels): \Magento\Framework\Data\Form\Element\Fieldset
    {
        $fieldset = $form->addFieldset(
            'store_labels_fieldset',
            ['legend' => __('Store View Specific Labels'), 'class' => 'store-scope']
        );
        $renderer = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset::class
        );
        $fieldset->setRenderer($renderer);

        foreach ($this->_storeManager->getWebsites() as $website) {
            $fieldset->addField(
                "w_{$website->getId()}_label",
                'note',
                ['label' => $website->getName(), 'fieldset_html_class' => 'website']
            );

            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();

                if (count($stores) == 0) {
                    continue;
                }

                $fieldset->addField(
                    "sg_{$group->getId()}_label",
                    'note',
                    ['label' => $group->getName(), 'fieldset_html_class' => 'store-group']
                );

                foreach ($stores as $store) {
                    $fieldset->addField(
                        "s_{$store->getId()}",
                        'text',
                        [
                            'name' => 'store_labels[' . $store->getId() . ']',
                            'title' => $store->getName(),
                            'label' => $store->getName(),
                            'required' => true,
                            'value' => $labels[$store->getId()] ?? '',
                            'fieldset_html_class' => 'store',
                            'data-form-part' => 'productpagetabs_form'
                        ]
                    );
                }
            }
        }

        return $fieldset;
    }
}
