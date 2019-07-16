<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Services\Details\Tabs\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('oa_service_form');
        $this->setTitle(__('Service Information'));
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_service');
        $values = $model->getData();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post'
            ]
        ]);

        $form->setHtmlIdPrefix('service_');

        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('General Information'),
            'class' => 'fieldset-wide'
        ]);
        if ($model->getId()) {
            $fieldset->addField('service_id', 'hidden', [
                'name' => 'service_id'
            ]);
        }
        $fieldset->addField('product_id', 'text', [
            'name' => 'product_id',
            'label' => __('Product ID'),
            'title' => __('Product ID'),
            'required' => true
        ]);
        $fieldset->addField('name', 'text', [
            'name' => 'name',
            'label' => __('Name'),
            'title' => __('Name'),
        ]);
        $fieldset->addField('expire_at', 'text', [
            'name' => 'expire_at',
            'label' => __('Expire At'),
            'title' => __('Expire At'),
            'required' => true
        ]);

        $form->setValues($values);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
